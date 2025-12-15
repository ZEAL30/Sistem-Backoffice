<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentSecurityService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $securityService;

    public function __construct(CommentSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Display all comments for admin
     */
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $comments = Comment::with('post', 'user')->latest();

        // Filter by status if requested
        if (request('status') === 'approved') {
            $comments->where('is_approved', true);
        } elseif (request('status') === 'pending') {
            $comments->where('is_approved', false);
        }

        $comments = $comments->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Store a new comment (public) with security checks
     */
    public function store(Request $request, Post $post)
    {
        // Basic validation
        $validated = $request->validate([
            'author_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.]+$/',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:1000',
        ]);

        // Security and spam check
        $security_check = $this->securityService->validateComment($request, $validated['content']);

        // If spam score too high, reject
        if (!$security_check['is_allowed']) {
            return redirect()->route('blog.show', $post->slug)
                ->with('error', '⚠️ Komentar Anda ditolak karena alasan keamanan: ' . implode(', ', $security_check['issues']));
        }

        // Create comment with security data
        $comment = $post->comments()->create([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'user_id' => auth()->id() ?? null,
            'is_approved' => $security_check['spam_status'] === 'clean' ? true : false, // Auto-approve clean comments
            'ip_address' => $security_check['ip_address'],
            'user_agent' => $security_check['user_agent'],
            'spam_score' => $security_check['spam_score'],
            'spam_status' => $security_check['spam_status'],
            'contains_links' => substr_count($validated['content'], 'http://') + substr_count($validated['content'], 'https://') > 0,
            'char_count' => strlen($validated['content']),
        ]);

        // Update rate limiting
        $this->securityService->recordCommentSubmission(
            $security_check['ip_address'],
            $validated['author_email']
        );

        $message = $security_check['spam_status'] === 'clean'
            ? '✅ Komentar Anda berhasil dikirim!'
            : '⏳ Komentar Anda berhasil dikirim dan sedang diperiksa admin untuk keamanan';

        return redirect()->route('blog.show', $post->slug)
            ->with('success', $message);
    }

    /**
     * Approve a comment (admin only)
     */
    public function approve(Comment $comment)
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $comment->update(['is_approved' => true, 'is_flagged' => false]);

        return redirect()->back()->with('success', '✅ Komentar berhasil disetujui');
    }

    /**
     * Reject a comment (admin only)
     */
    public function reject(Comment $comment)
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $comment->update(['is_approved' => false]);

        return redirect()->back()->with('success', '✅ Komentar ditolak');
    }

    /**
     * Flag a comment as suspicious/spam (admin only)
     */
    public function flag(Request $request, Comment $comment)
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'flag_reason' => 'required|string|max:255',
        ]);

        $comment->update([
            'is_flagged' => true,
            'flag_reason' => $validated['flag_reason'],
            'spam_status' => 'spam',
        ]);

        return redirect()->back()->with('success', '🚩 Komentar berhasil ditandai sebagai spam');
    }

    /**
     * Delete a comment (admin only)
     */
    public function destroy(Comment $comment)
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        return redirect()->back()->with('success', '✅ Komentar berhasil dihapus');
    }

    // ==================== API METHODS ====================

    /**
     * API Store Comment (Public)
     */
    public function apiStore(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $validated = $request->validate([
            'author_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\-\.]+$/',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:1000',
        ]);

        // Security and spam check
        $security_check = $this->securityService->validateComment($request, $validated['content']);

        // If spam score too high, reject
        if (!$security_check['is_allowed']) {
            return response()->json([
                'success' => false,
                'message' => 'Comment rejected for security reasons',
                'issues' => $security_check['issues'],
            ], 403);
        }

        // Create comment with security data
        $comment = $post->comments()->create([
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'user_id' => auth()->id() ?? null,
            'is_approved' => $security_check['spam_status'] === 'clean' ? true : false,
            'ip_address' => $security_check['ip_address'],
            'user_agent' => $security_check['user_agent'],
            'spam_score' => $security_check['spam_score'],
            'spam_status' => $security_check['spam_status'],
            'contains_links' => substr_count($validated['content'], 'http://') + substr_count($validated['content'], 'https://') > 0,
            'char_count' => strlen($validated['content']),
        ]);

        // Update rate limiting
        $this->securityService->recordCommentSubmission(
            $security_check['ip_address'],
            $validated['author_email']
        );

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => $security_check['spam_status'] === 'clean'
                ? 'Comment submitted successfully'
                : 'Comment submitted and pending admin approval',
            'data' => $comment,
        ], 201);
    }

    /**
     * API List Comments
     */
    public function apiIndex(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role?->name !== 'Administrator') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $query = Comment::with('post', 'user')->latest();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by post
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        $perPage = $request->get('per_page', 20);
        $comments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    /**
     * API Approve Comment
     */
    public function apiApprove(Comment $comment)
    {
        if (auth()->user()->role?->name !== 'Administrator') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->update(['is_approved' => true, 'is_flagged' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Comment approved successfully',
            'data' => $comment,
        ]);
    }

    /**
     * API Reject Comment
     */
    public function apiReject(Comment $comment)
    {
        if (auth()->user()->role?->name !== 'Administrator') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->update(['is_approved' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Comment rejected successfully',
            'data' => $comment,
        ]);
    }

    /**
     * API Flag Comment
     */
    public function apiFlag(Request $request, Comment $comment)
    {
        if (auth()->user()->role?->name !== 'Administrator') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'flag_reason' => 'required|string|max:255',
        ]);

        $comment->update([
            'is_flagged' => true,
            'flag_reason' => $validated['flag_reason'],
            'spam_status' => 'spam',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment flagged successfully',
            'data' => $comment,
        ]);
    }

    /**
     * API Destroy Comment
     */
    public function apiDestroy(Comment $comment)
    {
        if (auth()->user()->role?->name !== 'Administrator') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
