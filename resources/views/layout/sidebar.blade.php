  <!-- Page Body Start-->
  <div class="page-body-wrapper sidebar-icon">
    <!-- Page Sidebar Start-->
<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="https://laravel.pixelstrap.com/viho/assets/images/dashboard/1.png" alt="" />
        <div class="badge-bottom"><span class="badge badge-primary">New</span></div>
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
                        <a class="nav-link menu-title link-nav" href="#"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="airplay"></i><span>Data</span></a>
                        <ul class="nav-submenu menu-content"  style="display: none;">
                            @if (Auth::user()->level == 1 )
                            <li><a href="/user/user" class="">Data User</a></li>
                             @endif
                            <li><a href="/driver/driver" class="">Data Driver</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="layout"></i><span>Page layout</span></a>
                        <ul class="nav-submenu menu-content"  style="display: none;">
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/boxed-layout" class="">Boxed</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/layout-rtl" class="">RTL</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/layout-dark" class="">Dark</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-light" class="">Footer Light</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-dark" class="">Footer Dark</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/page-layout/footer-fixed" class="">Footer Fixed</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Components</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title " href="javascript:void(0)"><i data-feather="box"></i><span>Ui Kits</span></a>
                        <ul class="nav-submenu menu-content" style="display: none;">
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/state-color" class="">State color</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/typography" class="">Typography</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/avatars" class="">Avatars</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/helper-classes" class="">helper classes</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/grid" class="">Grid</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/tag-pills" class="">Tag & pills</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/progress-bar" class="">Progress</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/modal" class="">Modal</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/alert" class="">Alert</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/popover" class="">Popover</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/tooltip" class="">Tooltip</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/loader" class="">Spinners</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/dropdown" class="">Dropdown</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/according" class="">Accordion</a></li>
                            <li>
                                <a class="submenu-title  " href="javascript:void(0)">
                                    Tabs<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: none;">
                                    <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/tab-bootstrap" class="">Bootstrap Tabs</a></li>
                                    <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/tab-material" class="">Line Tabs</a></li>
                                </ul>
                            </li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/navs" class="">Navs</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/box-shadow" class="">Shadow</a></li>
                            <li><a href="https://laravel.pixelstrap.com/viho/ui-kits/list" class="">Lists</a></li>
                        </ul>
                    </li>


                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>

