<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * 
 */
function layout() {
    $ci=& get_instance();
    if($ci->layout->auto_render) {
        $file_name=$ci->router->class.'/'.$ci->router->method;
        $ci->layout->render($file_name);
    }
    return false;
}