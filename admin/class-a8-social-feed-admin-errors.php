<?php

namespace ASF\Admin;

//class for displaying error or success notices

class A8_Social_Feed_Errors{
	/**
	 * Message to be displayed in a warning.
	 *
	 * @var string
	 */
	private string $message;

     /**
      * Type of message: notice-error, notice-warning, notice-success, or notice-info
      * 
      * @var string
      */
    private string $error_class; 

	/**
	 * Initialize class.
	 *
	 * @param string $message Message to be displayed in a warning.
     * @param string $error_class Type of message: notice-error, notice-warning, notice-success, or notice-info
	 */
	public function __construct( string $message, $error_class) {
		$this->message = $message;
        $this -> error_class = $error_class;

		add_action( 'admin_notices', array( $this, 'render' ), );
		do_action('admin_notices');
	}

	/**
	 * Displays warning on the admin screen.
	 *
	 * @return void
	 */
	public function render() {
		printf( '<div class="notice %s is-dismissible"><p>%s</p></div>',esc_html($this -> error_class), esc_html( $this->message ) );
	}
}