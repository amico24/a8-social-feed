<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://sample.com
 * @since      1.0.0
 *
 * @package    A8_Social_Feed
 * @subpackage A8_Social_Feed/admin/partials
 */

namespace ASF\Admin;
$users = A8_Social_Feed_Users::getInstance();
$categories = A8_Social_Feed_Categories::getInstance();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1>Accounts</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Delete Account</th>
        </tr>
        <?php foreach ($users->get_user_list() as $user): ?>
            <tr>
                <td>
                    <?= $user ?>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="delete_account" id="delete_account" value="<?= $user ?>" />
                        <button type="submit"> Delete </button>
                    </form>
                </td>
            </tr>
            <?php
            if (isset($_POST['delete_account'])) {
                //might wanna add a confirmation alert for this
                $users->delete_user($_POST['delete_account']);
                new A8_Social_Feed_Errors('Account Deleted.', 'notice-success');
                unset($_POST['delete_account']);
            }
            ?>
        <?php endforeach ?>
    </table>

</div>