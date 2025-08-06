<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    {{-- Logo (Tampil untuk semua user) --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <img src="{{ asset('dash/img/logo-brks.png') }}" alt="Logo BRKS" class="img-fluid" style="max-height: 50px;">
    </a>

    <hr class="sidebar-divider my-0">

    {{-- AWAL: MENU UNTUK SEMUA PENGGUNA (ADMIN & USER BIASA) --}}
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Data Dokumen
    </div>

    <li class="nav-item {{ request()->is('data_proyek*') ? 'active' : '' }}">
        <a class="nav-link" href="/data_proyek">
            <i class="fas fa-fw fa-table"></i>
            <span>List Dokumen</span></a>
    </li>
    {{-- AKHIR: MENU UNTUK SEMUA PENGGUNA --}}


    {{-- AWAL: BLOK MENU KHUSUS ADMIN --}}
    @if(Auth::check() && Auth::user()->role_id == 1)
        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Manajemen User
        </div>

        <li class="nav-item {{ request()->is('status*') || request()->is('daftar-akun*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('status.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Manajemen Akun</span></a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Master Data
        </div>

        <li class="nav-item {{ request()->is('admin/master/create*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.master.create') }}">
                <i class="fas fa-fw fa-plus-circle"></i>
                <span>Tambah Data</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('admin/master/manage*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.master.manage') }}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Kelola Data</span>
            </a>
        </li>

        {{-- BISA DITAMBAHKAN DI BAWAH MANAJEMEN AKUN --}}
        <li class="nav-item {{ request()->is('log-aktivitas*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('log.aktivitas') }}">
                <i class="fas fa-fw fa-history"></i>
                <span>Log Aktivitas</span></a>
        </li>
    @endif
    {{-- AKHIR: BLOK MENU KHUSUS ADMIN --}}


    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>