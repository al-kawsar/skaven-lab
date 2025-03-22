<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if (auth()->user()->isAdmin())
                    <li class="menu-title fw-bold" style="font-size: 12px">
                        <span class="text-uppercase">MANAJEMEN DATA</span>
                    </li>

                    <li class="submenu {{ request()->routeIs('admin.lab.*') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-building"></i> <span>Laboratorium</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.lab.index') }}"
                                    class="{{ request()->routeIs('admin.lab.index') ? 'active' : '' }}">Data
                                    Laboratorium</a>
                            </li>
                            @if (auth()->user()->hasRole('superadmin'))
                                <li>
                                    <a href="{{ route('admin.lab.create') }}"
                                        class="{{ request()->routeIs('admin.lab.create') ? 'active' : '' }}">Tambah
                                        Laboratorium</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <li
                        class="submenu {{ request()->routeIs('admin.barang.*') || request()->routeIs('admin.kategori.*') || request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-box"></i> <span>Inventaris Barang</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.barang.index') }}"
                                    class="{{ request()->routeIs('admin.barang.index') ? 'active' : '' }}">Data
                                    Barang</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kategori.index') }}"
                                    class="{{ request()->routeIs('admin.kategori.index') ? 'active' : '' }}">Kategori
                                    Barang</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.lokasi.index') }}"
                                    class="{{ request()->routeIs('admin.lokasi.index') ? 'active' : '' }}">Lokasi
                                    Penyimpanan</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.lokasi.detail.index') }}"
                                    class="{{ request()->routeIs('admin.lokasi.detail.index') ? 'active' : '' }}">Detail
                                    Lokasi Penyimpanan</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="submenu {{ request()->routeIs('admin.peminjaman.*') || request()->routeIs('admin.borrow.*') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-calendar-check"></i> <span>Peminjaman</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.borrow.index') }}"
                                    class="{{ request()->routeIs('admin.borrow.index') ? 'active' : '' }}">Peminjaman
                                    Lab</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.peminjaman.barang') }}"
                                    class="{{ request()->routeIs('admin.peminjaman.barang') ? 'active' : '' }}">Peminjaman
                                    Barang</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.jadwal.lab') }}"
                                    class="{{ request()->routeIs('admin.jadwal.lab') ? 'active' : '' }}">Jadwal
                                    Penggunaan Lab</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.peminjaman.pengembalian') }}"
                                    class="{{ request()->routeIs('admin.peminjaman.pengembalian') ? 'active' : '' }}">Pengembalian
                                    Barang</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="submenu {{ request()->routeIs('admin.user.*') || request()->routeIs('admin.student.*') || request()->routeIs('admin.teacher.*') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-users"></i> <span>Pengguna</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.user.index') }}"
                                    class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">Akun
                                    Pengguna</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.student.index') }}"
                                    class="{{ request()->routeIs('admin.student.*') ? 'active' : '' }}">Data
                                    Siswa</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.teacher.index') }}"
                                    class="{{ request()->routeIs('admin.teacher.*') ? 'active' : '' }}">Data
                                    Guru</a>
                            </li>
                            @if (auth()->user()->hasRole('superadmin'))
                                <li>
                                    <a href="{{ route('admin.role.index') }}"
                                        class="{{ request()->routeIs('admin.role.index') ? 'active' : '' }}">Manajemen
                                        Role</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <li class="{{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.report.index') }}"><i class="fa fa-chart-bar"></i>
                            <span>Laporan</span></a>
                    </li>
                @endif

                <li class="menu-title fw-bold" style="font-size: 12px">
                    <span class="text-uppercase">PEMINJAMAN</span>
                </li>
                <li class="{{ request()->routeIs('lab.index') ? 'active' : '' }}">
                    <a href="{{ route('lab.index') }}"><i class="fa fa-flask"></i><span>Pinjam Lab</span></a>
                </li>
                <li class="{{ request()->routeIs('item.index') ? 'active' : '' }}">
                    <a href="{{ route('item.index') }}"><i class="fa fa-toolbox"></i><span>Pinjam Barang</span></a>
                </li>
                <li class="{{ request()->routeIs('borrow.view') ? 'active' : '' }}">
                    <a href="{{ route('borrow.view') }}"><i class="fa fa-list-alt"></i><span>Peminjaman Saya</span></a>
                </li>

                <li class="menu-title fw-bold" style="font-size: 12px">
                    <span class="text-uppercase">AKUN</span>
                </li>
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
