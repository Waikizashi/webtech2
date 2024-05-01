<?php
$currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive($page, $currentPage)
{
    return $page == $currentPage ? 'active' : '';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-light">
    <div class="container-fluid">
        <a class="text-primary navbar-brand">
            <img src="/z2/frontend/assets/sapp.ico" width="30" height="30" class="d-inline-block align-top" alt="">
            University info
        </a>

        <button class="navbar-toggler bg-secondary" type="button" data-toggle="collapse" data-target="#mainNav"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-primary <?= isActive('/parse-app', $currentPage) ?>" aria-current="page"
                        href="/parse-app/bachelors">Bachelor themes</a>
                </li>
                <li class="nav-item">
                    <a onclick="sessionStorage.setItem('currentReceiver', JSON.stringify({new: true}))"
                        class="nav-link text-primary <?= isActive('/parse-app/timetable', $currentPage) ?>"
                        href="/parse-app/timetable">Timetable</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="/nobel-app/login">
                        <button hidden id="ref-login" type="button" class="btn btn-success py-0">
                            Login | Register
                        </button>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="nav-link">
                        <button hidden id="logout" type="button" class="btn btn-danger py-0">Logout</button>
                    </div>
                </li> -->
            </ul>
        </div>
    </div>
</nav>
<!-- <script type="module" src="z2/frontend2/menu/menu.js"></script> -->