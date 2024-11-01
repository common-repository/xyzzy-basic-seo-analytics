<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php settings_errors(); ?>
    <form action="options.php" method="post">
        <?php
        
        // output security fields for the registered setting "wporg_options"
        settings_fields( 'xbs-admin' );
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections( 'xbs-admin' );
        // output save settings button
        submit_button(__('Guardar'));
        ?>
      </form>
</div>