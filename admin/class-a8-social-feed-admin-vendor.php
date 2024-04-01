<?php
//class/template for creating the display for individual accounts
//actually using classes like how theyre supposed to be used what?????

namespace ASF\Admin;

class Vendor_Display extends Display{
    function generate_html(){
        $html = '
        <a href="https://www.instagram.com/' . $this->username . '" target="_blank" class="hide-hyperlink">
            <div class="account '.$this->username.'" >
                <img src = "' . $this->profile_picture . '" width = "150">
                <p class="account-name">' . $this->account_name . '</p>
                <p class="account-username">' . $this->username . '</p>
                <p class="account-bio">' . $this->bio . '</p>
            </div>
        </a>';
        return $html;
    }
    
}