<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sample.com
 * @since      1.0.0
 *
 * @package    A8_Social_Feed
 * @subpackage A8_Social_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    A8_Social_Feed
 * @subpackage A8_Social_Feed/admin
 * @author     Cyrus Kael Abiera <ckabieraprof@gmail.com>
 */

namespace ASF\Admin;

class A8_Social_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $shortcode_args = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
		add_action('init', array($this, 'init_admin_classes'));
		add_action('init', array($this, 'edit_options'));
		add_shortcode('feed_display', array($this, 'feed_display'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('wp_ajax_insta_api', array($this, 'handle_api_data'));
		add_action('wp_ajax_nopriv_insta_api', array($this, 'handle_api_data'));
		add_action('wp_ajax_insta_api_more', array($this, 'handle_more_api_data'));
		add_action('wp_ajax_nopriv_insta_api_more', array($this, 'handle_more_api_data'));
	}
	
	//have to put the deletion for categories here bc the redirect has to be done on initialization
	public function edit_options(){
		$categories = A8_Social_Feed_Categories::getInstance();
		$accounts = A8_Social_Feed_Users::getInstance();
		if(isset($_GET['action'])){
			switch ($_GET['action']){
				case 'delete-category':
					if(isset(($_GET['element']))){
						$categories->delete_category($_GET['element']);
					}
					break;
				case 'edit-category':
					if(isset(($_GET['element']))){
						//to be done
					}
					break;
				case 'delete-account':
					if(isset(($_GET['element']))){
						$accounts->delete_user($_GET['element']);
					}
					break;
				case 'edit-account':
					if(isset(($_GET['element']))){
						//to be done
					}
					break;
			}
			/*
			if($_GET['action'] == 'delete-category'){
				if(isset(($_GET['element']))){
					$categories->delete_category($_GET['element']);
				}
			}
			if($_GET['action'] == 'edit-category'){
				if(isset(($_GET['element']))){
					//to be done
				}
			}*/
			$url = esc_url(remove_query_arg(array('action','element'), false));
			/*
			var_dump($url);
			die();
			*/
			wp_safe_redirect ($url);
			exit;
		}
	}

	public function edit_account(){
		$accounts = A8_Social_Feed_Users::getInstance();
		if(isset($_GET['action'])){
			if($_GET['action'] == 'delete-account'){
				if(isset(($_GET['element']))){
					$accounts->delete_user($_GET['element']);
				}
			}
			if($_GET['action'] == 'edit-account'){
				if(isset(($_GET['element']))){
					//to be done
				}
			}
			$url = esc_url(remove_query_arg(array('action','element'), false));
			/*
			var_dump($url);
			die();
			*/
			wp_safe_redirect ($url);
			exit;
		}
	}

	public function handle_more_api_data(){
		$raw_data=wp_unslash($_POST); //cannot tell if i need to use json_decode here 
		//if things break in the next parts maybe put it
		$api_data=json_decode($raw_data['api_data'],1);
		$a = $raw_data['atts'];
		$shortcode_identifier = $raw_data['identifier'];
		//only get posts without checkbox or button
		$feed_display = (new A8_Social_Feed_Admin_Feed_Display($a, $api_data, $shortcode_identifier)) -> generate_feed_display();
		wp_send_json_success($feed_display);
	}

	public function handle_api_data(){
		$raw_data=wp_unslash($_POST); //cannot tell if i need to use json_decode here 
		//if things break in the next parts maybe put it
		$api_data=json_decode($raw_data['api_data'],1);
		$a = $raw_data['atts'];
		$shortcode_identifier = $raw_data['identifier'];
		$feed_display = (new A8_Social_Feed_Admin_Feed_Display($a, $api_data, $shortcode_identifier)) -> generate_full_display();
		$_POST = array(); //idk if i need this honestly but its not like im breaking anything by putting this here
		wp_send_json_success($feed_display);
	}


	public function init_admin_classes(){
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-errors.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-graph-api.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-users.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-categories.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-feed-display.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-entry-display.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-vendor.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin\class-a8-social-feed-admin-post.php';
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/a8-social-feed-admin.css');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('jquery');
		//wp_enqueue_script('category-toggle', plugin_dir_url(__FILE__) . 'js/a8-social-feed-admin-category-toggle.js', array('jquery') , null);
		//wp_register_script('graphAPI', plugin_dir_url(__FILE__) . 'js/a8-social-feed-admin-graph-api.js', array('jquery'), null);
	}

	public function feed_display($atts, $content = ""){
		$a = shortcode_atts(
			array(
				'type' => 'posts', // posts | accounts | stories
				'category' => 'ALL', //ALL is default if no category is specified
				'length' => 'many', //int | many
				'special' => 'default', // default | featured | homepage
				'checkbox' => false
			),
			$atts
		);
		//identifier class for shortcode html element: differentiates shortcodes from one another
		$identifier = 'shortcode-'.$a['type'].'-'.$a['category'].'-'.$a['length'].'-'.$a['special'];
		$identifier = str_replace(' ', '', $identifier); //remove spaces since im gonna use this as a class name
		$identifier = str_replace(',', '', $identifier);
		$api = A8_Social_Feed_Graph_API::getInstance();
		
		$users = A8_Social_Feed_Users::getInstance();

		$accounts_to_display = array();
		switch($a['special']){
            case 'featured':
                $accounts_to_display = $users -> get_featured_users();
                break;
            case 'homepage':
                $accounts_to_display = $users -> get_homepage_users();
                break;
            case 'default':
                $accounts_to_display = $users -> get_users_in_category($a['category']);
                break;
            default:
                $accounts_to_display = null;
        }
		/*
		$categories = array();
		foreach((A8_Social_Feed_Categories::getInstance())->get_categories() as $category){
			$categories[$category] = $users -> get_users_in_category($category);
		}*/

		$this -> shortcode_args[] = array(
			'access_token' => $api -> get_access_token(),
			'ig_user_id' => $api -> get_ig_id(),
			'ig_client_id' => $api -> get_client_id(),
			'user_list' => $accounts_to_display,
			'shortcode_atts' => $a,
			'shortcode_identifier' => $identifier,
			//'categories' => $categories,
			'ajax_url' => admin_url('admin-ajax.php')
		);

		

		wp_enqueue_script('graphAPI', plugin_dir_url(__FILE__) . 'js/a8-social-feed-admin-graph-api.js', array('jquery') , null);
		wp_localize_script('graphAPI', 'php_data', $this -> shortcode_args); //refer to this for fixing the multiple shortcode problem: https://wordpress.stackexchange.com/questions/204765/enqueue-script-multiple-times
			/*
		if($a['length']=="many"){
			wp_enqueue_script('load-more', plugin_dir_url(__FILE__) . 'js/a8-social-feed-admin-load-more.js', array('jquery') , null);
		}*/
		
		if(filter_var($a['checkbox'],FILTER_VALIDATE_BOOLEAN)==true){
			$categories = array();
			foreach((A8_Social_Feed_Categories::getInstance())->get_categories() as $category){
				$categories[$category] = $users -> get_users_in_category($category);
			}
			wp_enqueue_script('category-toggle', plugin_dir_url(__FILE__) . 'js/a8-social-feed-admin-category-toggle.js', array('jquery') , null);
			wp_localize_script('category-toggle','categories', $categories);
		}

		if(empty($accounts_to_display)){
			return '<div class="shortcode '.$identifier.'"><p> No users in category </p></div>';
		} else{
			return '<div class="shortcode '.$identifier.'"><p class = "placeholder '.$identifier.'"> Loading Plugin... </p><div class="feed '.$identifier.'"></div></div>';
		}
	}

	/**
	 * Everything below displays the menus and submenus for the plugin settings
	 */
	public function add_plugin_admin_menu()
	{
		//adds the option for the settings to the sidebar
		add_menu_page($this->plugin_name, 'A8 Social Feed', 'administrator', $this->plugin_name, array($this, 'display_plugin_admin_dashboard'), 'dashicons-camera', 26);

		//adds a page to display when you click the sidebar button
		add_submenu_page($this->plugin_name, 'A8 Social Feed Settings', 'Settings', 'administrator', $this->plugin_name . '-settings', array($this, 'display_plugin_admin_settings'));

		add_submenu_page($this->plugin_name, 'A8 Social Feed Profiles', 'Profiles', 'administrator', $this->plugin_name . '-profiles', array($this, 'display_plugin_admin_users'));
		add_submenu_page($this->plugin_name, 'A8 Social Feed Categories', 'Categories', 'administrator', $this->plugin_name . '-categories', array($this, 'display_plugin_admin_categories'));
	}
	public function display_plugin_admin_dashboard()
	{
		//connects the display file for the dashboard and displays the html there
		require_once 'partials/' . $this->plugin_name . '-admin-display.php';
	}

	public function display_plugin_admin_settings()
	{
		//connects to the display file for settings page
		require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
	}

	public function display_plugin_admin_users(){
		require_once 'partials/class-' . $this->plugin_name . '-admin-accounts-table.php';
		require_once 'partials/' . $this->plugin_name . '-admin-users-display.php';
	}

	public function display_plugin_admin_categories(){
		require_once 'partials/class-' . $this->plugin_name . '-admin-categories-table.php';
		require_once 'partials/' . $this->plugin_name . '-admin-categories-display.php';
	}

}
