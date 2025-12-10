<footer class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

        <!-- About Company -->
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">About Company</h3>
            <p class="text-gray-600 leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Cras vel elit at ligula finibus facilisis.
            </p>
        </div>

        <!-- Menu (jarak lebih rapat) -->
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Menu</h3>
            <ul class="space-y-2 text-gray-700"> <!-- dari space-y-3 jadi space-y-2 -->
                <li><a href="{{ url('/') }}" class="hover:text-[#006666]">Home</a></li>
                <li><a href="{{ url('/about') }}" class="hover:text-[#006666]">About Us</a></li>
                <li><a href="{{ url('/product') }}" class="hover:text-[#006666]">Product</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:text-[#006666]">Contact</a></li>
            </ul>
        </div>

        <!-- Contact Info (phone & email horizontal, address full width) -->
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-8">Contact Info</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">📞</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Phone:</p>
                        <p class="text-gray-700 text-sm">+6285161728383</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">✉️</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Email:</p>
                        <p class="text-gray-700 text-sm">gecgroup@gmail.com</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center">📍</div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Alamat</p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Ruko Mangga Dua Plaza Blok I No 29<br>
                        Mangga Dua Jakarta Pusat 10730
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-gray-600 text-sm mt-12 border-t border-gray-300 pt-8 max-w-7xl mx-auto px-6">
        Copyright © 2025. gecgroups.co.id. All right reserved.
    </div>
</footer>
