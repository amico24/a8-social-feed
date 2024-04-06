<?php

class Account_Table extends WP_List_Table {
    private $table_data = array();

    function __construct(){
        parent::__construct( array(
            'singular'=> 'asf_category', //Singular label
            'plural' => 'asf_categories', //plural label, also this well be one of the table css class
            'ajax'   => true
            ) );
    }

    function prepare_items(){
        $users = ASF\Admin\A8_Social_Feed_Users::getInstance();
        $categories = ASF\Admin\A8_Social_Feed_Categories::getInstance();


        foreach ($users->get_user_list() as $user) {
            $category_checklist = '';
            foreach($categories->get_categories() as $category){
                $category_checklist .= '
                <input type = "checkbox" id= "' . $category .'" '. checked(in_array($category, $users->get_user($user)["category"]), true, false) . disabled(true, true, false) .'>
                <label for="'.$category.'">'.$category.'</label>
                <br>';
            } //i feel like im supposed to use sprintf for this
            array_push($this -> table_data, array(
                'index' => array_search($user, $users->get_user_list())+1,
                'name' => $user,
                'categories' => $category_checklist
                )
            );
        }


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        usort($this->table_data, array(&$this, 'usort_reorder'));

        $this->items = $this->table_data;
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'index' => '#',
            'name' => 'Name',
            'categories' => 'Categories',

        );
        return $columns;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'index':
                return $item[ $column_name ];
            case 'name':
                return $item[ $column_name ];
            case 'categories':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="element[]" value="%s" />',
            $item['index']
        );
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'index' => array('index', false),
            'name' => array('name', false),
        );
        return $sortable_columns;
    }

    function usort_reorder($a, $b){
        //the values arent showing up in $_GET
        /*
        var_dump($_GET);
        die();
        */
        // If no sort, default to index
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'index';

        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    function column_name($item){
        $actions = array(
            'edit' => sprintf('<a class="asf-quick-edit"> Edit </a>'),
            'delete' => sprintf('<a href="?page=%s&action=%s&element=%s"> Delete </a>', $_REQUEST['page'], 'delete-account', $item['name']),
        );
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions)); 
    }
}