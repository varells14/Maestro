<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.dashboard_admin')}}">
            {{-- <svg class="nav-icon">
                <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-speedometer"></use>
            </svg> Dashboard<span class="badge badge-sm bg-info ms-auto">NEW</span> --}}
            <i class="fa-solid fa-grid-2 nav-icon"></i>Home
        </a>
    </li>
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="fa-solid fa-grid-2 nav-icon"></i>App Level
        </a>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.app_level')}}">Create Level Approve</a></li>
        </ul>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.list_level')}}">List Level Approve</a></li>
        </ul>
     
    </li>
   
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="fa-solid fa-file-import nav-icon"></i>Permohonan Izin
        </a>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.pulang')}}">Izin Pulang Cepat</a></li>
        </ul>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.keluar')}}">Izin Keluar Kantor</a></li>
        </ul>
     
    </li>
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#">
            <i class="fa-solid fa-file-import nav-icon"></i>Approval Permohonan Izin
        </a>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.approval_pulang')}}">Approval Izin Pulang Cepat</a></li>
        </ul>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.approval_keluar')}}">Approval Izin Keluar Kantor</a></li>
        </ul>
    </li>
     <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#">
        <i class="fa-solid fa-history nav-icon"></i>History Permohonan Izin
        </a>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.history_pulang')}}">History Izin Pulang Cepat</a></li>  
        </ul>
        <ul class="nav-group-items compact">
            <li class="nav-item"><a class="nav-link" href="{{route('admin.history_keluar')}}">History Izin Keluar Kantor</a></li>  
        </ul>
      
        </ul>
    </li>
</ul>