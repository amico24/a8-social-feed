<?php

namespace ASF\Admin;

//class for managing categories

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

    /**
     * Adds category to settings
     * 
     * @param mixed $cat_name
     * 
     * @return bool
     */
    function create_category($cat_name){
        if(in_array($cat_name, $this -> categories)){
            new A8_Social_Feed_Errors('Category already exists. Please choose a different name.', 'notice-error');
            return false;
        } else {
            array_push($this -> categories, $cat_name);
            update_option($this -> db_categories, $this -> categories);
            new A8_Social_Feed_Errors('Category Created', 'notice-success');
            return true;
        }
    }

    /**
     * Returns array of category names
     * @return array
     */
    function get_categories(){
        return $this -> categories;
    }


    
    /**
     * Deletes a category from database
     * @param mixed $cat_name
     * 
     * @return void
     */
    function delete_category($cat_name){
        if (($key = array_search($cat_name, $this -> categories)) !== false) {
            unset($this -> categories[$key]);
        }
        update_option($this -> db_categories, $this -> categories);
    }



    /**
     * Changes the name of a category in the database
     * @param mixed $old_name
     * @param mixed $new_name
     * 
     * @return bool
     */
    function edit_category_name($old_name, $new_name){ 
        if(in_array($old_name, $this -> categories)){
            $old_cats = $this -> categories;
            $this -> categories = array_replace($old_cats, array(array_search($old_name, $old_cats) => $new_name));
            $users = A8_Social_Feed_Users::getInstance();
            $users -> update_category_name($old_name, $new_name); //to update users categories
            update_option($this -> db_categories, $this -> categories);
            return true;
        } else {
            return false;
        }
        
    }
}