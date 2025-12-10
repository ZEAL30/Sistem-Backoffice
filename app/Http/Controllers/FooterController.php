<?php

namespace App\Http\Controllers;

use App\Models\FooterSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FooterController extends Controller
{
    /**
     * Display a listing of footer sections
     */
    public function index()
    {
        $sections = FooterSection::orderBy('order')->get();
        return view('admin.footer.index', compact('sections'));
    }

    /**
     * Show the form for editing footer sections
     */
    public function edit()
    {
        $sections = FooterSection::orderBy('order')->get();
        
        // If no sections exist, create default ones
        if ($sections->isEmpty()) {
            $this->createDefaultSections();
            $sections = FooterSection::orderBy('order')->get();
        }

        return view('admin.footer.edit', compact('sections'));
    }

    /**
     * Update footer sections
     */
    public function update(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'nullable|exists:footer_sections,id',
            'sections.*.type' => 'required|string',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.content' => 'nullable|string',
            'sections.*.data' => 'nullable|array',
            'sections.*.order' => 'required|integer',
            'sections.*.is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $existingIds = [];

            foreach ($request->sections as $sectionData) {
                if (isset($sectionData['id']) && $sectionData['id']) {
                    // Update existing section
                    $section = FooterSection::findOrFail($sectionData['id']);
                    $section->update([
                        'type' => $sectionData['type'],
                        'title' => $sectionData['title'] ?? null,
                        'content' => $sectionData['content'] ?? null,
                        'data' => $sectionData['data'] ?? null,
                        'order' => $sectionData['order'],
                        'is_active' => $sectionData['is_active'] ?? true,
                    ]);
                    $existingIds[] = $section->id;
                } else {
                    // Create new section
                    $section = FooterSection::create([
                        'type' => $sectionData['type'],
                        'title' => $sectionData['title'] ?? null,
                        'content' => $sectionData['content'] ?? null,
                        'data' => $sectionData['data'] ?? null,
                        'order' => $sectionData['order'],
                        'is_active' => $sectionData['is_active'] ?? true,
                    ]);
                    $existingIds[] = $section->id;
                }
            }

            // Delete sections that are not in the request
            FooterSection::whereNotIn('id', $existingIds)->delete();

            DB::commit();
            return redirect()->route('footer.index')->with('success', 'Footer berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Create default footer sections
     */
    private function createDefaultSections()
    {
        $defaultSections = [
            [
                'type' => 'column',
                'title' => 'About Company',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'type' => 'menu',
                'title' => 'Menu',
                'data' => [
                    ['label' => 'Home', 'url' => '/'],
                    ['label' => 'About Us', 'url' => '/about'],
                    ['label' => 'Product', 'url' => '/product'],
                    ['label' => 'Contact', 'url' => '/contact'],
                ],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'type' => 'contact',
                'title' => 'Contact Info',
                'data' => [
                    'phone' => '+6285161728383',
                    'email' => 'gecgroup@gmail.com',
                    'address' => 'Ruko Mangga Dua Plaza Blok I No 29<br>Mangga Dua Jakarta Pusat 10730',
                ],
                'order' => 3,
                'is_active' => true,
            ],
            [
                'type' => 'copyright',
                'content' => 'Copyright © 2025. gecgroups.co.id. All right reserved.',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($defaultSections as $section) {
            FooterSection::create($section);
        }
    }
}

