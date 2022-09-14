<?php

$ROOT = $_SERVER['DOCUMENT_ROOT'];

$full_router = $_SERVER['REQUEST_URI'];
$router = strtok($full_router, '?');

if ($router === '/' || $router === '/home' || $router === '/index.php' || $router === '/home.php' || $router === '/home/') {
    include($ROOT . '/pages/home.php');
} elseif ($router === '/admin' || $router === '/admin.php' || $router === '/admin/') {
    include($ROOT . '/pages/admin.php');
}elseif ($router === '/edit_member' || $router === '/edit_member.php' || $router === '/edit_member/') {
    include($ROOT . '/pages/edit_member.php');
}elseif ($router === '/edit_stockvell' || $router === '/edit_stockvell.php' || $router === '/edit_stockvell/') {
    include($ROOT . '/pages/edit_stockvell.php');
} elseif ($router === '/dashboard' || $router === '/dashboard.php' || $router === '/dashboard/') {
    include($ROOT . '/pages/dashboard.php');
} elseif ($router === '/about' || $router === '/about.php' || $router === '/about/') {
    include($ROOT . '/pages/about.php');
} elseif ($router === '/privicypolicy' || $router === '/privicypolicy.php' || $router === '/privicypolicy/') {
    include($ROOT . '/pages/privicypolicy.php');
} elseif ($router === '/login' || $router === '/login.php'  || $router === '/login/') {
    include($ROOT . '/pages/login.php');
}elseif ($router === '/packs' || $router === '/packs.php'  || $router === '/packs/') {
    include($ROOT . '/pages/packs.php');
}elseif ($router === '/pack_single' || $router === '/pack_single.php'  || $router === '/pack_single/') {
    include($ROOT . '/pages/pack_single.php');
} elseif ($router === '/signup' || $router === '/signup.php' || $router === '/signup/') {
    include($ROOT . '/pages/signup.php');
} elseif ($router === '/forget_password' || $router === '/forget_password.php' || $router === '/forget_password/') {
    include($ROOT . '/pages/forget_password.php');
} elseif ($router === preg_match("/single\/[0-9]/i", $router)) {
    include('single-product-page.php');
} else {
    include($ROOT . '/pages/404.php');
}

// echo phpinfo();
