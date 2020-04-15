<?php
/*
Plugin Name: Custom Table Plugin
Plugin URI: https://onlytarikul.com
Description: Our First Custom Plugin
Version: 1.0
Author: Tarikul Islam
Author URI: https://onlytarikul.com
License: GPLv2 or later
Text Domain: shortcode
Domain Path: /languages/
*/
require_once "class.persons-table.php";

function custometable_plugins_loaded() {
    load_plugin_textdomain( 'posts-to-qrcode', false, dirname( __FILE__ ) . "/languages" );
}
add_action('plugins_loaded','custometable_plugins_loaded');
/*function wordcount_activation_hook(){}
register_activation_hook(__FILE__,"wordcount_activation_hook");

function wordcount_deactivation_hook(){}
register_deactivation_hook(__FILE__,"wordcount_deactivation_hook");*/

/**
 * Admin Page 
 */
function customtable_admin_menu(){
    add_menu_page(__("Data Table",'customtable'),
    __("My Data Table",'customtable'),
    'manage_options',
    'datable',
    'datatable_displaty_table'
);
}
add_action('admin_menu','customtable_admin_menu');

function datatable_search_by_name($item){
    print_r($item['name']);
    $name = strtolower($item['name']);
    $search_name = sanitize_text_field($_REQUEST['s']);
    if(strpos($name, $search_name) !== false){
        return true;
    }
    return false;
}

function datatable_filter_search($item){
    $sex = $_REQUEST['filters']??'all';
    if('all' == $sex){
        return true;
    }elseif( $sex == $item['sex']){
        return true;
    }
    return false;
}


function datatable_displaty_table(){
     include_once "dataset.php";
     $myListTable = new Persons_Table();
    $orderby = $_REQUEST['orderby']?? '';
    $order = $_REQUEST['order']?? '';
    if('age' == $orderby){
        if('asc' == $order){
            usort($data,function($item1,$item2){
                return $item2['age'] <=> $item1['age'];
            });
        }else{
            usort($data,function($item1,$item2){
                return $item1['age'] <=> $item2['age'];
            });
        }
    }elseif('name'== $orderby){
        if('asc' == $order){
            usort($data,function($item1,$item2){
                return $item2['name'] <=> $item1['name'];
            });
        }else{
            usort($data,function($item1,$item2){
                return $item1['name'] <=> $item2['name'];
            });
        }
    }

    if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])){
        $data = array_filter($data,'datatable_search_by_name');
    }

    if(isset($_REQUEST['filters']) && !empty($_REQUEST['filters'])){
        $data = array_filter($data,'datatable_filter_search');
    }


     $myListTable->set_data($data); 
     $myListTable->prepare_items();
     ?>
        <div class="wrap">
            <h2>My List Table Test</h2>
            <form action="" method="GET">
            <?php 
            $myListTable->search_box('search','search_id'); 
            $myListTable->display(); 
            ?>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>">
            </form>
        </div>
     <?php 
     
}


























/**
 * Admin Enqueue Script Here 
 */
function customtable_pqrc_assets( $screen ) {
    if ( 'options-general.php' == $screen ) {
        wp_enqueue_style( 'demo-main-css', plugin_dir_url( __FILE__ ) . "/assets/css/main.css" );
        wp_enqueue_script( 'demo-main-js', plugin_dir_url( __FILE__ ) . "/assets/js/main.js", array( 'jquery' ), time(), true );
    }
}
add_action( 'admin_enqueue_scripts', 'customtable_pqrc_assets' );

