<?php
/**
 * Plugin Name:     Fixonweb - Ref.: 159757 - Contas a Receber
 * Plugin URI:      https://fixonweb.com.br/fix159757-contas-a-receber
 * Description:     Controle simples de recebiveis
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix159757-contas-a-receber
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Fix159757_Contas_A_Receber
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/fixonweb/fix159757-contas-a-receber/fix159757-contas-a-receber.php',
    __FILE__, 
    'fix159757-contas-a-receber/fix159757-contas-a-receber'
);
