<?php
// Определите текущую страницу по URL.
$currentPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Функция для проверки активного пункта меню.
function isActive($page, $currentPage)
{
    return $page == $currentPage ? 'active' : '';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/nobel-app">
            <img src="/z1/frontend/assets/nobel.ico" width="30" height="30" class="d-inline-block align-top" alt="">
            Nobel Prizes
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= isActive('/nobel-app', $currentPage) ?>" aria-current="page"
                        href="/nobel-app">Prizes</a>
                </li>
                <li class="nav-item">
                    <a onclick="sessionStorage.setItem('currentReceiver', JSON.stringify({new: true}))"
                        class="nav-link <?= isActive('/nobel-app/nobel-receiver/new', $currentPage) ?>"
                        href="/nobel-app/nobel-receiver/new">Add
                        new receiver</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isActive('/nobel-app/login', $currentPage) ?>" href="/nobel-app/login">
                        <button hidden id="ref-login" type="button" class="btn btn-success py-0">
                            Login | Register
                        </button>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="nav-link">
                        <button hidden id="logout" type="button" class="btn btn-danger py-0">Logout</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script type="module" src="/z1/frontend/menu/menu.js"></script>