<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * config file for layout. 
 * @location ./application/config/layout.php
 */



/*
 * javascripts files folder url path
 * this will be added before javascript filenames
 * $config['js_file_path'] = '/js';
 */
$config['js_file_path'] = '';



/*
 * css files folder url path
 * this will be added before css filenames
 * $config['css_file_path'] = '/css';
 */
$config['css_file_path'] = '';



/*
 * layout folder path.
 * it must be under view folder
 */
$config['layout_folder'] = 'layout';


/*
 * elements are fractions of layout saved in defferent php files
 * it must be under layout folder
 */
$config['element_folder'] = 'elements';


/*
 * default layout configaration. 
 * 
 * 'meta'                   = meta for layout. See html helper meta() for details
 * 'title_for_layout'   = If auto_title set to false this will use as title
 * 'title_separator'    = for auto_tile 
 * 'layout'                 = name of the default layout
 * 
 */
$config['default'] = array(
    'meta' => array(
        array(
            'name' => 'Content-type',
            'content' => 'text/html; charset=utf-8',
            'type' => 'equiv'
        )
    ),
    'title_for_layout' => 'Codeigniter layout engine',
    'title_separator'=>'|',
    'layout' => 'default',
);