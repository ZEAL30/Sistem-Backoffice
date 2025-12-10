{{-- resources/views/home.blade.php --}}

@extends('Content.layout.app')

@section('content')

<!-- Hero Section -->
<section class="text-white py-40" style="background: linear-gradient(45deg, #378981, #5BAF9F);">
    <div class="max-w-7xl mx-auto pl-5 pr-5 flex flex-col lg:flex-row items-center gap-6    ">
        <div class="lg:w-1/2 space-y-4 ">
            <div class="bg-[#006666] px-4 py-2 rounded-full text-sm font-semibold uppercase w-70">WAKTU YANG PAS BUAT UPGRADE PC!</div>
            <h1 class="text-4xl lg:text-5xl font-bold">DESAIN PC SESUAI KEBUTUHANMU</h1>
            <p class="text-lg text-white/90">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.</p>
            <a href="{{ url('/contact') }}" class="inline-block bg-[#006666]  hover:bg-teal-700 transition px-8 py-3 rounded-full font-semibold shadow-lg">CONTACT US +</a>
        </div>
        <div class="lg:w-1/2 text-center">
            <img src="{{ asset('storage/media/hero-sec.webp') }}" alt="PC Components" class="mx-auto w-full max-w-md">
        </div>
    </div>
</section>

<!-- Category Cards -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto pl-5 pr-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
        @php
        $categories = [
            ['title'=>'LAPTOP','img'=>'cat-laptop.png','gradient'=>'from-teal-700 to-teal-400'],
            ['title'=>'KOMPUTER','img'=>'cat-pc.png','gradient'=>'from-teal-800 to-teal-500'],
            ['title'=>'KOMPONEN','img'=>'cat-komponen.png','gradient'=>'from-teal-800 to-teal-500'],
            ['title'=>'PELENGKAPAN','img'=>'cat-peripheral.png','gradient'=>'from-teal-700 to-teal-400'],
        ];
        @endphp

        @foreach($categories as $cat)
        <div class="relative overflow-hidden rounded-2xl shadow-lg group">
            <div class="p-8 bg-gradient-to-br {{ $cat['gradient'] }} text-white h-64 flex flex-col justify-between">
                <h3 class="text-xl font-bold">{{ $cat['title'] }}</h3>
                <p class="mt-4 font-semibold">Shop Now +</p>
            </div>
            <img src="{{ asset('images/'.$cat['img']) }}" alt="{{ $cat['title'] }}" class="absolute right-0 bottom-0 h-4/5 transition-transform group-hover:translate-x-2">
        </div>
        @endforeach
    </div>
</section>

<!-- Testimonial Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto pl-5 pr-5 text-center space-y-6">
        <h2 class="text-3xl font-bold text-teal-700">Apa Kata Mereka Tentang Kami</h2>
        <p class="text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-50 rounded-2xl p-6 shadow-lg">
                <div class="flex justify-center mb-4">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center shadow">
                        <i class="bi bi-person-fill text-gray-400 text-3xl"></i>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Testimonial #1</h5>
                <p class="text-gray-400 text-sm mb-2">Designation</p>
                <p class="text-gray-600 text-sm">freestar freestar Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-6 shadow-lg">
                <div class="flex justify-center mb-4">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center shadow">
                        <i class="bi bi-person-fill text-gray-400 text-3xl"></i>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Testimonial #2</h5>
                <p class="text-gray-400 text-sm mb-2">Designation</p>
                <p class="text-gray-600 text-sm">freestar freestar Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto pl-5 pr-5 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition-all duration-300 hover:-translate-y-[5px]">
            <i class="bi bi-truck text-teal-600 text-4xl"></i>
            <h5 class="mt-4 font-bold">Gratis Ongkir</h5>
            <p class="text-gray-500 text-sm mt-2">Lorem ipsum dolor sit amet, jsdahdw</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition-all duration-300 hover:-translate-y-[5px]">
            <i class="bi bi-currency-dollar text-teal-600 text-4xl"></i>
            <h5 class="mt-4 font-bold">Harga Terbaik</h5>
            <p class="text-gray-500 text-sm mt-2">Lorem ipsum dolor sit amet, jsdahdw</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
            <i class="bi bi-shield-check text-teal-600 text-4xl"></i>
            <h5 class="mt-4 font-bold">Garansi Resmi</h5>
            <p class="text-gray-500 text-sm mt-2">Lorem ipsum dolor sit amet, jsdahdw</p>
        </div>
    </div>
</section>

@endsection
