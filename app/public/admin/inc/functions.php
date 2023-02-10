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