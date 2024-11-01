<?php

// Añadimos una nueva opción de menú
function xbs_add_admin_link()
{
      add_menu_page(
        __('XYZZY Basic SEO & Analytics', 'xbs-lang'), // Titulo de la página
        __('SEO & Analytics','xbs-lang'), // Texto que muestra el menú
        'manage_options', // Permiso necesario para ver el link
        'xyzzy-basic-seo-analytics/inc/views/xbs-admin.php',// Slug del menú //'xyzzy-basic-seo-analytics/inc/views/admin.php', // Vista que se despliega cuando se abre el link
        '',
        'dashicons-megaphone', // Icono
        20
    );

    add_submenu_page(
        'xyzzy-basic-seo-analytics/inc/views/xbs-admin.php',
        __('XYZZY Basic SEO & Analytics', 'xbs-lang'),
        __('Ajustes','xbs-lang'),
        'manage_options',
        'xyzzy-basic-seo-analytics/inc/views/xbs-admin.php',
        null
    );

    add_submenu_page(
        'xyzzy-basic-seo-analytics/inc/views/xbs-admin.php',
        __('Acerca de','xbs-lang'),
        __('Acerca de','xbs-lang'),
        'manage_options',
        'xyzzy-basic-seo-analytics/inc/views/xbs-instructions.php',
        null
    );
}
add_action( 'admin_menu', 'xbs_add_admin_link' );

// Registramos las opciones y los campos para el formulario
function xbs_settings_init() {

    register_setting('xbs-admin', 'xbs_analytics_code','xbs_settings_validate_input');
    register_setting('xbs-admin', 'xbs_base_keywords','xbs_keywords_validate_input');
    register_setting('xbs-admin', 'xbs_base_description','xbs_settings_validate_input');

    add_settings_section(
        'xbs_settings_section',
        __('Ajustes','xbs-lang'),
        'xbs_settings_section_cb',
        'xbs-admin'
    );

    add_settings_field(
        'xbs_analytics_code_field',
        __('Código Analitycs','xbs-lang'),
        'xbs_analytics_field_cb',
        'xbs-admin',
        'xbs_settings_section'
    );

    add_settings_field(
        'xbs_base_keywords_field',
        __('Palabras clave base','xbs-lang'),
        'xbs_keywords_field_cb',
        'xbs-admin',
        'xbs_settings_section'
    );

    add_settings_field(
        'xbs_base_description_field',
        __('Meta descripción base','xbs-lang'),
        'xbs_description_field_cb',
        'xbs-admin',
        'xbs_settings_section'
    );

}
add_action('admin_init', 'xbs_settings_init');

function xbs_settings_section_cb() {
    echo '<p>' . __('Puedes ajustar las propiedades generales desde aquí','xbs-lang') . '</p>';
}

function xbs_analytics_field_cb() {
    $options = get_option('xbs_analytics_code');
    $placeholder = __('Ej. UA-123456789','xbs-lang');
    echo '<input type="text" name="xbs_analytics_code" placeholder="'. $placeholder . '" value="' . $options . '">';
}

function xbs_keywords_field_cb() {
    $options = get_option('xbs_base_keywords');
    $placeholder = __('Ej. palabra clave 1, palabra clave 2, palabra clave 3','xbs-lang');
    echo '<textarea cols="70" rows="4" name="xbs_base_keywords" placeholder="'. $placeholder . '">'. $options .'</textarea>';
}

function xbs_description_field_cb(){
    $options = get_option('xbs_base_description');
    $placeholder = __('Escribe aquí la descripción base del sitio','xbs-lang');
    echo '<textarea cols="70" rows="4" name="xbs_base_description" placeholder="'. $placeholder . '">'. $options .'</textarea>';
}

function xbs_keywords_validate_input($input) {
    $old = get_option('xbs_base_keywords');
    
    if(isset($input)){
        $keywords_array = explode(",", $input);
        
        if(count($keywords_array) > 10) {
            add_settings_error(
                'keywords_ko', 
                'keywords_ko', 
                __( 'Error: utiliza un máximo de 10 palabras clave separadas por comas', 'xbs-lang' ), 
                'error'
            );
            return $old;
        }
    }
    return strip_tags(stripslashes($input));
}


function xbs_settings_validate_input($input) {
    return strip_tags(stripslashes($input));
}

