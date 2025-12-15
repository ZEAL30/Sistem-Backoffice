@php
    use App\Models\FooterSection;
    $sections = FooterSection::getActiveSections();
    $columnSections = $sections->where('type', 'column');
    $menuSections = $sections->where('type', 'menu');
    $contactSections = $sections->where('type', 'contact');
    $copyrightSection = $sections->where('type', 'copyright')->first();

    // Jika tidak ada data, gunakan default
    if ($sections->isEmpty()) {
        $columnSections = collect([
            (object)[
                'title' => 'About Company',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis.'
            ]
        ]);
        $menuSections = collect([
            (object)[
                'title' => 'Menu',
                'data' => [
                    ['label' => 'Home', 'url' => url('/')],
                    ['label' => 'About Us', 'url' => url('/about')],
                    ['label' => 'Product', 'url' => url('/product')],
                    ['label' => 'Contact', 'url' => url('/contact')],
                ]
            ]
        ]);
        $contactSections = collect([
            (object)[
                'title' => 'Contact Info',
                'data' => [
                    'phone' => '+6285161728383',
                    'email' => 'gecgroup@gmail.com',
                    'address' => 'Ruko Mangga Dua Plaza Blok I No 29<br>Mangga Dua Jakarta Pusat 10730'
                ]
            ]
        ]);
        $copyrightSection = (object)['content' => 'Copyright © 2025. gecgroups.co.id. All right reserved.'];
    }
@endphp

<footer class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

        @foreach($columnSections as $section)
        <!-- Column Section -->
        <div>
            @if($section->title)
            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $section->title }}</h3>
            @endif
            @if($section->content)
            <p class="text-gray-600 leading-relaxed">
                {!! nl2br(e($section->content)) !!}
            </p>
            @endif
        </div>
        @endforeach

        @foreach($menuSections as $section)
        <!-- Menu Section -->
        <div>
            @if($section->title)
            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $section->title }}</h3>
            @endif
            @if($section->data && is_array($section->data))
            <ul class="space-y-2 text-gray-700">
                @foreach($section->data as $item)
                <li><a href="{{ url($item['url'] ?? '#') }}" class="hover:text-[#006666]">{{ $item['label'] ?? '' }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
        @endforeach

        @foreach($contactSections as $section)
        <!-- Contact Info Section -->
        <div>
            @if($section->title)
            <h3 class="text-2xl font-bold text-gray-900 mb-8">{{ $section->title }}</h3>
            @endif

            @if($section->data)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                @if(isset($section->data['phone']))
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">📞</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Phone:</p>
                        <p class="text-gray-700 text-sm">{{ $section->data['phone'] }}</p>
                    </div>
                </div>
                @endif

                @if(isset($section->data['email']))
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">✉️</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Email:</p>
                        <p class="text-gray-700 text-sm">{{ $section->data['email'] }}</p>
                    </div>
                </div>
                @endif
            </div>

            @if(isset($section->data['address']))
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">📍</div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Alamat</p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {!! nl2br(e($section->data['address'])) !!}
                    </p>
                </div>
            </div>
            @endif
            @endif
        </div>
        @endforeach
    </div>

    @if($copyrightSection && $copyrightSection->content)
    <div class="text-center text-gray-600 text-sm mt-12 border-t border-gray-300 pt-8 max-w-7xl mx-auto px-6">
        {!! nl2br(e($copyrightSection->content)) !!}
    </div>
    @endif
</footer>
