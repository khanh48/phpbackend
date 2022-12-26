<?php
require_once("./includes/connect.php")
?>
<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="offcanvas offcanvas-start" tabindex="-1" aria-labelledby="offCanvasExampleLabel"
            id="navbarSupportedContent">
            <!-- Navbar brand -->
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <a class="navbar-brand mt-2 mt-lg-0" href="./">
                    <img src="./lib/images/logo.png" height="50" alt="MDB Logo" loading="lazy" />
                </a>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Projects</a>
                </li>
            </ul>
            <!-- Left links -->
        </div>
        <!-- Collapsible wrapper -->

        <!-- Right elements -->
        <div class="d-flex align-items-center nav-right">
            <a class="nav-link me-3" href="#" data-bs-toggle='modal' data-bs-target='#modal-login'>Đăng nhập</a>
            <!-- Icon -->
            <a class="text-reset me-3" href="#">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Notifications -->
            <div class="dropdown">
                <a class="me-3 dropdown" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                    aria-expanded="true">
                    <i class="fas fa-bell"><span
                            class="badge rounded-pill position-absolute top-0 start-100 translate-middle bg-danger">1</span></i>

                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">Some news</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Another news</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </li>
                </ul>
            </div>
            <!-- Avatar -->
            <div class="dropdown">
                <a class="dropdown" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="<?php echo $userObj->getUser($my_id)["avatar"]; ?>" class="rounded-circle" height="25"
                        alt="avatar" loading="lazy" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li>
                        <a class="dropdown-item" href="./profile?user=<?php echo $my_id; ?>">Trang cá nhân</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Settings</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<?php
if (!isset($_SESSION['userID'])) {
    include("loginform.php");
}

?>
<!-- Navbar -->