<div class="header">
    <div class="header-left">
        <a href="{{ route('dashboard') }}" class="logo">
            <img src="/assets/img/favicon.png" alt="Logo">
            <span class="ml-2">SKAVEN LAB RPL</span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-small">
            <img src="/assets/img/favicon.png" alt="Logo">
        </a>
    </div>
    <div class="menu-toggle">
        <a href="javascript:void(0);" id="toggle_btn">
            <i class="fas fa-arrow-alt-circle-right" id="sidebar_toggle_icon"></i>
        </a>
    </div>
    {{-- <div class="top-nav-search">
        <form>
            <input type="text" class="form-control" placeholder="Cari...">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div> --}}
    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>
    <ul class="nav user-menu">
        <li class="nav-item zoom-screen me-2">
            <a href="#" class="nav-link header-nav-list win-maximize">
                <img src="/assets/img/icons/header-icon-04.svg" alt="Full Screen">
            </a>
        </li>
        <li class="nav-item dropdown has-arrow new-user-menus">
            <a class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle"
                    src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : '/assets/img/profile.png' }}"
                    width="31" alt="{{ auth()->user()->name }}">
                    <div class="user-text">
                        <h6>{{ App\Helpers\ConvertStringHelper::convert(auth()->user()->name) }}</h6>
                        <p class="text-muted mb-0 text-capitalize">{{ auth()->user()->role->name }}</p>
                    </div>
                </span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : '/assets/img/profile.png' }}"
                        alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ auth()->user()->name }}</h6>
                        <p class="text-muted mb-0 text-capitalize">{{ auth()->user()->role->name }}</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a>
                {{-- <a class="dropdown-item" href="{{ route('settings.general') }}">Pengaturan</a> --}}
                <a class="dropdown-item" href="{{ route('auth.logout') }}">Keluar</a>
            </div>
        </li>
        <!-- Notifications Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if (auth()->user()->unreadNotifications->count() > 0)
                <span
                class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{ auth()->user()->unreadNotifications->count() }} Notifikasi Baru</span>
                <div class="dropdown-divider"></div>

                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                <a href="{{ $notification->data['url'] }}?read={{ $notification->id }}" class="dropdown-item">
                    <i class="fas fa-{{ $notification->data['icon'] }} mr-2"></i>
                    {{ $notification->data['title'] }}
                    <span
                    class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    <p class="text-sm mb-0">{{ $notification->data['message'] }}</p>
                </a>
                <div class="dropdown-divider"></div>
                @empty
                <span class="dropdown-item dropdown-footer">Tidak ada notifikasi baru</span>
                @endforelse

                <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">Lihat Semua
                Notifikasi</a>
            </div>
        </li>
    </ul>
</div>
