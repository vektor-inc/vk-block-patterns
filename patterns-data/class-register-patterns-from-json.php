<?php
namespace wp_content\plugins\vk_block_patterns\patterns_data;

 class VK_RegisterPatternsFromJson {

    public function __construct(){
        add_action( 'init', array( __CLASS__, 'register_template' ) );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'print_pattern_css' ) );
        add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'print_pattern_editor_css' ) );
    }

    public static function load_inline_css(){
        $style_path = dirname( __FILE__ ) . '/style.css';
        $style_url = str_replace( ABSPATH, site_url() . '/', dirname( __FILE__ ) ) . '/style.css';
        $dynamic_css = '';
        if ( file_exists( $style_path ) ){

            $dynamic_css = file_get_contents( $style_path );
            // delete before after space
            $dynamic_css = trim( $dynamic_css );
            // convert tab and br to space
            $dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
            // Change multiple spaces to single space
            $dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );
        }
        return $dynamic_css;
    }

    public static function print_pattern_css(){
        $css = self::load_inline_css();
        if ( $css ){
            wp_add_inline_style( 'wp-block-library',  $css );
        }
    }

    public static function print_pattern_editor_css(){
        $css = self::load_inline_css();
        if ( $css ){
            wp_add_inline_style( 'wp-edit-blocks',  $css );
        }
    }

    public static function register_template(){

        // これは読み込み側では存在しないクラスなので要対応
        $json_dir_path = dirname( __FILE__ ) . '/';

        if ( function_exists( 'register_block_pattern_category' ) && function_exists( 'register_block_pattern' ) ) {

            // import category 
            /* ------------------------------*/
            $category_json = $json_dir_path . 'category.json';

            if ( file_exists( $category_json ) ) {
                $json = file_get_contents( $category_json );
                $json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                $obj = json_decode( $json, true );
                foreach( $obj as $key => $val) {
                    // Block Category.
                    register_block_pattern_category(
                        $val['slug'],
                        array( 'label' => esc_html( $val['name'] ) )
                    );
                }
            }

            /* import posts 
            /* ------------------------------*/
            if ( is_plugin_active( 'vk-blocks/vk-blocks.php' ) ) {
                $filename = 'template-for-vk-free.json';
            } else if ( is_plugin_active( 'vk-blocks-pro/vk-blocks.php' ) ) {
                $filename = 'template-for-vk-pro.json';
            } else {
                $filename = 'template-exclude-vk.json';
            } 

            $jsonUrl = $json_dir_path . $filename;

            if ( file_exists( $jsonUrl ) ) {
                $json = file_get_contents( $jsonUrl );
                $json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                $obj = json_decode( $json, true );

                $image_dir_path = $json_dir_path . 'images/';
                $image_dir_uri = str_replace( ABSPATH, site_url() . '/', $image_dir_path );

                foreach( $obj as $key => $val) {

                    // 本文欄の /// エスケープを戻す
                    $val = wp_unslash( $val );

                    $val['content'] = str_replace( '[pattern_directory]', $image_dir_uri , $val['content'] );

                    register_block_pattern(
                        $val['post_name'],
                        array(
                            'title'      => $val['title'],
                            'categories' => $val['categories'],
                            'content'    => $val['content'],
                        )
                    );

                }
            } else {
                echo "データがありません";
            }	
        }
    }
 }

 new VK_RegisterPatternsFromJson;