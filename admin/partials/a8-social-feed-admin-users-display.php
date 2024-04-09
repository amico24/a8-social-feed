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
    <h2>Users</h2>
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

    <?php 
        $accTable->display();
        $table_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'type' => 'profiles',
            'categories' => $categories -> get_categories()
        );
        wp_localize_script('table_quick_edit', 'table_data', $table_data);
        wp_enqueue_script('table_quick_edit');
    ?>

    <form method="POST">

        <table>
            <tr>
                <th>User</th>
                <th>Type</th>
                <th>Category</th>
                <th>Featured</th>
                <th>Homepage</th>
            </tr>
            <?php foreach($users->get_user_list() as $user): ?>
                <tr>
                    <td><?=$user?></td>
                    <td>
                        <input type = "checkbox" id="story" name="user_option[<?=$user?>][type][0]" value = "story" <?php checked($users->get_user($user)['type'][0] ?? '', 'story')?>>
                        <label for="story">Story</label>
                        <br>
                        <input type = "checkbox" id="vendor" name="user_option[<?=$user?>][type][1]"value = "vendor"<?php checked($users->get_user($user)['type'][1] ?? '', 'vendor')?>>
                        <label for="vendor">Vendor</label>
                        <br>
                        <input type = "checkbox" id="posts" name="user_option[<?=$user?>][type][2]"value = "posts" <?php checked($users->get_user($user)['type'][2] ?? '', 'posts')?>>
                        <label for="posts">Posts</label>
                    </td>
                    <td>
                        <?php $i = 0;
                        foreach($categories->get_categories() as $category):?>
                            <input type = "checkbox" id="<?=$category?>" name="user_option[<?=$user?>][category][<?=$i?>]" value = "<?=$category?>" <?php checked($users->get_user($user)['category'][$i] ?? '', $category)?>>
                            <label for="<?=$category?>"><?=$category?></label>
                            <br>
                        <?php $i++;
                        endforeach ?>
                    </td>
                    <td>
                        <input type = "radio" id="yes_feat" name="user_option[<?=$user?>][featured]" value = "1" <?php checked($users->get_user($user)['featured'] ?? '', 1)?>>
                        <label for="yes_feat">Yes</label>
                        <br>
                        <input type = "radio" id="no_feat" name="user_option[<?=$user?>][featured]" value = "0" <?php checked($users->get_user($user)['featured'] ?? '', 0)?>>
                        <label for="no_feat">No</label>
                    </td>
                    <td>
                        <input type = "radio" id="yes_home" name="user_option[<?=$user?>][homepage]" value = "1" <?php checked($users->get_user($user)['homepage'] ?? '', 1)?>>
                        <label for="yes_home">Yes</label>
                        <br>
                        <input type = "radio" id="no_home" name="user_option[<?=$user?>][homepage]" value = "0" <?php checked($users->get_user($user)['homepage'] ?? '', 0)?>>
                        <label for="no_home">No</label>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <button type="submit"> Save Changes </button>
    </form>
    <?php
        if (isset($_POST['user_option'])){
            //var_dump($_POST['user_option']);
            //die();
            $users->update_user_options($_POST['user_option']);
            new A8_Social_Feed_Errors('Options updated.', 'notice-success');
        }
    ?>
</div>