<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Div TSI - Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('dash/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('dash/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">
                                <div class="w-100 text-center" style="padding-left: 40px;">
                                    <img src="{{ asset('dash/img/logo-brks.png') }}" alt="Logo BRKS"
                                        class="img-fluid mx-auto d-block" style="max-height: 200px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Registrasi!</h1>
                                    </div>
                                    {{-- Form untuk registrasi --}}
                                    <form class="user" action="/register" method="POST">
                                        @csrf

                                        {{-- Blok error ini tidak perlu lagi karena ditangani SweetAlert --}}
                                        {{-- @if ($errors->any()) ... @endif --}}

                                        {{-- Field Nama --}}
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="InputName"
                                                placeholder="Masukkan Nama Lengkap Anda" name="name"
                                                value="{{ old('name') }}" required autofocus>
                                        </div>

                                        {{-- Field Email --}}
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Masukkan Alamat Email..." name="email"
                                                value="{{ old('email') }}" required>
                                        </div>

                                        {{-- Field Password --}}
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="password"
                                                required>
                                        </div>

                                        {{-- Field Konfirmasi Password --}}
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleRepeatPassword" placeholder="Ulangi Password"
                                                name="password_confirmation" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Registrasi Akun
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="/">Sudah punya akun? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('dash/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dash/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('dash/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('dash/js/sb-admin-2.min.js') }}"></script>
    @include('components.sweetalert')

</body>

</html>