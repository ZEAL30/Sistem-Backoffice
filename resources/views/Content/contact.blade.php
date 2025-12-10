{{-- resources/views/home.blade.php --}}

@extends('Content.layout.app')

@section('content')

<!-- Hero Section -->
<section class="relative w-full">
    <!-- Background image -->
    <img src="{{ asset('storage/media/breadcrumb2.png') }}"
         class="w-full h-[320px] object-cover brightness-75" />

    <!-- Title absolute -->
    <h1 class="absolute inset-0 flex items-center justify-center
               text-white text-4xl font-bold">
        Categories
    </h1>

</section>

<!-- Informasi -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto pl-5 pr-5">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Kiri: Info Kontak -->
            <div class="space-y-10">
                <!-- Informasi Kontak -->
                <div class="space-y-10">
                    <h2 class="text-4xl font-bold text-teal-600 mb-7">Informasi Kontak</h2>
                    <p class="text-gray-700 mb-16 max-w-2xl mx-auto">
                    Punya pertanyaan atau ingin mulai bekerja sama? Silakan hubungi kami:
                </p>
                </div>
                <!-- Lokasi -->
                <div class="flex gap-6">
                    <div class="w-14 h-14 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">Lokasi</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Ruko Mangga Dua Plaza Blok I No 29<br>
                            Mangga Dua Jakarta Pusat 10730
                        </p>
                    </div>
                </div>

                <!-- Telepon -->
                <div class="flex gap-6">
                    <div class="w-14 h-14 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">Telepon</h3>
                        <p class="text-gray-700">+62 85161728383</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex gap-6">
                    <div class="w-14 h-14 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">Email</h3>
                        <p class="text-gray-700">gecgroup@gmail.com</p>
                    </div>
                </div>
            </div>

            <!-- Kanan: Google Maps -->
            <div class="w-full h-96 lg:h-full min-h-96 rounded-xl overflow-hidden shadow-xl">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7933.902921307566!2d106.829012!3d-6.137224!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5f8256e3e03%3A0x68690db632600085!2sPT.%20Graha%20Eka%20Cipta!5e0!3m2!1sid!2sus!4v1765158304945!5m2!1sid!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>


<!-- Layanan Kami -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-teal-50">
    <div class="max-w-7xl mx-auto pl-5 pr-5 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Kiri: Google Maps -->
        <div class="order-2 lg:order-1">
            <div class="rounded-2xl overflow-hidden shadow-2xl">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7933.902921307566!2d106.829012!3d-6.137224!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5f8256e3e03%3A0x68690db632600085!2sPT.%20Graha%20Eka%20Cipta!5e0!3m2!1sid!2sus!4v1765158304945!5m2!1sid!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    width="100%"
                    height="520"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Kanan: Marketplace & Pertanyaan -->
        <div class="order-1 lg:order-2 space-y-8">
            <!-- Marketplace Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-shadow">
                <h3 class="text-3xl font-bold text-teal-700 mb-4">MARKETPLACE</h3>
                <p class="text-gray-600 mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="inline-flex items-center gap-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-4 rounded-full transition">
                    Mulai Telusuri
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>

            <!-- Pertanyaan Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-shadow">
                <h3 class="text-3xl font-bold text-teal-700 mb-4">PERTANYAAN</h3>
                <p class="text-gray-600 mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#kontak" class="inline-flex items-center gap-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-4 rounded-full transition">
                    CONTACT US
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</section>


<!-- Features Section -->
<div class="max-w-7xl mx-auto pl-5 pr-5">

        <!-- Judul Utama -->
        <div class="text-center mt-16 mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-teal-700 mb-4">
                Layanan Servis yang Cepat, Tepat, dan Terpercaya
            </h2>
            <p class="text-gray-600 max-w-3xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Kiri: Daftar Layanan -->
            <div class="space-y-12">

                <!-- Servis & Perbaikan Perangkat -->
                <div class="flex gap-6">
                    <div class="w-16 h-16 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-9 h-9 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.5 12a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                            <path d="M12 9v3.75m0 0v3.75m0-3.75h3.75m-3.75 0H8.25"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-teal-700 mb-2">Servis & Perbaikan Perangkat</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.
                        </p>
                    </div>
                </div>

                <!-- Upgrade & Optimasi Kinerja -->
                <div class="flex gap-6">
                    <div class="w-16 h-16 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-9 h-9 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5-5H3v5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-teal-700 mb-2">Upgrade & Optimasi Kinerja</h3>
                        <p class="text-gray-600">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Kanan: Ilustrasi + CTA Bawah -->
            <div class="space-y-12">

                <!-- Ilustrasi Gelombang Abu -->
                <div class="relative">
                    <div class="bg-gray-300 rounded-2xl w-full h-80 md:h-96 shadow-xl"></div>
                    <svg class="absolute inset-0 w-full h-full text-white" viewBox="0 0 800 400" fill="currentColor">
                        <path d="M0,100 Q200,250 400,150 T800,100 L800,400 L0,400 Z"/>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Bagian Konsultasi + Tombol -->
        <div class="text-center mt-16 mb-8">
            <h3 class="text-2xl md:text-3xl font-bold text-teal-700 mb-4">
                Konsultasi Lewat WA
            </h3>
            <p class="text-gray-600 mx-auto mb-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel elit at ligula finibus facilisis. Aenean ultricies consectetur risus, ac blandit dolor tincidunt ut.
            </p>
            <a href="https://wa.me/6285161728383" target="_blank"
                class="inline-flex items-center gap-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-lg px-10 py-5 rounded-full shadow-lg transition transform hover:scale-105">
                CONTACT US
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
        </div>
    </div>
</section>


@endsection
