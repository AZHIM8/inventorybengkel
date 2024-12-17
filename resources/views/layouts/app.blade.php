<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory Gudang</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->

    <!-- Template CSS -->

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>


    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Datatable Jquery -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.4.1/css/dataTables.dateTime.min.css">

    <!-- Font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">


                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/ubah-password" class="dropdown-item has-icon">
                                <i class="fa fa-sharp fa-solid fa-lock"></i> Ubah Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                Swal.fire({
                                    title: 'Konfirmasi Keluar',
                                    text: 'Apakah Anda yakin ingin keluar?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, Keluar!'
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                      document.getElementById('logout-form').submit();
                                    }
                                  });">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Keluar') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">

                    <div class="sidebar-brand">
                        <a href="/">INVENTORY GUDANG</a>
                    </div>

                    <ul class="sidebar-menu">
                        @if (auth()->user()->role->role === 'manajer')
                            <li class="sidebar-item">
                                <a class="nav-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}"
                                    href="/">
                                    <i class="fa fa-area-chart" aria-hidden="true"></i> <span class="align-middle">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-header">LAPORAN</li>
                            <li><a class="nav-link {{ Request::is('laporan-stok') ? 'active' : '' }}"
                                    href="laporan-stok"><i
                                        class="fa fa-sharp fa-reguler fa-file"></i><span>Stok</span></a></li>
                            <li><a class="nav-link {{ Request::is('laporan-barang-masuk') ? 'active' : '' }}"
                                    href="laporan-barang-masuk"><i
                                        class="fa fa-sign-in" aria-hidden="true"></i><span>Barang
                                        Masuk</span></a></li>
                            <li><a class="nav-link {{ Request::is('laporan-barang-keluar') ? 'active' : '' }}"
                                    href="laporan-barang-keluar"><i
                                        class="fa fa-sign-out" aria-hidden="true"></i><span>Barang
                                        Keluar</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('laporan-kategori') ? 'active' : '' }}"
                                    href="laporan-kategori"><i
                                        class="fa fa-sharp fa-reguler fa-file-invoice"></i><span>Kategori /
                                        Jenis</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('laporan-kotak-saran') ? 'active' : '' }}"
                                    href="laporan-kotak-saran"><i
                                        class="fa fa-sharp fa-reguler fa-paste"></i><span>Kotak
                                        Saran</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('laporan-order-barang') ? 'active' : '' }}"
                                    href="laporan-order-barang"><i
                                        class="fa fa-cube" aria-hidden="true""></i><span>Order
                                        Barang</span></a>
                            </li>

                            {{-- <li class="menu-header">MANAJEMEN USER</li>
                            <li><a class="nav-link {{ Request::is('aktivitas-user') ? 'active' : '' }}"
                                    href="aktivitas-user"><i class="fa fa-solid fa-list"></i><span>Aktivitas
                                        User</span></a></li> --}}
                        @endif

                        @if (auth()->user()->role->role === 'superadmin')
                            <li class="sidebar-item">
                                <a class="nav-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}"
                                    href="/">
                                    <i class="fa fa-area-chart" aria-hidden="true"></i> <span class="align-middle">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-header">DATA MASTER</li>
                            <li class="dropdown">
                                <a href="#"
                                    class="nav-link has-dropdown {{ Request::is('barang') || Request::is('jenis-barang') || Request::is('satuan-barang') ? 'active' : '' }}"
                                    data-toggle="dropdown"><i class="fa fa-cube" aria-hidden="true"></i><span>Data
                                        Barang</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link {{ Request::is('barang') ? 'active' : '' }}"
                                            href="/barang"><i class="fa fa-solid fa-circle fa-xs"></i> Nama Barang</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('jenis-barang') ? 'active' : '' }}"
                                            href="/jenis-barang"><i class="fa fa-solid fa-circle fa-xs"></i> Jenis</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('satuan-barang') ? 'active' : '' }}"
                                            href="/satuan-barang"><i class="fa fa-solid fa-circle fa-xs"></i>
                                            Satuan</a></li>
                                </ul>

                            </li>
                            <li class="dropdown">
                                <a href="#"
                                    class="nav-link has-dropdown {{ Request::is('supplier') || Request::is('customer') ? 'active' : '' }}"
                                    data-toggle="dropdown"><i
                                        class="fa fa-sharp fa-solid fa-building"></i><span>Perusahaan</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link {{ Request::is('supplier') ? 'active' : '' }}"
                                            href="/supplier"><i class="fa fa-solid fa-circle fa-xs"></i> Supplier</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('customer') ? 'active' : '' }}"
                                            href="/customer"><i class="fa fa-solid fa-circle fa-xs"></i> Customer</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="menu-header">TRANSAKSI</li>
                            <li><a class="nav-link {{ Request::is('barang-masuk') ? 'active' : '' }}"
                                    href="barang-masuk"><i class="fa fa-solid fa-arrow-right"></i><span>Barang
                                        Masuk</span></a></li>
                            <li><a class="nav-link {{ Request::is('barang-keluar') ? 'active' : '' }}"
                                    href="barang-keluar"><i class="fa fa-sharp fa-solid fa-arrow-left"></i>
                                    <span>Barang Keluar</span></a></li>
                            {{-- <li><a class="nav-link {{ Request::is('kotak-saran') ? 'active' : '' }}"
                                    href="kotak-saran"><i class="fa fa-solid fa-box"></i><span>Kotak Saran</span></a>
                            </li> --}}
                            <li><a class="nav-link {{ Request::is('order-barang') ? 'active' : '' }}"
                                    href="order-barang"><i class="fa fa-solid fa-store"></i><span>Order
                                        Barang</span></a>
                            </li>

                            <li class="menu-header">LAPORAN</li>
                            <li><a class="nav-link {{ Request::is('laporan-stok') ? 'active' : '' }}"
                                    href="laporan-stok"><i
                                        class="fa fa-sharp fa-reguler fa-file"></i><span>Stok</span></a></li>
                            <li><a class="nav-link {{ Request::is('laporan-barang-masuk') ? 'active' : '' }}"
                                    href="laporan-barang-masuk"><i
                                        class="fa fa-sign-in" aria-hidden="true""></i><span>Barang Masuk</span></a></li>
                            <li><a class="nav-link {{ Request::is('laporan-barang-keluar') ? 'active' : '' }}"
                                    href="laporan-barang-keluar"><i
                                        class="fa fa-sign-out" aria-hidden="true"></i><span>Barang
                                        Keluar</span></a></li>
                            {{-- <li><a class="nav-link {{ Request::is('laporan-kategori') ? 'active' : '' }}"
                                    href="laporan-kategori"><i
                                        class="fa fa-sharp fa-reguler fa-file-invoice"></i><span>Kategori /
                                        Jenis</span></a>
                            </li> --}}
                            {{-- <li><a class="nav-link {{ Request::is('laporan-kotak-saran') ? 'active' : '' }}"
                                    href="laporan-kotak-saran"><i
                                        class="fa fa-sharp fa-reguler fa-paste"></i><span>Kotak
                                        Saran</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('laporan-order-barang') ? 'active' : '' }}"
                                    href="laporan-order-barang"><i
                                        class="fas fa-sharp fa-reguler fa-file-invoice-dollar"></i><span>Order
                                        Barang</span></a>
                            </li> --}}


                            <li class="menu-header">MANAJEMEN USER</li>
                            <li><a class="nav-link {{ Request::is('data-pengguna') ? 'active' : '' }}"
                                    href="data-pengguna"><i class="fa fa-solid fa-users"></i><span>Data
                                        Pengguna</span></a></li>
                            <li><a class="nav-link {{ Request::is('hak-akses') ? 'active' : '' }}"
                                    href="hak-akses"><i class="fa fa-solid fa-user-lock"></i><span>Hak
                                        Akses/Role</span></a></li>
                            {{-- <li><a class="nav-link {{ Request::is('aktivitas-user') ? 'active' : '' }}"
                                    href="aktivitas-user"><i class="fa fa-solid fa-list"></i><span>Aktivitas
                                        User</span></a></li> --}}
                        @endif

                        @if (auth()->user()->role->role === 'operator')
                            <li class="sidebar-item">
                                <a class="sidebar-link nav-link {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}"
                                    href="/">
                                    <i class="fa fa-area-chart" aria-hidden="true"></i> <span class="align-middle">Dashboard</span>
                                </a>
                            </li>

                            <li class="menu-header">DATA MASTER</li>
                            <li class="dropdown">
                                <a href="#"
                                    class="nav-link has-dropdown {{ Request::is('barang') || Request::is('jenis-barang') || Request::is('satuan-barang') ? 'active' : '' }}"
                                    data-toggle="dropdown"><i class="fa fa-cube" aria-hidden="true"></i><span>Data
                                        Barang</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link {{ Request::is('barang') ? 'active' : '' }}"
                                            href="/barang"><i class="fa fa-solid fa-circle fa-xs"></i> Nama Barang</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('jenis-barang') ? 'active' : '' }}"
                                            href="/jenis-barang"><i class="fa fa-solid fa-circle fa-xs"></i> Jenis</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('satuan-barang') ? 'active' : '' }}"
                                            href="/satuan-barang"><i class="fa fa-solid fa-circle fa-xs"></i>
                                            Satuan</a></li>
                                    <li><a class="nav-link {{ Request::is('rak') ? 'active' : '' }}"
                                            href="/rak"><i class="fa fa-solid fa-circle fa-xs"></i> Data Rak</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#"
                                    class="nav-link has-dropdown {{ Request::is('supplier') || Request::is('customer') ? 'active' : '' }}"
                                    data-toggle="dropdown"><i
                                        class="fa fa-sharp fa-solid fa-building"></i><span>Perusahaan</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link {{ Request::is('supplier') ? 'active' : '' }}"
                                            href="/supplier"><i class="fa fa-solid fa-circle fa-xs"></i> Supplier</a>
                                    </li>
                                    <li><a class="nav-link {{ Request::is('customer') ? 'active' : '' }}"
                                            href="/customer"><i class="fa fa-solid fa-circle fa-xs"></i> Customer</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="menu-header">TRANSAKSI</li>
                            <li><a class="nav-link {{ Request::is('barang-masuk') ? 'active' : '' }}"
                                    href="barang-masuk"><i class="fa fa-solid fa-arrow-right"></i><span>Barang
                                        Masuk</span></a></li>
                            <li><a class="nav-link {{ Request::is('barang-keluar') ? 'active' : '' }}"
                                    href="barang-keluar"><i class="fa fa-sharp fa-solid fa-arrow-left"></i>
                                    <span>Barang Keluar</span></a></li>
                            <li><a class="nav-link {{ Request::is('kotak-saran') ? 'active' : '' }}"
                                    href="kotak-saran"><i class="fa fa-solid fa-box"></i><span>Kotak Saran</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('order-barang') ? 'active' : '' }}"
                                    href="order-barang"><i class="fa fa-solid fa-store"></i><span>Order
                                        Barang</span></a>
                            </li>

                            <li class="menu-header">LAPORAN</li>
                            <li><a class="nav-link {{ Request::is('laporan-stok') ? 'active' : '' }}"
                                    href="laporan-stok"><i
                                        class="fa fa-sharp fa-reguler fa-file"></i><span>Stok</span></a></li>

                            <li><a class="nav-link {{ Request::is('laporan-kategori') ? 'active' : '' }}"
                                    href="laporan-kategori"><i
                                        class="fa fa-sharp fa-reguler fa-file-invoice"></i><span>Kategori /
                                        Jenis</span></a>
                            </li>

                            <li><a class="nav-link {{ Request::is('laporan-barang-masuk') ? 'active' : '' }}"
                                    href="laporan-barang-masuk"><i
                                        class="fa fa-sign-in" aria-hidden="true"></i><span>Barang Masuk</span></a></li>
                            <li><a class="nav-link {{ Request::is('laporan-barang-keluar') ? 'active' : '' }}"
                                    href="laporan-barang-keluar"><i
                                        class="fa fa-sign-out" aria-hidden="true"></i><span>Barang
                                        Keluar</span></a></li>
                            {{-- <li><a class="nav-link {{ Request::is('laporan-kotak-saran') ? 'active' : '' }}"
                                    href="laporan-kotak-saran"><i
                                        class="fa fa-sharp fa-reguler fa-paste"></i><span>Kotak
                                        Saran</span></a>
                            </li>
                            <li><a class="nav-link {{ Request::is('laporan-order-barang') ? 'active' : '' }}"
                                    href="laporan-order-barang"><i
                                        class="fas fa-sharp fa-reguler fa-file-invoice-dollar"></i><span>Order
                                        Barang</span></a>
                            </li> --}}
                        @endif
                    </ul>

                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">

                    @yield('content')
                    <div class="section-body">
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2024
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>



    <!-- General JS Scripts -->
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/popper.js"></script>
    <script src="assets/modules/tooltip.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="assets/modules/moment.min.js"></script>
    <script src="assets/js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Select2 Jquery -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
        integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>

    <!-- Datatables Jquery -->
    <script type="text/javascript" src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Sweet Alert -->
    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Day Js Format -->
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>


    @stack('scripts')


    <script>
        $(document).ready(function() {
            var currentPath = window.location.pathname;

            $('.nav-link a[href="' + currentPath + '"]').addClass('active');
        });
    </script>

</body>

</html>
