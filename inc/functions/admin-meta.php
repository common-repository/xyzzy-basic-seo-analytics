<?php 

// Función que registra los metadatos necesarios
function xbs_register_post_meta() {

    register_post_meta( '', '_xbs_meta_keywords_field', array(
        'show_in_rest' => true,
        'single' => false,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        }
    ) );

    register_post_meta( '', '_xbs_meta_description_field', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        }
    ) );

} 
add_action( 'init', 'xbs_register_post_meta' );

// Función para crear la caja donde se mostrarán los inputs
function xbs_seo_box(){

    $screens = get_post_types();

    foreach ($screens as $screen):
        add_meta_box(
            'xbs-seo-box',                      // ID
            __('Herramientas SEO'),             // Título
            'xbs_seo_box_html',                 // Callback
            $screen,                            // Pantallas en las que se muestra
            'side'                              // Zona en la que lo mostramos
        );
    endforeach;
} 
add_action('add_meta_boxes', 'xbs_seo_box');

// Función donde creamos los inputs para los metadatos
function xbs_seo_box_html($post) {

    $current_screen = get_current_screen();

    if( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {

        echo '<div id="xbs-keywords-component"></div><br/>';
        echo '<div id="xbs-description-component"></div>';

        wp_enqueue_script(
            'xbs-token-form', 
            plugins_url( '../../admin/js/token-form.js', __FILE__ ),
            array('wp-components', 'wp-data','wp-dom-ready', 'wp-i18n', 'wp-compose')
        );

    } else {

        // Implementación para editor clásico (sin Gutenberg)

        $keywords = '';
        $description = '';

        if(get_post_meta(get_the_ID(), '_xbs_meta_keywords_field')) {
            $keywords = implode(",",get_post_meta(get_the_ID(), '_xbs_meta_keywords_field'));
        }

        if(get_post_meta(get_the_ID(), '_xbs_meta_description_field')) {
            $description = get_post_meta(get_the_ID(), '_xbs_meta_description_field');
        }

        $keys_placeholder = __('Ej. palabra clave 1, palabra clave 2, palabra clave 3','xbs-lang');
        $desc_placeholder = __('Escribe aquí la meta descripción del post','xbs-lang');

        echo '<p class="post-attributes-label-wrapper">';
        echo '<label class="post-attributes-label" for="xbs_meta_keywords_ng">'. __('Palabras clave', 'xbs-lang').'</label></p>';
        echo '<textarea style="width:100%;" rows="4" name="xbs_meta_keywords_ng" placeholder="'. $keys_placeholder . '">'. $keywords .'</textarea>';
        echo '<p class="howto" style="margin-top:0px;">'. __('10 máximo. Separadas por comas.', 'xbs-lang').'</p>';
        echo '<p class="post-attributes-label-wrapper">';
        echo '<label class="post-attributes-label" for="xbs_meta_description_ng">'. __('Meta descripción', 'xbs-lang').'</label></p>';
        echo '<textarea style="width:100%;" rows="4" name="xbs_meta_description_ng" placeholder="'. $desc_placeholder . '">'. $description[0] .'</textarea>';
        echo '<p class="howto" style="margin-top:0px;">'. __('Introduce aquí la descripción SEO', 'xbs-lang').'</p>';
    }
}

// Implementación para editor clásico (sin Gutenberg)
function xbs_save_postdata($post_id)
{
    if (array_key_exists('xbs_meta_keywords_ng', $_POST)) {
        
        delete_post_meta($post_id, '_xbs_meta_keywords_field');
        
        if(!empty($_POST['xbs_meta_keywords_ng'])) {

            $keywords_array = explode(",", $_POST['xbs_meta_keywords_ng']);
            $total = 0;

            foreach($keywords_array as $keyword){
                
                if(!ctype_space($keyword)) {
                    add_post_meta(
                        $post_id,
                        '_xbs_meta_keywords_field',
                        strip_tags(stripslashes($keyword))
                    );
                }

                $total++;
                if($total >= 10) { break; }
            }
        }
    }

    if (array_key_exists('xbs_meta_description_ng', $_POST)) {
        update_post_meta(
            $post_id,
            '_xbs_meta_description_field',
            str_replace('"','\'',$_POST['xbs_meta_description_ng'])
        );
    }
}
add_action('save_post', 'xbs_save_postdata');

function xbs_set_script_translations() {
    wp_set_script_translations( 'xbs-token-form', 'xbs-lang' );
}
add_action( 'init', 'xbs_set_script_translations' );