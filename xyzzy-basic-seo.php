<?php 
/*
  Plugin name: XYZZY Basic SEO & Analytics
  Plugin URI: https://xyzzyestudioweb.com/blog/basic-seo-analytics
  Description: XYZZY Basic SEO & Analytics es un sencillo y ligero plugin con el que integrar Analytics y los metadatos SEO en nuestra web
  Requires at least: 5.0
  Tested up to: 5.3
  Author: XYZZY estudio web
  Author URI: https://www.xyzzyestudioweb.com
  License: GPL v2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: xbs-lang
  Domain Path: /languages/
  Version: 1.0.4
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );

// Funciones para registro de estilos
require_once plugin_dir_path(__FILE__) . 'inc/functions/enqueue-styles.php';

// Funciones para el manejo de metadatos
require_once plugin_dir_path(__FILE__) . 'inc/functions/admin-meta.php';

// Funciones para insertar metadatos en head
require_once plugin_dir_path(__FILE__) . 'inc/functions/head-embed.php';

// Funciones para el menú de administración
require_once plugin_dir_path(__FILE__) . 'inc/functions/admin-menu.php';