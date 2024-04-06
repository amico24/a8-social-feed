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

use Category_Table;

$categories = A8_Social_Feed_Categories::getInstance();
$catTable = new Category_Table;

$catTable->prepare_items();
?>

<div class="wrap">

    <h1>Categories</h1>

    <p>Create New Category:</p>
    <form method="POST">
        <input type="text" id="new_cat_name" name="new_cat_name" placeholder="Category Name">
        <button type="submit">Create Category</button>
    </form>

    <?php if (isset($_POST['new_cat_name'])) {
        if (isset($_POST['new_cat_name'])) {
            $categories->create_category($_POST['new_cat_name']);
        }
    } ?>

    <?php $catTable->display(); ?>
    <!--
    <table>
        <tr>
            <th>Category Name</th>
            <th>Delete Category</th>
        </tr>
        <?php foreach ($categories->get_categories() as $category) : ?>
            <tr>
                <td>
                    <?= $category ?>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="delete_category" id="delete_acategory" value="<?= $category ?>" />
                        <button type="submit"> Delete </button>
                    </form>
                </td>
            </tr>
            <?php
            if (isset($_POST['delete_category'])) {
                /*
                var_dump($_POST);
                die();
                */
                //might wanna add a confirmation alert for this
                $categories->delete_category($_POST['delete_category']);
                unset($_POST['delete_category']);
            }
            ?>
        <?php endforeach ?>
    </table>
    -->
    <?php /*
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url_parts = parse_url($url);
        $redirect_url = $url_parts["scheme"] . "://" . $url_parts["host"] . $url_parts["path"] . "?page=a8-social-feed-categories";
        var_dump($redirect_url);
        die();*/
    ?>

</div>