{{-- resources/views/partials/navbar.blade.php --}}

<nav class="fixed top-0 w-full bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo + Nama --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-2">
                <img src="{{ asset('storage/media/logo-gec.png') }}" alt="Logo" class="h-10 w-auto">
            </a>

            {{-- Desktop Menu --}}
            <ul class="hidden lg:flex space-x-8 items-center">
                <li>
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active-link' : '' }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active-link' : '' }}">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="{{ url('/blog') }}" class="nav-link {{ request()->is('blog*') ? 'active-link' : '' }}">
                        Blog
                    </a>
                </li>
                <li>
                    <a href="{{ url('/product') }}" class="nav-link {{ request()->is('product*') ? 'active-link' : '' }}">
                        Product
                    </a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active-link' : '' }}">
                        Contact
                    </a>
                </li>
            </ul>

            {{-- Social Icons (Desktop) --}}
            <div class="hidden lg:flex space-x-4">
                <a href="#" class="text-gray-600 hover:text-blue-500 transition transform hover:-translate-y-1"><i class="bi bi-facebook text-xl"></i></a>
                <a href="#" class="text-gray-600 hover:text-pink-500 transition transform hover:-translate-y-1"><i class="bi bi-instagram text-xl"></i></a>
                <a href="#" class="text-gray-600 hover:text-red-600 transition transform hover:-translate-y-1"><i class="bi bi-youtube text-xl"></i></a>
            </div>

            {{-- Mobile Menu Toggle Button --}}
            <div class="lg:hidden">
                <button id="mobile-menu-button" class="text-gray-800 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition" aria-label="Toggle menu">
                    <svg id="menu-icon" class="w-6 h-6" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                        <g class="burger" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M4 6h16" />
                            <path d="M4 12h16" />
                            <path d="M4 18h16" />
                        </g>
                        <g class="close" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M6 6L18 18" />
                            <path d="M6 18L18 6" />
                        </g>
                    </svg>
                </button>
            </div>

        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="lg:hidden hidden overflow-hidden transition-all duration-300 ease-in-out max-h-0">
            <div class="px-4 pt-2 pb-4 space-y-2 bg-gray-50 border-t">
                <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md nav-link {{ request()->is('/') ? 'active-link bg-blue-50' : 'hover:bg-gray-100' }}">
                    Home
                </a>
                <a href="{{ url('/about') }}" class="block px-3 py-2 rounded-md nav-link {{ request()->is('about') ? 'active-link bg-blue-50' : 'hover:bg-gray-100' }}">
                    About Us
                </a>
                <a href="{{ url('/blog') }}" class="block px-3 py-2 rounded-md nav-link {{ request()->is('blog*') ? 'active-link bg-blue-50' : 'hover:bg-gray-100' }}">
                    Blog
                </a>
                <a href="{{ url('/product') }}" class="block px-3 py-2 rounded-md nav-link {{ request()->is('product*') ? 'active-link bg-blue-50' : 'hover:bg-gray-100' }}">
                    Product
                </a>
                <a href="{{ url('/contact') }}" class="block px-3 py-2 rounded-md nav-link {{ request()->is('contact') ? 'active-link bg-blue-50' : 'hover:bg-gray-100' }}">
                    Contact
                </a>

                {{-- Social Icons (Mobile) --}}
                <div class="flex space-x-4 pt-4 px-3 border-t">
                    <a href="#" class="text-gray-600 hover:text-blue-500 transition transform hover:-translate-y-1"><i class="bi bi-facebook text-xl"></i></a>
                    <a href="#" class="text-gray-600 hover:text-pink-500 transition transform hover:-translate-y-1"><i class="bi bi-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-600 hover:text-red-600 transition transform hover:-translate-y-1"><i class="bi bi-youtube text-xl"></i></a>
                </div>
            </div>
        </div>

    </div>

</nav>

{{-- Navbar Styles --}}
<style>
    /* Navbar link styles */
    .nav-link {
        color: #374151;
        font-weight: 600;
        text-transform: uppercase;
        position: relative;
        transition: color .18s ease, transform .18s ease;
        padding-bottom: 4px;
        letter-spacing: 0.02em;
        display: inline-block;
    }

    .nav-link::after {
        content: '';
        display: block;
        height: 3px;
        background: #006666;
        width: 0;
        transition: width .28s ease;
        position: absolute;
        bottom: -6px;
        left: 0;
        border-radius: 2px;
    }

    .nav-link:hover {
        color: #006666;
        transform: translateY(-2px);
    }

    .nav-link:hover::after,
    .active-link::after {
        width: 100%;
    }

    .active-link {
        color: #006666;
    }

    /* Mobile menu animation */
    #mobile-menu.show {
        max-height: 500px;
    }

    /* Burger/Close icon animation */
    #menu-icon .close {
        opacity: 0;
        transform: scale(0.8) rotate(0deg);
        transition: opacity .18s ease, transform .18s ease;
    }
    #menu-icon .burger {
        opacity: 1;
        transform: scale(1) rotate(0deg);
        transition: opacity .18s ease, transform .18s ease;
    }
    #mobile-menu-button.active #menu-icon .close {
        opacity: 1;
        transform: scale(1) rotate(0deg);
    }
    #mobile-menu-button.active #menu-icon .burger {
        opacity: 0;
        transform: scale(0.8) rotate(20deg);
    }
</style>

{{-- Mobile Menu Toggle Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHidden = menu.classList.toggle('hidden');
            btn.classList.toggle('active');

            // Smooth height transition
            if (isHidden) {
                menu.style.maxHeight = '0';
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
            }
        });

        // Close menu when a link is clicked
        const menuLinks = menu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                menu.classList.add('hidden');
                btn.classList.remove('active');
                menu.style.maxHeight = '0';
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!btn.contains(event.target) && !menu.contains(event.target)) {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    btn.classList.remove('active');
                    menu.style.maxHeight = '0';
                    menuIcon.style.transform = 'rotate(0deg)';
                }
            }
        });
    });
</script>

