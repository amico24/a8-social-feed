<?php
namespace ASF\Admin;

class A8_Social_Feed_Admin_Feed_Display {

    private $a = array();

    private $post_list = array();

    private $accounts_to_display = array();

    private $api_data = array();

    private $shortcode_identifier;

    private $feed_length;

    function __construct($atts, $data, $identifier){
        $this -> a = $atts;
        $this -> api_data = $data;
        $this -> shortcode_identifier = $identifier;
        $users = A8_Social_Feed_Users::getInstance();
        switch($this -> a['special']){
            case 'featured':
                $this -> accounts_to_display = $users -> get_featured_users();
                break;
            case 'homepage':
                $this -> accounts_to_display = $users -> get_homepage_users();
                break;
            case 'default':
                $this -> accounts_to_display = $users -> get_users_in_category($this -> a['category']);
                break;
        }

        if(filter_var($this -> a['length'], FILTER_VALIDATE_INT) === true){
            $this -> feed_length = $this -> a['length'];
        } elseif($this -> a['length'] == "many") {
            $this -> feed_length = null;
        } else {
            //add error checking here
        }
		

    }

    function get_accounts_to_display(){
        return $this -> accounts_to_display;
    }

    function generate_checkbox_display(){
        $html_checkbox_snippet = '';
        foreach((A8_Social_Feed_Categories::getInstance()) -> get_categories() as $category){
            $html_checkbox_snippet .= '<label><input type="checkbox" class="category-checkbox" name="category" value="'.$category.'" checked="true">'.$category.'</label>';
        }
        $html_checkbox_snippet = '
            <div class = "category_selector '.$this -> shortcode_identifier.'">
                <p>Select Category</p>'.$html_checkbox_snippet.'
            </div>
        ';
        return $html_checkbox_snippet;
    }

    function generate_feed_display(){
        $html_snippet = '';
        switch ($this -> a['type']) {
            case 'accounts':
                foreach( $this -> accounts_to_display as $account ){
                    $html_snippet .= (new Vendor_Display($this -> api_data[$account])) -> generate_html(); 
                }
                break;
            case 'posts':
                //make array of post objects
                $post_object_list = array();
                foreach($this -> accounts_to_display as $account){
                    $post_count = sizeof($this -> api_data[$account]['business_discovery']['media']['data']);
                    for($i=0; $i < $post_count; $i++){ //for loop so i have the counter
                        $temp = new Post_Display($this -> api_data[$account],$i);
                        //i just realized the consctructor i have for post_display is kinda stupid i should probably change this eventually
                        if($i == ($post_count-1)){
                            $temp -> set_last_post(); //last post has unique is_last_post property
                        }
                        if($temp->post_exists()){
                            array_push($post_object_list, $temp);
                        }
                        
                    }
                }
                /*
                var_dump($post_object_list);
                die();*/
                //sort post objects
                $date_compare = function ($a, $b) {
                    $t1 = strtotime($a->date);
                    $t2 = strtotime($b->date);
                    return $t2 - $t1;
                };
                usort($post_object_list, $date_compare);

                if(!is_null($this -> feed_length)){
                    $post_object_list = array_slice($post_object_list, 0, $this -> feed_length);
                }
                //make html
                foreach( $post_object_list as $post_object ){
                    $html_snippet.=$post_object->generate_html();
                }
                break;
            default:
                $html_snippet = '<p>Feed Type Not Found.</p>';
        }
        return $html_snippet;
    }

    function generate_button_display(){
        $html_load_more_button = '
            <div class = "load-button-section '.$this -> shortcode_identifier.'">
                <button type="button" class = "load-button '.$this -> shortcode_identifier.'">Load More</button>
            </div>
        ';
        return $html_load_more_button;
    }

    function generate_full_display(){
        $html_snippet = '';
        $html_checkbox_snippet = '';
        $html_load_more_button = '';

        if(filter_var($this -> a['checkbox'],FILTER_VALIDATE_BOOLEAN)==true){
            //make checkbox
            $html_checkbox_snippet = $this -> generate_checkbox_display();
        }

        if($this -> a['length'] == "many") {
            $html_load_more_button = $this -> generate_button_display();
            
        }


        $html_snippet = $this -> generate_feed_display();

        return $html_checkbox_snippet .
            '<div class="feed_content '.$this -> shortcode_identifier.'">' . $html_snippet . '
            </div>
            '.$html_load_more_button;
    }

}