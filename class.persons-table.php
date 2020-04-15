<?php
if ( ! class_exists( "WP_List_Table" ) ) {
	require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
}

class Persons_Table extends WP_List_Table{

    var $_items;
    function __construct($args = array()){
        parent::__construct($args);
    }

    /**
     * Data set Function 
     * And Data store in items 
     */
  
    function set_data( $data ){
        $this->_items = $data;
    }

    /**
     * Cloumns Create 
     * array return
     */
    function get_columns(){
        $columns = array(
          'cb'      => '<input type="checkbox">',
          'name'     => 'Name',
          'email'    => 'Email',
          'age'      => 'Age',
          'sex'      => 'Sex'
        );
        return $columns;
      }
      /**
       * Age column sortable 
       */
      function get_sortable_columns(){
        return [
          'age'=>['age',true],
          'name'=>['name',true],
          ];
      }


      /**
       * id column modify
       */
      function column_cb($item){
        return '<input type="checkbox" value="'.$item['id'].'">';
      }

      /**
       * Email column modify 
       */
      function column_email($item){
        return "<strong>{$item['email']}</strong>";
      }

      /**
       * Email column modify 
       */
      function column_age($item){
        return "<em>{$item['age']}</em>";
      }

      /**
       * Filter Add 
       */
      function extra_tablenav($which){
        if('top' == $which):
        ?>
          <div class="actions alignleft">
              <select name="filters" id="filters">
                <option value="all">All</option>
                <option value="M">Males</option>
                <option value="F">Females</option>
              </select>
              <?php 
              submit_button(__('Filetr','customtable'),'button','submit',false);
              ?>
          </div>
        <?php
        endif;
      }




      /**
       * Prepare Function Excute here 
       * _column_hears include here 
       * enter all paramter 
       * clumns, hidden field, soratable
       */
      function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $paged = $_REQUEST['paged']?? 1;
        $per_page = 2;
        $total_items = count($this->_items);
        $data_chunk = array_chunk($this->_items, $per_page);
        $this->items = $data_chunk[$paged-1];
    
        $this->set_pagination_args(array(

          'total_items' =>$total_items,
          'per_page'=> $per_page,
          'total_pages'=> ceil(count($this->_items)/$per_page),

        ));

       // $this->items = $this->data;;
      }

      /**
       * Column Defalt value 
       */
      function column_default( $item, $column_name ) {
        switch( $column_name ) { 
          case 'name':
          case 'email':
          case 'age':
          case 'sex':
            return $item[ $column_name ];
          default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
      }

   





}