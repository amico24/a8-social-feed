<?php

namespace ASF\Admin;


$graph_api = A8_Social_Feed_Graph_API::getInstance();
$users = A8_Social_Feed_Users::getInstance();
$categories = A8_Social_Feed_Categories::getInstance();

?>



<div class="wrap">
    <h2>Settings</h2>

    <form action='options.php' method='post'>

        <?php
        settings_fields('ASF-settings');

        echo '<table class="form-table" role="presentation">';
		do_settings_fields( $this->plugin_name . '-settings', 'asf-api-details');
        do_settings_fields( $this->plugin_name . '-settings', 'asf-feed-settings');
		echo '</table>';

        submit_button();
        ?>

    </form>

    <form action='options.php' method='post'>

        <?php
        settings_fields('ASF-API-token');

        echo '<table class="form-table" role="presentation">';
		do_settings_fields( $this->plugin_name . '-settings', 'asf-api-access-token');
		echo '</table>';

        submit_button();
        ?>

    </form>


</div>