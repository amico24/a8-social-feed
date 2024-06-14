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

    <form action='options.php' method='post'>

        <?php
        settings_fields('ASF-category');
        do_settings_sections($this->plugin_name . '-categories');
        submit_button("Create Category");
        ?>

    </form>

    <?php 
        $catTable->display();
        $table_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'type' => 'categories'
        );
        wp_localize_script('table_quick_edit', 'table_data', $table_data);
        wp_enqueue_script('table_quick_edit');
    ?>

</div>