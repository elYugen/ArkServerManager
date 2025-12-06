<style>
    .sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        transition: transform 0.3s ease-in-out;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .sidebar-overlay.show {
        display: block;
    }

    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1001;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
    }

    /* Style moderne pour les liens de navigation */
    .nav-link {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #6c757d;
        font-weight: 500;
    }

    .nav-link:hover:not(.disabled) {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .nav-link.active {
        background-color: #e7f1ff;
        color: #0d6efd;
        position: relative;
    }

    .nav-link.active::after {
        content: '›';
        font-size: 1.5rem;
        font-weight: bold;
    }

    .nav-link i {
        margin-right: 0.5rem;
    }

    .nav-link.disabled {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #adb5bd;
        padding: 0.5rem 1rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: block;
        }
    }
</style>

<!-- Bouton toggle pour mobile -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-white" id="sidebar">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none" style="margin-bottom: 24px;">
        <img src="https://cdn2.steamgriddb.com/logo_thumb/49616ab5001dd01538f33c56818f9478.png" alt="Ark ASA Logo" width="92" height="58" class="me-2">
        <span class="fs-4"><b>Panel ASA</b></span>
    </a>
    <div style="height: 54px;"></div>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">MENU</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <span><i class="bi bi-terminal"></i> Accueil</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('player.index') }}" class="nav-link {{ request()->routeIs('player.*') ? 'active' : '' }}">
                <span><i class="bi bi-person"></i> Gestion des joueurs</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span><i class="bi bi-people"></i> Tribus</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span><i class="bi bi-coin"></i> Économie</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span><i class="bi bi-cart"></i> Boutique</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <span><i class="bi bi-sliders"></i> Permissions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('configuration.index') }}" class="nav-link {{ request()->routeIs('configuration.*') ? 'active' : '' }}">
                <span><i class="bi bi-gear"></i> Configuration</span>
            </a>
        </li>
    </ul>
    
    @if (Auth::check())
    <div class="d-flex align-items-center mt-auto justify-content-between">
        <div class="d-flex align-items-center">
            <img src="https://avatar.iran.liara.run/public" alt="" width="42" height="42" class="rounded-circle me-2">
            <strong>{{ Auth::user()->name }}</strong>
        </div>
        <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-danger p-0 ms-4" title="Déconnexion" style="text-decoration: none;">
                <i class="bi bi-box-arrow-right" style="font-size: 1.5rem;"></i>
            </button>
        </form>
    </div>
    @endif
</div>

<!-- JavaScript à ajouter avant la fermeture </body> -->
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
    });

    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });

    const navLinks = sidebar.querySelectorAll('.nav-link:not(.disabled)');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });
    });
</script>