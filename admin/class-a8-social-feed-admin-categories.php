<?php

namespace ASF\Admin;

class A8_Social_Feed_Categories{
    private $db_categories = 'asf_categories';

    private $categories = array();

    private static $instance = null;

    private function __construct() {
        $this -> categories = get_option($this -> db_categories, array());
        if(empty($this -> categories)){
            //i feel like this bit of code is redundant but i dont feel like dealing with extra errors bc of this so im keeping it
            $this -> categories = array();
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new A8_Social_Feed_Categories();
        }
    
        return self::$instance;
    }

    function create_category($cat_name){
        if(in_array($cat_name, $this -> categories)){
            new A8_Social_Feed_Errors('Category already exists. Please choose a different name.', 'notice-error');
        } else {
            array_push($this -> categories, $cat_name);
            update_option($this -> db_categories, $this -> categories);
            new A8_Social_Feed_Errors('Category Created', 'notice-success');
        }
    }

    function get_categories(){
        return $this -> categories;
    }

    function delete_category($cat_name){
        if (($key = array_search($cat_name, $this -> categories)) !== false) {
            unset($this -> categories[$key]);
        }
        update_option($this -> db_categories, $this -> categories);
    }
}