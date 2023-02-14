<?php

class MainController {

    public function render($template, $props) {
        ob_start();
        include 'templates/' . $template . '.php';
        $output = ob_get_clean();
        echo $output;
    }
    
}