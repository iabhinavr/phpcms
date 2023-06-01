<?php

function get_template($template_name) {
    ob_start();

    include 'templates/' . $template_name . '.php';

    $template_content = ob_get_clean();

    echo $template_content;
}

function get_home_url() {
    return "http://php-cms.local:8084";
}

function generate_csrf_token() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function validate_csrf_token($token) {
    if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return ["status" => false, "result" => "Invalid token"];
    }
    return ["status" => true, "result" => "Token validated"];
}