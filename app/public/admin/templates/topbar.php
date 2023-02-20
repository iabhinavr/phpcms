<div class="position-absolute w-100 d-flex align-items-center z-1 bg-body-tertiary border-bottom">
    <ul class="d-flex align-items-center justify-content-between mb-0 py-2 list-unstyled w-100">
        <li>
            <ul class="d-flex align-items-center list-unstyled">
                <li class="me-2">
                    <h1 class="ps-2 fs-4 font-bold mb-0">Dashboard - PHPCMS</h1>
                </li>
                <li>
                    <span id="theme-toggler">
                        <svg xmlns="http://www.w3.org/2000/svg" height="21" viewBox="0 96 960 960" width="21">
                            <use href="#light-fill"></use>
                        </svg>
                    </span>
                    
                </li>
            </ul>
            
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