<div class="position-absolute w-100 bg-dark text-white d-flex align-items-center z-1">
    <ul class="d-flex align-items-center justify-content-between mb-0 py-2 list-unstyled w-100">
        <li>
            <h1 class="ps-2 fs-4 font-bold mb-0">Dashboard - PHPCMS</h1>
        </li>
        <li>
            <ul class="d-flex align-items-center list-unstyled">
                <li class="me-2">Hello, <?= $_SESSION['first_name'] ?></li>
                <li class="me-2">
                    <a href="/" class="btn btn-sm btn-success">Visit Site</a>
                </li>
                <li class="me-2">
                    <a href="logout.php" class="btn btn-sm btn-danger">Logout →</a>
                </li>
            </ul>    
        </li>
    </ul>
</div>