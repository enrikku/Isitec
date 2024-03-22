<?php
$cookie = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;

if ($cookie == null) {
    header("Location: http://127.0.0.1/isitec/");
}
