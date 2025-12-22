<li class="nav-item {{ Nav::isRoute('produccion.animales.costos.expenses.index') }}">
        <a class="nav-link" href="{{ route('produccion.animales.costos.expenses.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Costos') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Nav::isRoute('produccion.animales.costos.profit.index') }}">
        <a class="nav-link" href="{{ route('produccion.animales.costos.profit.index') }}">
            <i class="fas fa-fw fa-hands-helping"></i>
            <span>{{ __('Profit Plus Exp:') }}</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">