<?php

/**
 * Layout Library Class
 *
 * @author Sifat kabir
 * @version 1.0
 * @location ./application/libraries/Layout.php
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Layout {

    private $_ci;
    public $data = array();     // array to pass variables in views
    public $config;                 //array of default config variables. $config['default'] in layout config
    public $meta;                   // html meta tag for layout.
    public $layout;                 // name of the layout
    public $title_for_layout;   // title of html rendered html page
    public $style_for_layout = array();     // css files/code for the layout
    public $script_for_layout = array();        // script files/code for the layout
    public $title_separator;    // symble to separate tile if two words
    public $auto_title = true;  // If set to true title will be set by according to method and controller
    public $auto_render = true;
    public $baseUrl;

    /*
     * Class constructor
     *  
     * The constructor can be passed an array of key value pair
     */
    function __construct($params=array()) {
        $this->_ci = &get_instance();
        $this->_ci->load->helper('html');
        $this->_ci->config->load('layout');
        $this->config = $this->_ci->config->item('default');
        $this->baseUrl = $this->_ci->config->slash_item('base_url');
        
        if (!empty($params)) {
            foreach ($params as $key=>$value) {
                $this->$key=$value;
            }
        }
    }
    
    /*
     * function to render layout and pass variables
     * 
     * @access public
     * @param string
     * @param array
     * @return void
     */
    function render($view_name, $data = array()) {
        //setting layout
        $this->layout = isset($this->layout) ? $this->layout : $this->config['layout'];
        $full_layout_path = $this->_ci->config->item('layout_folder') . '/' . $this->layout;

        //pass the class refarence to the views
        //$Layout object in views
        $this->data['Layout'] = &$this;
        $this->data = array_merge($this->data, $data);
        $this->data['contents_for_layout'] = $this->_ci->load->view($view_name, $this->data, TRUE);

        $this->data['meta'] = isset($this->meta) ? $this->meta : $this->config['meta'];
        $this->data['title_for_layout'] = $this->_get_title();
        $this->data['script_for_layout'] = $this->_get_scripts();
        $this->data['style_for_layout'] = $this->_get_styles();

        $this->_ci->load->view($full_layout_path, $this->data);
    }
    
    /*
     * assign variable to a public variable to make them available
     * on views
     * 
     * @access public
     * @param array
     * @return void
     */
    function set($var) {
        if (!is_array($var) || empty($var)) {
            return false;
        }
        foreach ($var as $key => $value) {
            $this->data[$key] = $value;
        }
    }
    
    /*
     * elements are views to display in the layout 
     *  
     * @access public
     * @param stirng
     * @return string
     */
    function element($name) {
        $element_path = $this->_ci->config->item('layout_folder') . '/' . $this->_ci->config->item('element_folder') . '/' . $name;

        return $this->_ci->load->view($element_path, $this->data, true);
    }
    
    /*
     * add new javascript 
     * 
     * @access public
     * @param string    source or js code
     * @param string    link, embed
     * @return void
     */
    function add_js($script, $type = 'link') {
        $this->script_for_layout[] = array(
            'type' => $type,
            'src' => $script,
        );
    }
    
    /*
     * add new css
     * 
     * @access public
     * @param string    style source or code
     * @param string    link, import, embed
     * @param string    all, screen, print (css media)
     * @return void
     */
    function add_css($style, $type = 'link', $media = 'all') {
        $this->style_for_layout[] = array(
            'type' => $type,
            'src' => $style,
            'media' => $media,
        );
    }
    
    /*
     * Get title
     * 
     * @access private
     * @return string
     */
    private function _get_title() {
        if ($this->title_for_layout) {
            return $this->title_for_layout;
        }
        if (!$this->auto_title) {
            return $this->config['title_for_layout'];
        }
        if (!$this->title_separator) {
            $this->title_separator = $this->config['title_separator'];
        }


        $title = '';

        $seg2 = $this->_ci->uri->segment(2) ? $this->_ci->uri->segment(2) : $this->_ci->router->method;
        $seg1 = $this->_ci->uri->segment(1) ? $this->_ci->uri->segment(1) : $this->_ci->router->class;

        if ($seg2 != 'index') {
            $title.=$seg2 . ' ' . $this->title_separator . ' ' . $seg1;
        } else {
            $title = $seg1;
        }

        $this->_ci->load->helper('inflector');

        return humanize($title);
    }

    /*
     * Get all javascripts
     * 
     * @access private
     * @return string
     */
    private function _get_scripts() {
        $s = '';

        if (empty($this->script_for_layout)) {
            return $s;
        }
        foreach ($this->script_for_layout as $script) {

            switch ($script['type']) {
                case 'link':
                    if (preg_match('@^http:\/\/|^https:\/\/@', $script['src'])) {
                        $src = $script['src'];
                    } else {
                        $src = $this->_ci->config->item('js_file_path') . $script['src'];
                    }
                    $s.='<script src="' . $src . '" type="text/javascript"></script>';
                    break;
                case 'embed':
                    $s.='<script type="text/javascript">';
                    $s.=$script['src'];
                    $s.='</script>';
                    break;

                default:
                    show_error('array formate for js is not correct');
                    break;
            }
        }
        return $s;
    }

    /*
     * Get all styles
     * 
     * @access private
     * @return string
     */
    private function _get_styles() {
        $s = '';

        if (empty($this->style_for_layout)) {
            return $s;
        }

        foreach ($this->style_for_layout as $style) {
            
            switch ($style['type']) {
                case 'link':
                    if (preg_match('@^http:\/\/|^https:\/\/@', $style['src'])) {
                        $src = $style['src'];
                    } else {
                        $src = $this->_ci->config->item('css_file_path') . $style['src'];
                    }
                    $s.='<link href="' . $src . '" media="' . $style['media'] . '" type="text/css" rel="stylesheet" />';

                    break;
                case 'import':
                    $s.='<style type="text/css">';
                    $s.='@import url("' . $style['src'] . '")';
                    if ($style['media']) {
                        $s.=' ' . $style['media'];
                    }
                    $s.=';</style>';

                    break;
                case 'embed':
                    $s.='<style type="text/css" media="' . $style['media'] . '">';
                    $s.=$style['src'];
                    $s.='</style>';

                    break;
                default:
                    show_error('array formate for style is not correct');
                    break;
            }
        }
        return $s;
    }

}