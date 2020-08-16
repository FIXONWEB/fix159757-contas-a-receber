<?php
/**
 * Plugin Name:     Fixonweb - Ref.: 159757 - Contas a Receber
 * Plugin URI:      https://fixonweb.com.br/fix159757-contas-a-receber
 * Description:     Controle simples de recebiveis
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix159757-contas-a-receber
 * Domain Path:     /languages
 * Version:         0.1.2
 *
 * @package         Fix159757_Contas_A_Receber
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$url_plugin_update 	= 'https://github.com/FIXONWEB/fix159757-contas-a-receber';
$slug_update 		= 'fix159757-contas-a-receber/fix159757-contas-a-receber';
$myUpdateChecker 	= Puc_v4_Factory::buildUpdateChecker($url_plugin_update, __FILE__, $slug_update);
