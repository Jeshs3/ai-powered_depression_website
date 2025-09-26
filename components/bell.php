<?php
// Get current page path relative to site root
$currentpg = $_SERVER['PHP_SELF']; 
?>

<li class="nav-item dropdown">
    <a class="nav-link position-relative" 
        href="javascript:void(0);" 
        id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notifications">
            <i class="fa-solid fa-bell me-2 icon-md"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifCount">
                0
                <span class="visually-hidden">unread notifications</span>
            </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg" aria-labelledby="navbarDropdown" style="width: 350px;">
        <li class="dropdown-header bg-primary text-white">Notifications</li>
        <li>
            <div id="notifList" class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                <div class="text-center text-muted py-3">Loading...</div>
            </div>
        </li>
        <li>
            <a class="dropdown-item text-center text-primary" href="../Admin/view_notif.php">View All</a>
        </li>
    </ul>
</li>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script/notification.js"></script>
