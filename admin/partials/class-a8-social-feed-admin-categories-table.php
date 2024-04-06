<?php

//apprently their tables are not an api but its a class u have to extend
//pain
class Category_Table extends WP_List_Table {
    private $table_data = array();

    function __construct(){
        parent::__construct( array(
            'singular'=> 'asf_category', //Singular label
            'plural' => 'asf_categories', //plural label, also this well be one of the table css class
            'ajax'   => true 
            ) );
    }

    function prepare_items(){
        //when u get the categories in from the options is gets passed as an indexed array
        //for the other functions to work normally we want the categories to be in a 2d array with the index and name in their own arrays as each entry
        $temp = get_option("asf_categories");
        //this restructures the categories so the nidex and name have their own entries
        //i mental-ed this shit i am so big brained for this
        foreach($temp as $category){
            array_push($this -> table_data, array(
                'index' => array_search($category, $temp)+1,
                'name' => $category)
            );
        }

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        usort($this->table_data, array(&$this, 'usort_reorder'));
        /*
        var_dump($_GET);
        die();
        */
        $this->items = $this->table_data;

    }
    //define column headers
    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'index' => '#',
            'name' => 'Name',
        );
        return $columns;
    }
    
    //defines what values to put for every data entry
    //column_[columnName] also works if i wanna separate the behavior into indiv functions
    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'index':
                return $item[ $column_name ];
            case 'name':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    //sets checkboxes for each row
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
            'delete' => sprintf('<a href="?page=%s&action=%s&element=%s"> Delete </a>', $_REQUEST['page'], 'delete-category', $item['name']),
        );
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }
}