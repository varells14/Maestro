<style>
.sidebar-nav .nav-link {
    font-size: 16px;
}

.sidebar-nav .nav-icon {
    font-size: 18px !important;
    margin-right: 10px;
}

/* Style tambahan untuk icon */
.sidebar-nav .nav-link i {
    width: 20px;
    text-align: center;
    transition: color 0.3s;
}

/* Hover effect untuk icon */
.sidebar-nav .nav-link:hover i {
    color: #4dabf7;
}
</style>
<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    {{-- Dashboard --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.dashboard') }}">
            <i class="fa-solid fa-grid-2 nav-icon"></i> Dashboard
        </a>
    </li>

    {{-- Material Request Dropdown --}}
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#" data-coreui-target="#materialRequestMenu">
            <i class="fa-solid fa-recycle nav-icon"></i> Material Request
        </a>
        <ul class="nav-group-items" id="materialRequestMenu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.material_request') }}">Request</a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.material_history') }}">History</a>
            </li>
        </ul>
    </li>

    {{-- Purchase Order --}}
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#" data-coreui-target="#purchaseOrderMenu">
            <i class="fa-solid fa-shop nav-icon"></i> Purchase Order
        </a>
        <ul class="nav-group-items" id="purchaseOrderMenu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.purchase_request') }}">Request</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.purchase_history') }}">History</a>
            </li>
        </ul>
    </li>

    {{-- Inventory Dropdown --}}
    <li class="nav-group">
        <a class="nav-link nav-group-toggle" href="#" data-coreui-target="#inventoryMenu">
            <i class="fa-solid fa-box-archive nav-icon"></i> Inventory
        </a>
        <ul class="nav-group-items" id="inventoryMenu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.index') }}">Stock</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stock-ins.index') }}">In</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stock-outs.index') }}">Out</a>
            </li>
        </ul>
    </li>
</ul>