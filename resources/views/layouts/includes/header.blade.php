
<style>
/* Styles untuk avatar dan nama pengguna */
.avatar .avatar-text {
    color: rgb(224, 224, 224);
    font-family: 'Arial', sans-serif;
    font-weight: bold;
}

.user-name {
    font-family: 'Arial', sans-serif;
    color: rgb(224, 224, 224);
    font-weight: 500;
}

.custom-avatar-bg {
    background-color: rgb(28, 84, 168);
}

.fas.fa-chevron-down {
    font-size: 13px;
}

/* Styles untuk dropdown notifikasi */
.notification-dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    width: 320px;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    display: none;
    color: #333;
    overflow: hidden;
}

.notification-header {
    border-bottom: 1px solid #dee2e6;
}

.notification-item {
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.notification-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
}

.notification-body {
    max-height: 300px;
    overflow-y: auto;
}

.notification-footer {
    border-top: 1px solid #dee2e6;
}


</style>
<header class="header header-sticky p-0 mb-3 bg-dark text-white">
    <div class="container-fluid border-bottom px-3">
        <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -12px;">
        <i class="fa-solid fa-bars icon icon-menu icon-md" style="color: white;"></i>
        </button>
        <ul class="header-nav ms-auto d-flex align-items-center">
    <!-- Notifikasi Bell -->
    <li class="nav-item dropdown position-relative">
        <a class="nav-link" href="#" role="button" id="notification-bell" onclick="toggleNotification(event)">
            <span class="d-inline-block my-1 mx-2 position-relative">
                <i class="fa-solid fa-bell icon icon-lg" style="font-size: 22px; color: #f8f9fa;"></i>
               
            </span>
        </a>
        
    </li>
    
    <!-- Avatar dan Nama Pengguna -->
    <li class="nav-item dropdown">
        <a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <div class="avatar avatar-md">
                <div class="avatar custom-avatar-bg avatar-text">
                    {{ strtoupper(substr(session('user_fullname'), 0, 1)) }}
                </div>
                </div>
                <!-- Nama Pengguna -->
                <span class="ms-2 user-name">{{ session('user_fullname') }} <i class="fas fa-chevron-down"></i></span>
            </div>
        </a>
        
        <!-- Dropdown Menu -->
        <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item fw-medium p-md-2-2" id="listProfile" href="javascript:void(0);">
                <i class="fa-regular fa-user icon me-2 fw-medium"></i>Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="login">
                <i class="fa-solid fa-right-from-bracket icon me-2"></i>Sign out
            </a>
        </div>
    </li>
</ul>
    </div>
</header>
