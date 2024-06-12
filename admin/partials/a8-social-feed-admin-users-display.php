<?php
namespace ASF\Admin;

use Account_Table;

$users = A8_Social_Feed_Users::getInstance();
$categories = A8_Social_Feed_Categories::getInstance();
$graph_api = A8_Social_Feed_Graph_API::getInstance();
$accTable = new Account_Table;

$accTable->prepare_items();
?>

<div class="wrap">
    <h2>Profile Management</h2>
    <hr>

    <form action='options.php' method='post'>

        <?php
        settings_fields('ASF-user');
        do_settings_sections($this->plugin_name . '-profiles');
        submit_button("Find Account");
        ?>

    </form>
<!--
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
-->

    <?php 
        $accTable->display();
        $user_data = array();
        foreach($users->get_user_list() as $user){
            $user_data[$user] = $users->get_user($user);
        }
        $table_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'type' => 'profiles',
            'categories' => $categories -> get_categories(),
            'user_data' => $user_data
        );
        wp_localize_script('table_quick_edit', 'table_data', $table_data);
        wp_enqueue_script('table_quick_edit');
    ?>

</div>