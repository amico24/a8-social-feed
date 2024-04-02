<?php

namespace ASF\Admin;


$graph_api = A8_Social_Feed_Graph_API::getInstance();
$users = A8_Social_Feed_Users::getInstance();
$categories = A8_Social_Feed_Categories::getInstance();
?>

<div class="wrap">
    <h2>Multi Insta Feed Settings</h2>

    <br>
    <form method="POST">
        <input type="text" id="client_id" name="app_details[client_id][" value="<?=$graph_api->get_client_id()?>" placeholder="Facebook App Client ID">
        <br>
        <br>
        <input type="text" id="app_secret" name="app_details[app_secret]" value="<?=$graph_api->get_app_secret()?>" placeholder="Facebook App Secret">
        <br>
        <br>
        <input type="text" id="access_token" name="app_details[access_token]" placeholder="Access Token">
        <br>
        <br>
        <button type="submit">Submit Graph API Details</button>
    </form>

    <?php
        if(isset($_POST['app_details'])){
            //var_dump($_POST['app_details']);
            //die();
            $graph_api -> update_app_details($_POST['app_details']['access_token'], $_POST['app_details']['client_id'], $_POST['app_details']['app_secret']);
        }
    ?>


    <p>Long Lived Access Token:</p>
    <input type="text" value="<?=$graph_api->get_access_token()?>" readonly>

    <p>Instagram User ID: <?=$graph_api->get_ig_id()?></p>

    <hr>

    <h2>Connected Accounts</h2>
    <p>Add Business/Creator Account:</p>
    <form method="POST">
        <input type="text" id="new_account" name="new_account" placeholder="Username">
        <button type="submit">Find Account</button>
    </form>

    <?php if (isset($_POST['new_account'])){
        if ($graph_api->account_exists($_POST['new_account'])){
            $users -> add_user($_POST['new_account']);
            new A8_Social_Feed_Errors('Account Added.', 'notice-success');
        
        } else{
            new A8_Social_Feed_Errors('Instagram account does not exist or is not a Business/Creator account.', 'notice-error');
        }        
    } ?>

</div>