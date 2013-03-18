<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * 
 */
function layout() {
    $ci=& get_instance();
    if(isset($ci->layout->auto_render) && $ci->layout->auto_render === true) {
        $file_name = $ci->router->directory != '' ? $ci->router->directory : '';
        $file_name .= $ci->router->class.'/'.$ci->router->method;
        
        $ci->layout->render($file_name);
    }
    return false;
}