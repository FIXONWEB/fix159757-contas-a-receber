<?php
/**
 * Plugin Name:     Fixonweb - Ref.: 159757 - Contas a Receber
 * Plugin URI:      https://fixonweb.com.br/fix159757-contas-a-receber
 * Description:     Controle simples de recebiveis
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix159757-contas-a-receber
 * Domain Path:     /languages
 * Version:         0.1.5
 *
 * @package         Fix159757_Contas_A_Receber
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$url_plugin_update 	= 'https://github.com/FIXONWEB/fix159757-contas-a-receber';
$slug_update 		= 'fix159757-contas-a-receber/fix159757-contas-a-receber';
$myUpdateChecker 	= Puc_v4_Factory::buildUpdateChecker($url_plugin_update, __FILE__, $slug_update);

function fix159757_load_modules($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
    $arrayItems = array();
    $skipByExclude = false;
    $handle = opendir($directory);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
        preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
        if($exclude){
            preg_match($exclude, $file, $skipByExclude);
        }
        if (!$skip && !$skipByExclude) {
            if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                if($recursive) {
                    $arrayItems = array_merge($arrayItems, fix159757_load_modules($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                }
                if($listDirs){
                    $file = $directory . DIRECTORY_SEPARATOR . $file;
                    $arrayItems[] = $file;
                }
            } else {
                if($listFiles){
                    $file = $directory . DIRECTORY_SEPARATOR . $file;
                    $arrayItems[] = $file;
                }
            }
        }
    }
    closedir($handle);
    }
    return $arrayItems;
}

$plugin_file = __FILE__;


$path_modules = plugin_dir_path( __FILE__ )."add-in";
function fixon_ss_activate_au(){
    $current_version = '1.0.0';
    $remote_path = 'https://update.fixon.biz/';
    $slug = plugin_basename(__FILE__);
    $key = "j08uy8j74UFbKwRu";
    new fixon_suporte_update ($current_version, $remote_path, $slug, $key);
}

$dire = fix159757_load_modules($path_modules);
sort($dire);
foreach ($dire as $key => $value) {
	$extensao = substr($value, -4) ;
	if($extensao=='.php') require_once($value);;
}
