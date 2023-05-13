  <!-- Page Body Start-->
  <div class="page-body-wrapper sidebar-icon">
    <!-- Page Sidebar Start-->
<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="https://laravel.pixelstrap.com/viho/assets/images/dashboard/1.png" alt="" />

        <a href="user-profile"> <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6></a>
        <p class="mb-0 font-roboto">{{ Auth::user()->email }}</p>

    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Umum</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav" href="/dashboard"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav" href="/maps/maps"><i data-feather="map"></i><span>Maps</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="airplay"></i><span>Data</span></a>
                        <ul class="nav-submenu menu-content"  style="display: none;">
                            @if (Auth::user()->level == 1 )
                            <li><a href="/user/user" class="">Data User</a></li>
                             @endif
                            <li><a href="/driver/driver" class="">Data Driver</a></li>
                            <li><a href="/lokasi/lokasi" class="">Data Lokasi</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav" href="/jarak/jarak"><i data-feather="map"></i><span>Jarak</span></a>
                    </li>
                    {{-- <li class="dropdown">
                        <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="layout"></i><span>Page layout</span></a>
                        <ul class="nav-submenu menu-content"  style="display: none;">
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/boxed-layout" class="">Boxed</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/layout-rtl" class="">RTL</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/layout-dark" class="">Dark</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-light" class="">Footer Light</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-dark" class="">Footer Dark</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-fixed" class="">Footer Fixed</a></li>
                        </ul>
                    </li> --}}



                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>

