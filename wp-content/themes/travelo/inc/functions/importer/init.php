<?php

/* Functions.php
 *
 * Travelo Importer
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Travelo_Importer {

    public $error    = array();
    
    function __construct() {
        add_action( 'admin_menu', array( &$this, 'init' ) );
    }

    function init() {
        add_theme_page(
            esc_html__( 'Travelo Import Demo Data', 'trav' ),
            esc_html__( 'Travelo Demo Data', 'trav' ),
            'edit_theme_options',
            'travelo_importer',
            array( &$this, 'importer' )
        );
        add_action( 'admin_init', array( &$this, 'increase_server_vars' ) );
    }

    function increase_server_vars() {
        if( key_exists( 'travelo_importer_nonce',$_POST ) ) {
            if ( wp_verify_nonce( $_POST['travelo_importer_nonce'], 'travelo_importer_notice_text' ) ) {
                if ( $_POST && key_exists('attachments', $_POST) && $_POST['attachments'] ) {
                    if ( !defined( 'WP_MEMORY_LIMIT' ) ) define('WP_MEMORY_LIMIT', '256M');
                    set_time_limit(0);
                }
            }
        }
    }

    function import_content( $file = 'all.xml' ){
        $importer = new WP_Import();
        $xml = TRAV_INC_DIR . '/functions/importer/demo_data/'. $file;
        $importer->fetch_attachments = true;

        ob_start();
        $importer->import( $xml ); 
        ob_end_clean();
    }

    function import_menu_location( $file = 'menu.txt' ){
        $file_path  = TRAV_TEMPLATE_DIRECTORY_URI . '/inc/functions/importer/demo_data/'. $file;
        $file_data  = wp_remote_get( $file_path );
        $data       = unserialize( $file_data['body'] );
        $menus      = wp_get_nav_menus();
        foreach( $data as $key => $val ){
            foreach( $menus as $menu ){
                if( $val && $menu->slug == $val ){
                    $data[$key] = absint( $menu->term_id );
                }
            }
        }
        set_theme_mod( 'nav_menu_locations', $data );
    }

    function travelo_importer_error_handler() {
        // ignore errors
    }

    function import_options( $file = 'options.json' ){
        $file_path     = TRAV_TEMPLATE_DIRECTORY_URI . '/inc/functions/importer/demo_data/'. $file;
        $file_data     = wp_remote_get( $file_path );

        $data         = array( 'import_code' => $file_data['body'] );

        set_error_handler(array($this, 'travelo_importer_error_handler'));
        $redux = ReduxFrameworkInstances::get_instance( 'travelo' );
        $redux->set_options( $redux->_validate_options( $data ) );
    }
    
    
    function import_widget( $file = 'widget_data.json' ){
        $file_path      = TRAV_TEMPLATE_DIRECTORY_URI . '/inc/functions/importer/demo_data/'. $file;
        $file_data      = wp_remote_get( $file_path );
        $data           = $file_data['body'];
        $this->import_widget_data( $data );
    }

    function importer(){

        $current_theme = wp_get_theme();
        global $wpdb;

        if( key_exists( 'travelo_importer_nonce',$_POST ) ){
            if ( wp_verify_nonce( $_POST['travelo_importer_nonce'], 'travelo_importer_notice_text' ) ){
                if( ! defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );
                
                if( ! class_exists( 'WP_Importer' ) ){
                    require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                }
                
                if( ! class_exists( 'WP_Import' ) ){
                    require_once TRAV_INC_DIR . '/lib/wordpress-importer/wordpress-importer.php';
                }
                
                if( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ){

                    if ( !class_exists( 'ReduxFrameworkInstances' ) ) {
                        $this->error[] = esc_html__('Please install required plugins before importing sample data.','trav');
                    } else {
                        switch( $_POST['content'] ) {
                            case 'all':
                                $file = 'travelo-sample-all.xml';
                                $this->import_content( $file );

                                $file = 'menu.txt';
                                $this->import_menu_location( $file );

                                $file = 'options.json';
                                $this->import_options( $file );

                                $file = 'widget_data.json';
                                $this->import_widget( $file );

                                // set home & blog page
                                $home = get_page_by_title( 'Home' );
                                $blog = get_page_by_title( 'Blog' );
                                if( $home->ID ) {
                                    update_option('show_on_front', 'page');
                                    update_option('page_on_front', $home->ID); // Front Page
                                }
                                if ( $blog->ID ) {
                                    update_option('page_for_posts', $blog->ID);
                                }
                                break;
                            case 'options':
                                $file = 'options.json';
                                $this->import_options( $file );
                                break;
                            case 'widgets':
                                $file = 'widget_data.json';
                                $this->import_widget( $file );
                                break;
                            default:
                                if ( !empty( $_POST['content'] ) ) {
                                    $file = esc_html( $_POST['content'] ) . '.xml';
                                    $this->import_content( $file );
                                }
                                break;
                        }
                    }

                    if( !empty( $this->error ) ){
                        echo '<div class="error settings-error">';
                            foreach( $this->error as $e ) {
                                echo '<p><strong>'. $e .'</strong></p>';
                            }
                        echo '</div>';
                    } else {
                        echo '<div class="updated settings-error">';
                            echo '<p><strong>'. esc_html__('All done. Have fun!','trav') .'</strong></p>';
                        echo '</div>';
                    }

                }
    
            }
        }

        ?>
        <div id="travelo-wrapper" class="travelo-importer wrap">
        
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            
            <p><strong><?php esc_html_e( 'Notice', 'trav' ); ?>:</strong></p>
            <p>
                <?php esc_html_e( 'Before starting the import, you need to install all required plugins.', 'trav' ); ?><br />
                <?php esc_html_e( 'Ex. If you are planning to use Shop, please remember to install Woocommerce plugin.', 'trav' ); ?>
            </p>
            <form action="<?php echo esc_url( remove_query_arg( 'step' ) ); ?> " method="post">
                
                <input type="hidden" name="travelo_importer_nonce" value="<?php echo wp_create_nonce( 'travelo_importer_notice_text' ); ?>" />

                <table class="form-table">

                    <tr class="row-content hide">
                        <th scope="row">
                            <label for="content">Content</label>
                        </th>
                        <td>
                            <select name="content">
                                <option value="all">-- All --</option>
                                <option value="travelo-accommodations">Accommodations</option>
                                <option value="travelo-contact_forms">Contact Forms</option>
                                <option value="travelo-pages">Pages</option>
                                <option value="travelo-posts">Posts</option>
                                <option value="travelo-room_types">Room Types</option>
                                <option value="travelo-taxonomies">Taxonomies</option>
                                <option value="travelo-things_to_do">Things To Do</option>
                                <option value="travelo-tours">Tours</option>
                                <option value="travelo-travel_guide">Travel Guide</option>
                                <option value="options">Theme Options</option>
                                <option value="widgets">Widgets</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Import demo data', 'trav'); ?>" />
            </form>
        </div>
<?php
    }

    function import_widget_data( $json_data ) {

        $json_data         = json_decode( $json_data, true );
        $sidebar_data     = $json_data[0];
        $widget_data     = $json_data[1];
        $widgets = array();
        foreach( $widget_data as $k_w => $widget_type ){
            if( $k_w ){
                $widgets[ $k_w ] = array();
                foreach( $widget_type as $k_wt => $widget ){
                    if( is_int( $k_wt ) ) $widgets[$k_w][$k_wt] = 1;
                }
            }
        }

        // sidebars
        foreach ( $sidebar_data as $title => $sidebar ) {
            $count = count( $sidebar );
            for ( $i = 0; $i < $count; $i++ ) {
                $widget = array( );
                $widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
                $widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
                if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
                    unset( $sidebar_data[$title][$i] );
                }
            }
            $sidebar_data[$title] = array_values( $sidebar_data[$title] );
        }

        // widgets
        foreach ( $widgets as $widget_title => $widget_value ) {
            foreach ( $widget_value as $widget_key => $widget_value ) {
                $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
            }
        }

        $sidebar_data = array( array_filter( $sidebar_data ), $widgets );
        $this->parse_importer_data( $sidebar_data );
    }

    function parse_importer_data( $importer_array ) {
        $sidebars_data         = $importer_array[0];
        $widget_data         = $importer_array[1];

        $redux = ReduxFrameworkInstances::get_instance( 'travelo' );
        $redux->get_options();
        $current_options = $redux->options;
        if ( !empty( $current_options ) ) {
            global $travelo_options;
            $travelo_options = $current_options;
        }
        if ( function_exists( 'travelo_widgets_init' ) ) {
            travelo_widgets_init();
        }

        $travelo_sidebars = array();
        foreach ( $sidebars_data as $importer_sidebar => $import_widgets ) {
            if ( strpos( $importer_sidebar, "trav-custom-sidebar-" ) !== false ) {
                $travelo_sidebars[str_replace( "trav-custom-sidebar-", "", $importer_sidebar )] = ucfirst( str_replace( "travelo-sidebar-", "", $importer_sidebar ) );
            }
        }
        if ( !empty( $travelo_sidebars ) ) {
            sidebar_generator::update_sidebars($travelo_sidebars);
        }

        $current_sidebars     = get_option( 'sidebars_widgets' );
        $new_widgets         = array( );

        foreach ( $sidebars_data as $importer_sidebar => $import_widgets ) :

            foreach ( $import_widgets as $import_widget ) :

                if ( ! isset( $current_sidebars[$importer_sidebar] ) ){
                    $current_sidebars[$importer_sidebar] = array();
                }

                $title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
                $index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
                $current_widget_data = get_option( 'widget_' . $title );
                $new_widget_name = $this->get_new_widget_name( $title, $index );
                $new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );
            
                if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
                    while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
                        $new_index++;
                    }
                }
                $current_sidebars[$importer_sidebar][] = $title . '-' . $new_index;
                if ( array_key_exists( $title, $new_widgets ) ) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];

                    if( ! key_exists('_multiwidget',$new_widgets[$title]) ) $new_widgets[$title]['_multiwidget'] = '';
                    
                    $multiwidget = $new_widgets[$title]['_multiwidget'];
                    unset( $new_widgets[$title]['_multiwidget'] );
                    $new_widgets[$title]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];

                    if( ! key_exists('_multiwidget',$current_widget_data) ) $current_widget_data['_multiwidget'] = '';

                    $current_multiwidget = $current_widget_data['_multiwidget'];
                    $new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
                    $multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
                    unset( $current_widget_data['_multiwidget'] );
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[$title] = $current_widget_data;
                }
                
            endforeach;
        endforeach;

        if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
            update_option( 'sidebars_widgets', $current_sidebars );

            foreach ( $new_widgets as $title => $content ) {
                update_option( 'widget_' . $title, $content );
            }
            return true;
        }
        return false;
    }

    function get_new_widget_name( $widget_name, $widget_index ) {
        $current_sidebars = get_option( 'sidebars_widgets' );
        $all_widget_array = array( );
        foreach ( $current_sidebars as $sidebar => $widgets ) {
            if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
                foreach ( $widgets as $widget ) {
                    $all_widget_array[] = $widget;
                }
            }
        }
        while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
            $widget_index++;
        }
        $new_widget_name = $widget_name . '-' . $widget_index;
        return $new_widget_name;
    }
}

$travelo_importer = new Travelo_Importer;