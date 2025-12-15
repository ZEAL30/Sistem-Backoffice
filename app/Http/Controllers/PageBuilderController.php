<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\TestimonialSection;
use App\Models\NavigationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageBuilderController extends Controller
{
    /**
     * Display page builder dashboard
     */
    public function index()
    {
        $hero = HeroSection::getActive();
        $testimonials = TestimonialSection::getActive();
        $navItems = NavigationItem::getActive();

        return view('admin.page-builder.index', compact('hero', 'testimonials', 'navItems'));
    }

    // ==================== HERO SECTION ====================

    public function heroIndex()
    {
        $heroes = HeroSection::orderBy('order')->get();
        return view('admin.page-builder.hero.index', compact('heroes'));
    }

    public function heroEdit()
    {
        $hero = HeroSection::getActive();

        // Create default if none exists
        if (!$hero) {
            $this->createDefaultHero();
            $hero = HeroSection::getActive();
        }

        return view('admin.page-builder.hero.edit', compact('hero'));
    }

    public function heroUpdate(Request $request)
    {
        $request->validate([
            'badge_text' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_gradient' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $hero = HeroSection::findOrFail($request->id);

        $data = $request->only([
            'badge_text', 'title', 'description', 'button_text',
            'button_url', 'background_gradient', 'is_active'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($hero->image) {
                Storage::disk('public')->delete($hero->image);
            }
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        $hero->update($data);

        return redirect()->route('page-builder.hero.index')->with('success', 'Hero section berhasil diperbarui!');
    }

    private function createDefaultHero()
    {
        HeroSection::create([
            'badge_text' => 'WAKTU YANG PAS BUAT UPGRADE PC!',
            'title' => 'DESAIN PC SESUAI KEBUTUHANMU',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.',
            'button_text' => 'CONTACT US +',
            'button_url' => '/contact',
            'image' => 'media/hero-sec.webp',
            'background_gradient' => '45deg, #378981, #5BAF9F',
            'order' => 1,
            'is_active' => true,
        ]);
    }

    // ==================== TESTIMONIAL SECTION ====================

    public function testimonialIndex()
    {
        $testimonials = TestimonialSection::orderBy('order')->get();
        return view('admin.page-builder.testimonial.index', compact('testimonials'));
    }

    public function testimonialEdit($id = null)
    {
        if ($id) {
            $testimonial = TestimonialSection::findOrFail($id);
        } else {
            $testimonial = new TestimonialSection();
        }

        return view('admin.page-builder.testimonial.edit', compact('testimonial'));
    }

    public function testimonialStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'testimonial' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'designation', 'testimonial', 'order', 'is_active']);
        // Ensure order is not null to avoid DB constraint error
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        if ($request->id) {
            $testimonial = TestimonialSection::findOrFail($request->id);
            if ($request->hasFile('avatar') && $testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $testimonial->update($data);
            $message = 'Testimonial berhasil diperbarui!';
        } else {
            TestimonialSection::create($data);
            $message = 'Testimonial berhasil ditambahkan!';
        }

        return redirect()->route('page-builder.testimonial.index')->with('success', $message);
    }

    public function testimonialDestroy($id)
    {
        $testimonial = TestimonialSection::findOrFail($id);
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }
        $testimonial->delete();

        return redirect()->route('page-builder.testimonial.index')->with('success', 'Testimonial berhasil dihapus!');
    }

    // ==================== NAVIGATION SECTION ====================

    public function navigationIndex()
    {
        $items = NavigationItem::orderBy('order')->get();
        return view('admin.page-builder.navigation.index', compact('items'));
    }

    public function navigationEdit($id = null)
    {
        if ($id) {
            $item = NavigationItem::findOrFail($id);
        } else {
            $item = new NavigationItem();
        }

        return view('admin.page-builder.navigation.edit', compact('item'));
    }

    public function navigationStore(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'route_pattern' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'target' => 'nullable|in:_self,_blank',
        ]);

        $data = $request->only(['label', 'url', 'route_pattern', 'order', 'is_active', 'target']);

        if ($request->id) {
            $item = NavigationItem::findOrFail($request->id);
            $item->update($data);
            $message = 'Navigation item berhasil diperbarui!';
        } else {
            NavigationItem::create($data);
            $message = 'Navigation item berhasil ditambahkan!';
        }

        return redirect()->route('page-builder.navigation.index')->with('success', $message);
    }

    public function navigationDestroy($id)
    {
        NavigationItem::findOrFail($id)->delete();
        return redirect()->route('page-builder.navigation.index')->with('success', 'Navigation item berhasil dihapus!');
    }

    public function navigationReorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:navigation_items,id',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            NavigationItem::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}

