<?php

// Función para hacer set de los datos en el header
function xbs_set_metadata() { 

    global $wp;

    $id             = get_the_ID();
    $title          = xbs_get_the_title();
    $name           = get_bloginfo('name');
    $description    = xbs_get_description($id);
    $keywords       = xbs_get_keywords($id);
    $image          = xbs_get_post_img($id);
    $url            = home_url(add_query_arg(array($_GET), $wp->request));
    $analytics      = xbs_get_analytics();

    $html = xbs_delete_meta();
    
    $html .= '<!-- XYZZY Basic SEO meta tags -->';
    

    if (!current_theme_supports('title-tag') && !empty($title)):
        $html .= '<title>' . $title . '</title>';
    endif;

    if(!empty($title)):
        $html .= '<meta property="og:title" content="' . $title .'">';
    endif;

    if(!empty($description)):
        $html .= '<meta name="description" content="' . $description . '" />';
        $html .= '<meta property="og:description" content="' . $description . '">';
    endif;

    if(!empty($keywords)):
        $html .= '<meta name="keywords" content="' . $keywords . '" />';
        $html .= '<meta name="news_keywords" content="' . $keywords . '" />';
    endif;

    if(!empty($image)):
        $html .= '<meta property="og:image" content="' . $image . '">';
        $html .= '<meta name="twitter:card" content="summary_large_image">';
    endif;

    if(!empty($url)):
        $html .= '<meta property="og:url" content="' . $url . '">';
    endif;

    if(!empty($name)):
        $html .= '<meta property="og:site_name" content="' . $name . '">';
    endif;

    if(!empty($analytics)):
        $html .= $analytics;
    endif;

    $html .= '<!-- End XYZZY Basic SEO meta tags -->';

    echo $html;
} 
add_action( 'wp_head' ,'xbs_set_metadata');

// Función para eliminar las meta previas a insertar las nuestras (evita duplicados)
function xbs_delete_meta(){
    $script  = '<script type="text/javascript">';
    $script .= 'document.querySelectorAll("[property=\'og:title\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[name=\'description\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[property=\'og:description\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[name=\'keywords\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[name=\'news_keywords\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[property=\'og:image\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[name=\'twitter:card\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[property=\'og:url\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= 'document.querySelectorAll("[property=\'og:site_name\']").forEach(e => e.parentNode.removeChild(e));';
    $script .= '</script>';
    return $script;
 }

// Función que devuelve el título compuesto (sobreescribimos si el tema soporta title-tag)
function xbs_get_the_title() {

    $title = get_bloginfo('name');
    $subtitle = '';
    $content = '';

    if(!empty(single_post_title('', false))):
        $subtitle = single_post_title('', false);
    else:
        $subtitle = get_bloginfo('description');
    endif;

    if(!empty($subtitle)):
        $content = $title . ' | ' . $subtitle;
    else:
        $content = $title;
    endif;

    return $content;
}
add_filter( 'pre_get_document_title', 'xbs_get_the_title', 10 );

// Función para recoger la meta-descripción
function xbs_get_description($id){

    $description = '';

    if(!is_home() && !empty(get_post_meta( $id,'_xbs_meta_description_field', true))):
        $description = get_post_meta( $id,'_xbs_meta_description_field', true);
    elseif(!empty(get_option('xbs_base_description'))):
        $description = get_option('xbs_base_description');
    endif;

    return $description;
}

// Función para recoger las keywords
function xbs_get_keywords($id) {

    $keywords = '';

    if(!is_home() && !empty(get_post_meta( $id,'_xbs_meta_keywords_field', true))):
        $keywords = implode(',', get_post_meta( $id,'_xbs_meta_keywords_field', false));
    elseif(!empty(get_option('xbs_base_keywords'))):
        $keywords = get_option('xbs_base_keywords');
    endif;

    return $keywords;
}

// Función para recoger la url de la imagen
function xbs_get_post_img($id) {
    
    $image = '';

    if((is_home() || is_front_page()) && !empty(get_custom_logo())):
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        $image = $logo[0];
    elseif(!empty(get_the_post_thumbnail_url($id))):
        $image = get_the_post_thumbnail_url($id);
    elseif(!empty(get_custom_logo())):
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        $image = $logo[0];
    endif;

    return $image;
}

// Función que devuelve el código html de analytics
function xbs_get_analytics() {
    
    $script = '';
    $analytics = '';
    $options = get_option('xbs_analytics_code');
    
    if(!empty($options)):
        $analytics = $options;
        $script  = '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $analytics . '"></script>';
        $script .= '<script>window.dataLayer = window.dataLayer || [];';
        $script .= 'function gtag(){dataLayer.push(arguments);}';
        $script .= 'gtag(\'js\', new Date()); gtag(\'config\', \''. $analytics .'\'); </script>';
        return $script;
    else:
        return null;
    endif;
}