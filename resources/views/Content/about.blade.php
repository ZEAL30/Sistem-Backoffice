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

<!-- Keunggulan -->
<section class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

    <!-- Left Image -->
    <div class="w-full">
        <img src="/your-image.jpg"
             class="w-full h-[380px] object-cover rounded-lg bg-gray-300" />
    </div>

    <!-- Right Content -->
    <div class="space-y-6">

        <h2 class="text-4xl font-bold text-[#0D6B63]">Gec Groups</h2>

        <p class="text-gray-600 leading-relaxed">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Cras vel elit at ligula finibus facilisis.
        </p>

        <!-- Point 1 -->
        <div class="flex items-start gap-3">
            <span class="bg-[#2BB88A] text-white p-2 rounded-full text-xl">✔</span>
            <div>
                <h3 class="text-2xl font-semibold text-[#0D6B63]">Kualitas Terjamin</h3>
                <p class="text-gray-600">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis.
                </p>
            </div>
        </div>

        <!-- Point 2 -->
        <div class="flex items-start gap-3">
            <span class="bg-[#2BB88A] text-white p-2 rounded-full text-xl">✔</span>
            <div>
                <h3 class="text-2xl font-semibold text-[#0D6B63]">
                    Pengiriman Aman & Tepat Waktu
                </h3>
                <p class="text-gray-600">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis.
                </p>
            </div>
        </div>

        <!-- Button -->
        <button class="bg-[#005F5B] text-white px-8 py-3 rounded-full font-semibold
                       flex items-center gap-2 hover:bg-[#004945] transition">
            CONTACT US
            <span class="text-xl">+</span>
        </button>

    </div>
</section>


<!-- Layanan Kami -->
<section class="py-20 bg-[#F4F6F8]">
    <div class="max-w-7xl mx-auto px-6 text-center">

        <!-- Title -->
        <h2 class="text-4xl font-bold text-[#0D6B63]">Layanan Kami</h2>
        <p class="text-gray-600 mt-2">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Cras vel elit at ligula finibus facilisis.
        </p>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-14">

            <!-- Card 1 -->
            <div class="bg-white p-10 rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
                <h3 class="text-3xl font-bold text-[#0D6B63] mb-8 leading-tight">
                    Konsultasi <br> Produk
                </h3>

                <img src="/icons/handshake.png" class="w-40 h-40 mb-8" />

                <p class="text-gray-600 leading-relaxed mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis. Aenean ultricies
                    consectetur risus, ac blandit dolor tincidunt ut.
                </p>

                <span class="text-2xl">→</span>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-10 rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
                <h3 class="text-3xl font-bold text-[#0D6B63] mb-8 leading-tight">
                    Pemesanan <br> Online
                </h3>

                <img src="/icons/cart.png" class="w-40 h-40 mb-8" />

                <p class="text-gray-600 leading-relaxed mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis. Aenean ultricies
                    consectetur risus, ac blandit dolor tincidunt ut.
                </p>

                <span class="text-2xl">→</span>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-10 rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
                <h3 class="text-3xl font-bold text-[#0D6B63] mb-8 leading-tight">
                    Garansi & <br> Klaim
                </h3>

                <img src="/icons/shield.png" class="w-40 h-40 mb-8" />

                <p class="text-gray-600 leading-relaxed mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis. Aenean ultricies
                    consectetur risus, ac blandit dolor tincidunt ut.
                </p>

                <span class="text-2xl">→</span>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-10 rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
                <h3 class="text-3xl font-bold text-[#0D6B63] mb-8 leading-tight">
                    After-Sales <br> Support
                </h3>

                <img src="/icons/tools.png" class="w-40 h-40 mb-8" />

                <p class="text-gray-600 leading-relaxed mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Cras vel elit at ligula finibus facilisis. Aenean ultricies
                    consectetur risus, ac blandit dolor tincidunt ut.
                </p>

                <span class="text-2xl">→</span>
            </div>

        </div>
    </div>
</section>


<!-- Features Section -->
<section class="w-full bg-gradient-to-r from-teal-600 to-teal-500 py-20 md:py-8">
    <div class="max-w-4xl mx-auto text-center px-6">

        <span class="inline-block bg-teal-800 text-white px-6 py-2 rounded-full mb-4 text-sm md:text-base">
            Siap Membantu Anda Lebih Jauh
        </span>

        <h2 class="text-white text-3xl md:text-5xl font-bold mb-4">
            Punya Pertanyaan?
        </h2>

        <p class="text-white/80 mb-8 text-sm md:text-lg">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>

        <a href="#"
           class="bg-teal-800 hover:bg-teal-900 text-white font-semibold px-6 py-3 rounded-full inline-flex items-center gap-2">
            KONSULTASI SEKARANG
            <span>+</span>
        </a>

    </div>
</section>


@endsection
