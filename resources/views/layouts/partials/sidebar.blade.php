<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ request()->routeIs('dashboard') ? 'active fw-semibold' : 'fw-medium' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if (auth()->user()->isAdmin())
                <li class="menu-title fw-bold" style="font-size: 12px">
                    <span class="text-uppercase">MANAJEMEN DATA</span>
                </li>

                <li
                class="submenu {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.kategori.*') || request()->routeIs('admin.lokasi.*') ? 'active fw-semibold' : 'fw-medium' }}">
                <a href="#"><i class="fa fa-box"></i> <span>Inventaris Barang</span> <span
                    class="menu-arrow"></span></a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.barang.index') }}"
                            class="{{ request()->routeIs('admin.barang.index') ? 'active fw-semibold' : 'fw-normal' }}">Data
                        Barang</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kategori.index') }}"
                        class="{{ request()->routeIs('admin.kategori.index') ? 'active fw-semibold' : 'fw-normal' }}">Kategori
                    Barang</a>
                </li>
                <li>
                    <a href="{{ route('admin.lokasi.index') }}"
                    class="{{ request()->routeIs('admin.lokasi.index') ? 'active fw-semibold' : 'fw-normal' }}">Lokasi
                Penyimpanan</a>
            </li>
        </ul>
    </li>

    <li class="submenu {{ request()->routeIs('borrowing.lab.admin.*') ? 'active' : '' }}">
        <a href="#"><i class="fa fa-clipboard-list"></i> <span>Peminjaman</span> <span
            class="menu-arrow"></span></a>
            <ul>
                <li>
                    <a href="{{ route('borrowing.lab.admin.index') }}"
                    class="{{ request()->routeIs('borrowing.lab.admin.index') ? 'active' : '' }}">Peminjaman
                Ruangan</a>
            </li>
            <li>
                <a href="{{ route('admin.peminjaman.barang') }}"
                class="{{ request()->routeIs('admin.peminjaman.barang') ? 'active' : '' }}">Peminjaman
            Barang</a>
        </li>
                  {{--           <li>
                                <a href="{{ route('admin.jadwal.lab') }}"
                                    class="{{ request()->routeIs('admin.jadwal.lab') ? 'active' : '' }}">Jadwal
                                    Penggunaan Ruangan</a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('admin.peminjaman.pengembalian') }}"
                                    class="{{ request()->routeIs('admin.peminjaman.pengembalian') ? 'active' : '' }}">Pengembalian
                                Barang</a>
                            </li>
                        </ul>
                    </li>

                    <li
                    class="submenu {{ request()->routeIs('user.*') || request()->routeIs('student.*') || request()->routeIs('teacher.*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-users"></i> <span>Pengguna</span> <span
                        class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('user.index') }}"
                                class="{{ request()->routeIs('user.*') ? 'active' : '' }}">Akun
                            Pengguna</a>
                        </li>
                        <li>
                            <a href="{{ route('student.index') }}"
                            class="{{ request()->routeIs('student.*') ? 'active' : '' }}">Data
                        Siswa</a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.index') }}"
                        class="{{ request()->routeIs('teacher.*') ? 'active' : '' }}">Data
                    Guru</a>
                </li>
            </ul>
        </li>

        <li class="{{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
            <a href="{{ route('admin.report.index') }}"><i class="fa fa-chart-line"></i>
                <span>Laporan</span></a>
            </li>
            @endif

            <li class="menu-title fw-bold" style="font-size: 12px">
                <span class="text-uppercase">PEMINJAMAN</span>
            </li>
            <li class="{{ request()->routeIs('borrowing.lab.create') ? 'active fw-semibold' : 'fw-medium' }}">
                <a href="{{ route('borrowing.lab.create') }}"><i class="fa fa-calendar-plus"></i><span>Ajukan
                Peminjaman</span></a>
            </li>
            <li class="{{ request()->routeIs('item.index') ? 'active' : '' }}">
                <a href="{{ route('item.index') }}"><i class="fa fa-dolly"></i><span>Pinjam Barang</span></a>
            </li>
            <li
            class="{{ request()->routeIs('borrowing.*') && !request()->routeIs('borrowing.lab.admin.*') ? 'active' : '' }}">
            <a href="{{ route('borrowing.lab.index') }}"><i class="fa fa-list-alt"></i><span>Peminjaman
            Saya</span></a>
            <li class="{{ request()->routeIs('item.index') ? 'active' : '' }}">
                <a href="{{ route('item.index') }}"><i class="fa fa-dolly"></i><span>Pengembalian barang</span></a>
            </li>
        </li>

        <li class="menu-title fw-bold" style="font-size: 12px">
            <span class="text-uppercase">AKUN</span>
        </li>
        @if (auth()->user()->isAdmin())
        <li class="submenu {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-cog"></i> <span>Pengaturan</span> <span
                class="menu-arrow"></span></a>
                <ul>
                    <li>
                        <a href="{{ route('settings.general') }}"
                        class="{{ request()->routeIs('settings.general') ? 'active' : '' }}">Umum</a>
                    </li>
                    <li>
                        <a href="{{ route('settings.security') }}"
                        class="{{ request()->routeIs('settings.security') ? 'active' : '' }}">Keamanan</a>
                    </li>
                    <li>
                        <a href="{{ route('settings.activity.logs') }}"
                        class="{{ request()->routeIs('settings.activity.logs') ? 'active' : '' }}">Log
                    Aktivitas</a>
                </li>
            </ul>
        </li>
        @endif
        <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}"><i class="fa fa-user"></i> <span>Profil Saya</span></a>
        </li>
        <li>
            <a href="{{ route('auth.logout') }}"><i class="fa fa-sign-out-alt"></i> <span>Keluar</span></a>
        </li>
    </ul>
</div>
</div>
</div>
