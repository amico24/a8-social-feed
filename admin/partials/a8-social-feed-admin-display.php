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

<h2>Instructions for use:</h3>
<ol>
    <li>Input Meta App Client ID and Client Secret in the settings</li>
    <p>App should have instagram graph api product active (and maybe facebook login but im not super sure but maybe add it too)</p>
    <p>Your developer account needs to be be connected to a facebook page which is connected to a business/creator instagram account</p>
    <p>Make sure that your developer facebook account has task access on the facebook page (note: you cant get task access if you're the creator of the page for some reason)</p>
    <p>The way graph api works is that it connects to facebook, then uses the facebook connection to grab data from instagram, which is why the fb page is needed.</p>
    <li>Input short lived access token in settings.</li>
    <p>I got the access code from the Meta developer graph api explorer </p>
    <p>the ff permissions are needed for everthing to work:</p>
    <ul class="asf-default-page">
        <li>pages_show_list</li>
        <li>ads_management</li>
        <li>business_management</li>
        <li>instagram_basic</li>
        <li>instagram_manage_insights</li>
        <li>pages_read_engagement</li>
    </ul class="asf-default-page">
    <p>after inputting the short lived access token, the page will access the graph api with the client id and app secret and grab the logn lived token and ig user id</p>
    <p>if the long lived access token and ig user id updates in the settings page then youre good to go</p>
    <li>Go to category management and create categories to put the users in</li>
    <li>Go to user management and add creator/business instagram accounts (personal accounts dont work)</li>
    <p>to add an account, type the accounts username into the field (no @ or spaces)</p>
    <li>the account should show up in the table on the page. click the edit button under the username on the table to assign the account to categories or set them as featured or homepage accounts</li>
    <p>Users need to have at least one category assigned to it to be displayed in the shortcode</p>
    <li>use the shortcode</li>
</ol>
<h4>Shortcode Specifications:</h1>
<p> default shortcode: </p>
<input type="text" value = "[feed_display]"readonly>
<p> default shortcode with paramaters specified: </p>
<input type="text" size="100" value = '[feed_display type="posts" category="ALL" length="many" special="default" checkbox="false"]' readonly>
<p>shortcode parameters:</p>

<ul class="asf-default-page">
    <li><input type="text" value = "type"readonly></li>
    <p>Possible values:</p>
    <ul class="asf-default-page">
        <li>posts</li>
        <li>accounts</li>
    </ul class="asf-default-page">
    <p>Default value :posts</p>
    <li><input type="text" value = "category"readonly></li>
    <p>Possible values:</p>
    <ul class="asf-default-page">
        <li>categories specified in category management</li>
        <li>ALL</li>
    </ul class="asf-default-page">
    <p>Default value: ALL</p>
    <p>setting this to "ALL" only adds users with an assigned category</p>
    <li><input type="text" value = "length"readonly></li>
    <p>Possible values:</p>
    <ul class="asf-default-page">
        <li>{int values}</li>
        <li>many</li>
    </ul class="asf-default-page">
    <p>Default value: many</p>
    <p>"many" adds the load more button + just outputs every post loaded from the api</p>
    <li><input type="text" value = "special"readonly></li>
    <ul class="asf-default-page">
        <li>default</li>
        <li>featured</li>
        <li>homepage</li>
    </ul class="asf-default-page">
    <p>Default value: default</p>
    <p>"special" basically acts like a higher priority category, setting this to featured or homepage makes the shortcode ignore category parameter (if set)</p>
    <li><input type="text" value = "checkbox"readonly></li>
    <ul class="asf-default-page">
        <li>true</li>
        <li>false</li>
    </ul class="asf-default-page">
    <p>Default value: false</p>
    <p>adds a checklist of categories to the display if true</p>
</ul class="asf-default-page">

</div>