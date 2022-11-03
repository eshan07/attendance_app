<div class="sb-sidenav-menu">
    <div class="nav">
        <a class="nav-link" href="{{route('home')}}">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
        @can('role-create')
        <a class="nav-link" href="{{ route('admin.roles.index') }}">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Manager Role
        </a>
        @endcan
        @can('user-create')
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
            Users
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{route('admin.users.index')}}">List</a>
                <a class="nav-link" href="{{route('admin.users.create')}}">Add New</a>
            </nav>
        </div>
        @endcan
        @can('attendance')
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAtten" aria-expanded="false" aria-controls="collapseAtten">
            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
            Attendance
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseAtten" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{route('attendances.statement')}}" onclick="event.preventDefault();
                       document.getElementById('attendance-form').submit();">Log</a>
            </nav>
            <form id="attendance-form" action="{{ route('attendances.statement') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        @endcan
</div>
</div>
