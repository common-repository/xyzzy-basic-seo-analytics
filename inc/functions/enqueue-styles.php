<?php

function xbs_admin_styles() {
    wp_enqueue_style( 'xbs-admin-styles', plugins_url('../../admin/css/xbs-admin-styles.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'xbs_admin_styles');