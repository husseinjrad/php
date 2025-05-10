<?php
session_start();



function get_session($key)
{
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    }
    if (isset($_COOKIE[$key])) {
        return json_decode($_COOKIE[$key], true);
    }
    return null;
}

function add_session($key, $value)
{
    if (!headers_sent() && is_writable(session_save_path())) {
        setcookie($key, json_encode($value), time() + (30), '/');
    } else {
        $_SESSION[$key] = $value;
    }
}


function checkAuth()
{
    return isset(get_session('user')['id']);
}

function go($url, $arg = null, $return = true)
{
    define('URL', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME']);
    $url = URL . '/' . $url;
    $urlParts = explode(".", $url);
    if (end($urlParts) != 'php') {
        $url .= '.php';
    }
    if ($arg) {
        $query = http_build_query($arg);
        if (!empty($query)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . $query;
        }
    }
    if ($return) {
        return $url;
    } else {
        echo "<script>window.location.href='" . htmlspecialchars($url) . "';</script>";
        exit;
    }
}
