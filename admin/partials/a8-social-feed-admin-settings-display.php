<?php

namespace ASF\Admin;


$graph_api = A8_Social_Feed_Graph_API::getInstance();
$users = A8_Social_Feed_Users::getInstance();
$categories = A8_Social_Feed_Categories::getInstance();
?>

<div class="wrap">
    <h2>Settings</h2>

    <hr>
    <h3>Graph API Details</h3>
    <span>Facebook App Client ID: </span>
    <input type="text" value="<?=$graph_api->get_client_id()?>" readonly>
    <br>
    <br>
    <span>Facebook App Secret: </span>
    <input type="text" value="<?=$graph_api->get_app_secret()?>" readonly>
    <br>
    <br>
    <span>Long Lived Access Token: </span>
    <input type="text" value="<?=$graph_api->get_access_token()?>" readonly>
    <br>
    <br>
    <span>Instagram User ID: </span>
    <input type="text" value="<?=$graph_api->get_ig_id()?>" readonly>
    <br>
    <br>

    <hr>

    <h3>Update API Details: </h3>
    <form method="POST">
        <span>Facebook App Client ID</span>
        <input type="text" id="client_id" name="app_details[client_id]" value="<?=$graph_api->get_client_id()?>" placeholder="Facebook App Client ID">
        <br>
        <br>
        <span>Facebook App Secret</span>
        <input type="text" id="app_secret" name="app_details[app_secret]" value="<?=$graph_api->get_app_secret()?>" placeholder="Facebook App Secret">
        <br>
        <br>
        <span>Short Lived Access Token</span>
        <input type="text" id="access_token" name="app_details[access_token]" placeholder="Access Token">
        <br>
        <br>
        <button type="submit" class="button-primary button">Submit</button>
    </form>

    <?php
        if(isset($_POST['app_details'])){
            //var_dump($_POST['app_details']);
            //die();
            $graph_api -> update_app_details($_POST['app_details']['access_token'], $_POST['app_details']['client_id'], $_POST['app_details']['app_secret']);
        }
    ?>

    <hr>
    <h3>Feed Settings</h3>
    <form method="POST">
        <span>Maximum Accounts per Profile Feed</span>
        <input type="number" id="max_accounts" name="feed_settings[max_accounts]" value = "<?=$graph_api -> get_max_accounts(); ?>">
        <br>
        <br>
        <span>Maximum Posts to Fetch per Account</span>
        <input type="number" id="max_posts" name="feed_settings[max_posts]" value = "<?=$graph_api -> get_max_posts(); ?>">
        <br>
        <br>
        <span>Maximum Total Posts</span>
        <input type="number" id="abs_max_posts" name="feed_settings[abs_max_posts]" value = "<?=$graph_api -> get_abs_max_posts(); ?>">
        <br>
        <br>
        <button type="submit" class="button-primary button">Submit</button>
    </form>

    <?php
        if(isset($_POST['feed_settings'])){
            //var_dump($_POST['feed_settings']);
            //die();
            if(is_numeric($_POST['feed_settings']['max_accounts']) && is_numeric($_POST['feed_settings']['max_posts']) && is_numeric($_POST['feed_settings']['abs_max_posts'])){
                $graph_api -> update_feed_settings(intval($_POST['feed_settings']['max_accounts']),intval($_POST['feed_settings']['max_posts']),intval($_POST['feed_settings']['abs_max_posts']));
            }
        }
    ?>
    <!--
        TBD:
        use values above to change API call to lessen posts called
        mmodified API call is in graph explorer
        need media id to get posts: https://stackoverflow.com/questions/16758316/where-do-i-find-the-instagram-media-id-of-a-image?rq=3&answertab=modifieddesc#tab-top
     -->
    


</div>