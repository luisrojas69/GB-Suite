@can('ver inventario it')
<a class="nav-link" href="{{ route('inv.it.index') }}">
    <i class="fas fa-laptop"></i> <span>Inventario IT</span>
</a>
@endcan

@can('ver inventario admin')
<a class="nav-link" href="{{ route('inv.admin.index') }}">
    <i class="fas fa-couch"></i> <span>Activos Fijos</span>
</a>
@endcan