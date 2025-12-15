<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Gec-Groups</h2>
    </div>

    @php
        $user = Auth::user();
        $userRole = $user?->role?->name ?? 'Customer';
    @endphp

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- Dashboard (Admin & Editor only) -->
            @if($userRole === 'Administrator' || $userRole === 'Editor')
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @endif

            <!-- Post (Admin & Editor) -->
            @if($userRole === 'Administrator' || $userRole === 'Editor')
            <li class="nav-item">
                <a href="#" class="nav-link nav-toggle {{ request()->routeIs('post.*') ? 'active' : '' }}" onclick="toggleSubmenu(event, 'post-menu')">
                    <i class="icon-post"></i>
                    <span>Post</span>
                    <i class="icon-chevron" style="margin-left: auto;"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('post.*') ? 'show' : '' }}" id="post-menu">
                    <li><a href="{{ route('post.index') }}" class="submenu-link">📄 All Post</a></li>
                    <li><a href="{{ route('post.create') }}" class="submenu-link">➕ Add Post</a></li>
                    <li><a href="{{ route('categories.index', ['type' => 'post']) }}" class="submenu-link">🏷️ Add Category Post</a></li>
                </ul>
            </li>
            @endif

            <!-- Produk (Admin & Editor) -->
            @if($userRole === 'Administrator' || $userRole === 'Editor')
            <li class="nav-item">
                <a href="#" class="nav-link nav-toggle {{ request()->routeIs('product.*') ? 'active' : '' }}" onclick="toggleSubmenu(event, 'product-menu')">
                    <i class="icon-product"></i>
                    <span>Produk</span>
                    <i class="icon-chevron" style="margin-left: auto;"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('product.*') ? 'show' : '' }}" id="product-menu">
                    <li><a href="{{ route('product.index') }}" class="submenu-link">📦 All Product</a></li>
                    <li><a href="{{ route('product.create') }}" class="submenu-link">➕ Add Product</a></li>
                    <li><a href="{{ route('categories.index', ['type' => 'product']) }}" class="submenu-link">🏷️ Add Category Product</a></li>
                </ul>
            </li>
            @endif

            <!-- Page Builder (Admin & Editor) -->
            @if($userRole === 'Administrator' || $userRole === 'Editor')
            <li class="nav-item">
                <a href="#" class="nav-link nav-toggle {{ request()->routeIs('page-builder.*') ? 'active' : '' }}" onclick="toggleSubmenu(event, 'page-builder-menu')">
                    <i class="icon-page-builder"></i>
                    <span>Page Builder</span>
                    <i class="icon-chevron" style="margin-left: auto;"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('page-builder.*') ? 'show' : '' }}" id="page-builder-menu">
                    <li><a href="{{ route('page-builder.index') }}" class="submenu-link">🎨 Dashboard</a></li>
                    <li><a href="{{ route('page-builder.hero.index') }}" class="submenu-link">🚀 Hero Section</a></li>
                    <li><a href="{{ route('page-builder.testimonial.index') }}" class="submenu-link">💬 Testimonial</a></li>
                    <li><a href="{{ route('page-builder.navigation.index') }}" class="submenu-link">🧭 Navigation</a></li>
                    <li><a href="{{ route('footer.edit') }}" class="submenu-link">🦶 Footer</a></li>
                </ul>
            </li>
            @endif


            <!-- Image (Admin Only) -->
            @if($userRole === 'Administrator')
            <li class="nav-item">
                <a href="#" class="nav-link nav-toggle" onclick="toggleSubmenu(event, 'image-menu')">
                    <i class="icon-image"></i>
                    <span>Gallery</span>
                    <i class="icon-chevron" style="margin-left: auto;"></i>
                </a>
                <ul class="submenu" id="image-menu">
                    <li><a href="{{ route('media.index') }}" class="submenu-link">📁 Library</a></li>
                    <li><a href="{{ route('media.index') }}" class="submenu-link">⬆️ Add Media File</a></li>
                </ul>
            </li>
            @endif


            <!-- Comments (Admin Only) -->
            @if($userRole === 'Administrator')
            <li class="nav-item">
                <a href="{{ route('comments.index') }}" class="nav-link {{ request()->routeIs('comments.*') ? 'active' : '' }}">
                    <i class="icon-comment"></i>
                    <span>Komentar</span>
                </a>
            </li>
            @endif

            <!-- User (Admin Only) -->
            @if($userRole === 'Administrator')
            <li class="nav-item">
                <a href="#" class="nav-link nav-toggle {{ request()->routeIs('auth.*') ? 'active' : '' }}" onclick="toggleSubmenu(event, 'user-menu')">
                    <i class="icon-users"></i>
                    <span>User</span>
                    <i class="icon-chevron" style="margin-left: auto;"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('auth.*') ? 'show' : '' }}" id="user-menu">
                    <li><a href="{{ route('auth.show') }}" class="submenu-link">👥 All User</a></li>
                    <li><a href="{{ route('auth.create') }}" class="submenu-link">➕ Add User</a></li>
                    <li><a href="{{ route('profile') }}" class="submenu-link">👤 Profil</a></li>
                </ul>
            </li>
            @else
            <!-- Profil (Editor & Customer) -->
            <li class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="icon-profile"></i>
                    <span>Profil</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <p class="user-name">{{ $user?->name ?? 'User' }}</p>
            <p class="user-role">{{ $user?->role?->name ?? 'User' }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="icon-logout"></i>
                <span>Logout</span>
            </button>
        </form>
        <div class="sidebar-website" style="margin-top:12px; text-align:center;">
            <a href="{{ url('/') }}" class="btn-website" style="display:inline-block; padding:8px 12px; border-radius:8px; font-weight:600; text-decoration:none;">
                <i class="icon-globe" style="margin-right:6px;"></i> Visit Website
            </a>
        </div>
    </div>
</aside>

<style>
    .sidebar {
        width: 260px;
        background: #252C45; /* primary */
        color: #eef2f7;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        box-shadow: 2px 8px 28px rgba(37,44,69,0.08);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 22px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        background: linear-gradient(180deg, rgba(0,0,0,0.05), rgba(255,255,255,0.01));
    }

    .sidebar-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.2px;
    }

    .sidebar-nav {
        flex: 1;
        padding: 20px 0;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin: 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        cursor: pointer;
    }

    .nav-link:hover {
        background-color: rgba(255,255,255,0.03);
        color: #fff;
        border-left-color: rgba(255,255,255,0.06);
    }

    .nav-link.active {
        background-color: rgba(255,255,255,0.06);
        color: #fff;
        border-left-color: #4FD1C5; /* subtle accent */
        font-weight: 600;
    }

    .nav-link i {
        margin-right: 12px;
        font-size: 18px;
    }

    .nav-link span {
        font-size: 14px;
    }

    .submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: none;
        background-color: transparent;
    }

    .submenu.show {
        display: block;
    }

    .submenu li {
        margin: 0;
    }

    .submenu-link {
        display: block;
        padding: 10px 20px 10px 46px;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: all 0.18s ease;
        font-size: 13px;
        border-left: 3px solid transparent;
    }

    .submenu-link:hover {
        background-color: rgba(255,255,255,0.03);
        color: #fff;
        border-left-color: #4FD1C5;
        padding-left: 50px;
    }

    .sidebar-footer {
        padding: 18px;
        border-top: 1px solid rgba(255,255,255,0.03);
        margin-top: auto;
        background: transparent;
    }

    .user-info {
        margin-bottom: 15px;
    }

    .user-name {
        margin: 0 0 5px 0;
        font-weight: 600;
        font-size: 13px;
        color: #fff;
    }

    .user-role {
        margin: 0;
        font-size: 12px;
        opacity: 0.9;
        text-transform: capitalize;
        color: rgba(255,255,255,0.7);
    }

    .logout-form {
        margin: 0;
    }

    .btn-logout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background-color: #4FD1C5; /* accent */
        color: #052027;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.18s ease;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-logout:hover {
        background-color: #3ac6b1;
    }

    .btn-logout i {
        margin-right: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .nav-link {
            padding: 10px 15px;
        }

        .nav-link i {
            font-size: 16px;
        }

        .nav-link span {
            font-size: 13px;
        }

        .submenu-link {
            padding: 8px 15px 8px 40px;
        }

        .submenu-link:hover {
            padding-left: 45px;
        }
    }
</style>

<script>
    function toggleSubmenu(event, menuId) {
        event.preventDefault();
        const submenu = document.getElementById(menuId);
        submenu.classList.toggle('show');
    }
</script>
