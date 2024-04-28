<?php

namespace ASF\Admin;

class Post_Display extends Display{
    private $media_url;
    private $permalink;
    public $date; //setting to public for sorting
    private $type;
    public $is_last_post = false;

    private $post_exists; //for error checking


    /**
     * Summary of __construct
     * @param mixed $api_data for 1 user only
     * @param mixed $post_index
     */
    function __construct($api_data, $post_index){
        $this->post_list = $api_data['business_discovery']['media'];//including the pagination thing for now
        $this->username = $api_data['business_discovery']['username'];

        if(array_key_exists($post_index,$this -> post_list['data'])){
            $this -> post_exists = true;
            $post = $this -> post_list['data'][$post_index];
            $this -> permalink = $post['permalink'];
            $this -> date = $post['timestamp'];
            $this -> type = $post['media_type'];
            $this -> media_url = $post['media_url'];
        }else{
            $this -> post_exists = false;
        }
    }//idk what to do abt pagination yet

    function generate_html(){
        $html = '';
        if($this -> post_exists){
            if(isset($this -> media_url)){
                if ($this -> type == 'VIDEO') {
                    $media_snippet = '
                    <video height="200" width="200" muted autoplay loop controls>
                        <source src="'.$this -> media_url.'" type="video/mp4">
                        Video not found.
                    </video> 
                    ';
                } elseif($this -> type == 'IMAGE' || $this -> type == 'CAROUSEL_ALBUM') {
                    $media_snippet = '
                    <img src="' . $this -> media_url . '" height="200" width="200">
                    ';
                }

                $html = '
                    <a href="'.$this -> permalink.'" target="_blank" class="hide-hyperlink">
                        <div class="post '.$this->username.'">
                            <p>' . $this->username . '</p>
                            '.$media_snippet.'
                        </div>
                    </a>
                ';
            } else {
                //$html = '<p>Post Contains Copyrighted Material</p>'; // should probably change this eventually
            }
        }
        return $html;
    }

    function post_exists(){
        return $this -> post_exists;
    }

    function set_last_post(){
        $this -> is_last_post = true;
    }

    
}