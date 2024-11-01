<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$options = ['xbs_analytics_code','xbs_base_keywords','xbs_base_description'];
$post_meta = ['_xbs_meta_description_field','_xbs_meta_keywords_field'];

foreach($options as $option) {
    delete_option($option);
    delete_site_option($option);
}

foreach($post_meta as $meta) {
    delete_post_meta_by_key($meta);
}