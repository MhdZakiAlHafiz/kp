<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form action="{{ request()->url() }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            {{-- Menampilkan kembali kata kunci yang dicari --}}
            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." name="search"
                aria-label="Search" aria-describedby="basic-addon2" value="{{ request('search') }}">

            <div class="input-group-append">
                @if(request('search'))
                    {{-- Tombol clear (×) --}}
                    <a href="{{ request()->url() }}" class="btn px-2 py-0 d-flex align-items-center"
                        style="border: none; background: transparent; box-shadow: none;">
                        <span class="text-muted fs-4" style="line-height: 1;">&times;</span>
                    </a>
                @endif

                {{-- Tombol Cari --}}
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        @auth
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        {{-- Menggunakan Auth::user() untuk keamanan --}}
                        {{ Auth::user()->name ?? 'Pengguna' }}
                    </span>
                    <img class="img-profile rounded-circle" src="{{ asset('dash/img/undraw_profile.svg') }}">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                    {{-- PERBAIKAN: Menggunakan helper route() untuk tautan yang benar --}}
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profil
                    </a>

                    {{-- PERBAIKAN: Menggunakan helper route() untuk tautan yang benar --}}
                    <a class="dropdown-item" href="{{ route('change-password') }}">
                        <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                        Ubah Password
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        @endauth

    </ul>

</nav>