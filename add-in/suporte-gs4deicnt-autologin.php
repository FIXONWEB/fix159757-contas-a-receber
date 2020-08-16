<?php 
// suporte-gs4deicnt-autologin.php
if ( ! defined( 'ABSPATH' ) ) { exit; }
register_activation_hook( $plugin_file, 'fixon_gs4deicnt' );
function fixon_gs4deicnt() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."fixon_000016` (

    `fixon_000016_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
    `fixon_000016_string` varchar(50),
    `fixon_000016_user_id` varchar(50),
    `fixon_000016_login` varchar(50),
    `fixon_000016_senha` varchar(50),

    PRIMARY KEY (`fixon_000016_codigo`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;  
  ";
  $wpdb->query($sql);

  $sql = "
  INSERT INTO `".$GLOBALS['wpdb']->prefix."fixon_000002` (

  `fixon_000002_tabela`, 
  `fixon_000002_sql_sort`, 
  `fixon_000002_sql_limit`, 
  `fixon_000002_sql_dir`, 
  `fixon_000002_ativo` 
  ) VALUES (

  'fixon_000016', 
  'fixon_000016_codigo', 
  20, 
  'DESC', 
  ''
  );
  ";
  $wpdb->query($sql);

  $sql = "
  CREATE TRIGGER `".$GLOBALS['wpdb']->prefix."fixon_000016_bi` BEFORE INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000016` FOR EACH ROW begin
      if new.fixon_000016_data is null then set new.fixon_000016_data  = (SELECT DATE(CURRENT_TIMESTAMP())); end if;
      if new.fixon_000016_hora is null then set new.fixon_000016_hora  = (SELECT TIME(CURRENT_TIMESTAMP())); end if;
  end;
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->query($sql);

  fixon_create_fields("fixon_000016");
  //$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000016_pessoa_descri';");
}


add_action( 'parse_request', 'fixon_gs4deicnt_parse_request');
function fixon_gs4deicnt_parse_request( &$wp ) {
	global $wpdb;
  // if ( !is_user_logged_in() ) return "";
  // if(current_user_can( 'administrator' )) return;
  
  // if($wp->request == 'i000016'){
  	// echo do_shortcode('[idados_busca]');
    // echo do_shortcode('[idados_000016]');
    // exit;
  // }
	$autologin = isset($_GET['altl']) ? $_GET['altl'] : '';
	if($autologin){
		$sql = "select fixon_000016_user_id, fixon_000016_login, fixon_000016_senha from ".$wpdb->prefix."fixon_000016 where fixon_000016_string = '".$autologin."' limit 0,1;";
		$rr = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
		$ret['rows'] = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
		if(count($rr)){
			$creds = array();
			// echo '<pre>';
			// print_r($rr);
			// echo '</pre>';

			$creds['user_login'] = $ret['rows'][0]['fixon_000016_login'];////'teste55';
			$creds['user_password'] = $ret['rows'][0]['fixon_000016_senha'];//'teste123A';
			$user = wp_signon( $creds, false );

			// echo '<pre>';
			// print_r($user);
			// echo '</pre>';

			$rq = $_REQUEST;
			if ( is_wp_error($user) ){
				// echo $user->get_error_message();
				$rqt=$_SERVER['REQUEST_URI'];
				$rqt = preg_replace('/altl/', 'altl-fail', $rqt);
				wp_redirect( home_url().$rqt ); exit;

				// echo '<!--;';
				// echo '<pre>';
				// echo home_url().$rqt;
				exit;
				// // unset($rq['quux']);
				// echo 'erro'."\n";
				// print_r($user);
				// echo '</pre>';
				
				// echo '-->';


				return '';

			}
			// echo '<!--BINGO-->';
			// wp_redirect( home_url() ); exit;
			$rqt=$_SERVER['REQUEST_URI'];
			$rqt = preg_replace('/altl/', 'autologado', $rqt);
			wp_redirect( home_url().$rqt ); exit;
			return '';

		}
		// echo $sql; 

		// $ret['r'] = count($rr);
		// if($ret['r']){
	}
}