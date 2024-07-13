 <div class="header">
     <div class="header-left">
         <a href="{{ route('dashboard') }}" class="logo">
             -
             {{-- <img src="/assets/img/logo.png" alt="Logo"> --}}
         </a>
         <a href="{{ route('dashboard') }}" class="logo logo-small">
             -
             {{-- <img src="/assets/img/logo.png" alt="Logo" width="30" height="30"> --}}
         </a>
     </div>
     <div class="menu-toggle">
         <a href="javascript:void(0);" id="toggle_btn">
             <i class="fas fa-bars"></i>
         </a>
     </div>

     <div class="top-nav-search">
         <form>
             <input type="text" class="form-control" placeholder="Search here">
             <button class="btn" type="submit"><i class="fas fa-search"></i></button>
         </form>
     </div>
     <a class="mobile_btn" id="mobile_btn">
         <i class="fas fa-bars"></i>
     </a>

     <ul class="nav user-menu">
         <li class="nav-item zoom-screen me-2">
             <a href="#" class="nav-link header-nav-list win-maximize">
                 <img src="/assets/img/icons/header-icon-04.svg" alt="">
             </a>
         </li>
         <li class="nav-item dropdown has-arrow new-user-menus">
             <a class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                 <span class="user-img">
                     <img class="rounded-circle"
                         src="{{ auth()->user()->file->path_name ?? 'https://www.gravatar.com/avatar/ddc96cd95c37f4aaf1e1d4d4f891d6cf?s=200&d=mp' }}"
                         width="31" alt="{{ auth()->user()->name }}">
                     <div class="user-text">
                         <h6>{{ App\Helpers\ConvertString::convert(auth()->user()->name) }}</h6>
                         <p class="text-muted mb-0 text-capitalize">{{ auth()->user()->role->name }}</p>
                     </div>
                 </span>
             </a>
             <div class="dropdown-menu">
                 <div class="user-header">
                     <div class="avatar avatar-sm">
                         <img src="{{ auth()->user()->file->path_name ?? 'https://www.gravatar.com/avatar/ddc96cd95c37f4aaf1e1d4d4f891d6cf?s=200&d=mp' }}"
                             alt="User Image" class="avatar-img rounded-circle">
                     </div>
                     <div class="user-text">
                         <h6>{{ auth()->user()->name }}</h6>
                         <p class="text-muted mb-0 text-capitalize">{{ auth()->user()->role->name }}</p>
                     </div>
                 </div>
                 <a class="dropdown-item" href="{{ route('admin.profile.index') }}">My Profile</a>
                 <a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a>
             </div>
         </li>

     </ul>

 </div>
