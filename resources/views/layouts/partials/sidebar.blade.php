<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="feather-grid"></i>
                        <span>
                            Dashboard</span></a>
                </li>
                @if (auth()->user()->isAdmin())
                    <li class="menu-title fw-bold" style="font-size: 12px">
                        <span class="text-uppercase">DATA MASTER</span>
                    </li>
                    <li
                        class="submenu {{ request()->routeIs('admin.lab.index') || request()->routeIs('admin.lab.create') || request()->routeIs('admin.lab.edit') ? 'active' : '' }}">
                        <a href="/"><i class="fa fa-building"></i> <span> Lab</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('admin.lab.index') }}"
                                    class="{{ request()->routeIs('admin.lab.index') ? 'active' : '' }}">Data Lab</a>
                            </li>
                            <li><a href="{{ route('admin.lab.create') }}"
                                    class="{{ request()->routeIs('admin.lab.create') ? 'active' : '' }}">Tambah Lab</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('admin.borrow.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.borrow.index') }}"><i class="fa fa-calendar-check"></i> <span> Data
                                Peminjaman</span></a>
                    </li>
                    <li
                        class="{{ request()->routeIs('admin.siswa.index') || request()->routeIs('admin.siswa.create') || request()->routeIs('admin.siswa.edit') || request()->routeIs('admin.guru.index') || request()->routeIs('admin.guru.create') || request()->routeIs('admin.guru.edit') ? 'active' : '' }}">
                        <a href="{{ route('admin.guru.index') }}"><i class="fa fa-users"></i> <span> Data
                                Pengguna</span></a>
                        {{-- <ul>
                        <li>
                            <a href="{{ route('admin.siswa.index') }}"
                                class="{{ request()->routeIs('admin.siswa.index') ? 'active' : '' }}">Siswa</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.guru.index') }}"
                                class="{{ request()->routeIs('admin.guru.index') ? 'active' : '' }}">Guru</a>
                        </li>
                    </ul> --}}
                    </li>
                    <li class="{{ request()->routeIs('admin.report.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.report.index') }}"><i class="fa fa-clipboard"></i> <span>
                                Laporan</span></a>
                    </li>
                @endif
                <li class="menu-title fw-bold" style="font-size: 12px">
                    <span class="text-uppercase">Peminjaman</span>
                </li>
                <li class="{{ request()->routeIs('lab.index') ? 'active' : '' }}">
                    <a href="{{ route('lab.index') }}"><i class="fa fa-calendar"></i><span>Pinjam Lab</span></a>
                </li>
                <li class="{{ request()->routeIs('borrow.view') ? 'active' : '' }}">
                    <a href="{{ route('borrow.view') }}"><i class="fa fa-address-card"></i><span>Peminjaman
                            Saya</span></a>
                </li>
                <li class="menu-title fw-bold" style="font-size: 12px">
                    <span class="text-uppercase">Lainnya</span>
                </li>
                <li class="">
                    <a href="{{ route('settings.general') }}"><i class="fa fa-cog"></i> <span> Pengaturan</span></a>
                    {{-- <ul>
                        <li>
                            <a href="/" class="">Role Management</a>
                        </li>
                    </ul> --}}
                </li>
            </ul>
        </div>
    </div>
</div>
