<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-3">BORAURE<sup> APP</sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Nav::isRoute('home') }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>

    <hr class="sidebar-divider">

    {{-- MÓDULO LOGÍSTICA (TALLER) --}}

     {{-- @include('layouts.partials.sidebar.menu_animales') --}}

    

     {{-- @include('layouts.partials.sidebar.menu_administrador') --}}

     {{-- @include('layouts.partials.sidebar.menu_costos') --}}

    {{-- MÓDULO PECUARIO --}}


    {{-- MÓDULO AGROINDUSTRIAL (CAÑA) --}}

    {{-- ÁREAS DE PRODUCCIÓN --}}

    {{-- INVENTARIO Y MAESTRAS PECUARIAS --}}


    {{-- CONFIGURACIONES Y PERFIL --}}




    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item {{ Nav::isRoute('profile') }}">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>{{ __('Mi Pefil') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Nav::isRoute('about') }}">
        <a class="nav-link" href="{{ route('about') }}">
            <i class="fas fa-fw fa-hands-helping"></i>
            <span>{{ __('Acerca de:') }}</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>