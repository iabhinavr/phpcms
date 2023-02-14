<?php

class MainHandler {

    public function render($template, $props) {
        ob_start();
        include 'templates/' . $template . '.php';
        $output = ob_get_clean();
        echo $output;
    }
    
}