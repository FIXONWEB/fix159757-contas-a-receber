<?php
/*
CAIXA
CODIGO DE SUPORTE: gn5h7vpaxu
*/ 
if ( ! defined( 'ABSPATH' ) ) { exit; }

register_activation_hook( $plugin_file, 'fixon_000590a' );
function fixon_000590a() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."fixon_000590` (
    `fixon_000590_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
    `fixon_000590_data` date,
    `fixon_000590_hora` varchar(10),
    `fixon_000590_caixa` varchar(60),
    `fixon_000590_saldo` float(7,2) DEFAULT 0,

    PRIMARY KEY (`fixon_000590_codigo`)
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

  'fixon_000590', 
  'fixon_000590_codigo', 
  20, 
  'DESC', 
  ''
  );
  ";
  $wpdb->query($sql);

  $sql = "
  CREATE TRIGGER `".$GLOBALS['wpdb']->prefix."fixon_000590_bi` BEFORE INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000590` FOR EACH ROW begin
      if new.fixon_000590_data is null then set new.fixon_000590_data  = (SELECT DATE(CURRENT_TIMESTAMP())); end if;
      if new.fixon_000590_hora = '' then set new.fixon_000590_hora  = (SELECT CURRENT_TIME()); end if;
      #if new.fixon_000590_saldo is null then set fixon_000590_saldo = 0; end if;
      #if new.fixon_000590_saldo = '' then set fixon_000590_saldo = 0; end if;
  end;
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->query($sql);

	// fixon_create_fields("fixon_000590");
  $sql = "
  INSERT INTO `".$GLOBALS['wpdb']->prefix."fixon_000001` (`fixon_000001_codigo`, `fixon_000001_tabela`, `fixon_000001_campo`, `fixon_000001_value`, `fixon_000001_label`, `fixon_000001_hidelabel`, `fixon_000001_show`, `fixon_000001_ordem`, `fixon_000001_ctr_new`, `fixon_000001_ctr_edit`, `fixon_000001_ctr_view`, `fixon_000001_ctr_list`, `fixon_000001_ctr_loc`, `fixon_000001_ctr_lst`, `fixon_000001_ctr_vitrine`, `fixon_000001_dm`, `fixon_000001_tipo`, `fixon_000001_height`, `fixon_000001_largura`, `fixon_000001_altura`, `fixon_000001_tamanho`, `fixon_000001_align`, `fixon_000001_hidden`, `fixon_000001_black`, `fixon_000001_cls`, `fixon_000001_style`, `fixon_000001_cls_cp`, `fixon_000001_cls_view`, `fixon_000001_cls_vitrine`, `fixon_000001_clslabel`, `fixon_000001_ctcls`, `fixon_000001_itemcls`, `fixon_000001_formato`, `fixon_000001_renderer`, `fixon_000001_cmb_tp`, `fixon_000001_cmb_source`, `fixon_000001_cmb_codigo`, `fixon_000001_cmb_descri`, `fixon_000001_access_pub`, `fixon_000001_access_usr`, `fixon_000001_access_adm`, `fixon_000001_access_root`, `fixon_000001_url`, `fixon_000001_url_md`, `fixon_000001_url_op`, `fixon_000001_param`, `fixon_000001_modo`, `fixon_000001_cp_url`, `fixon_000001_ativo`, `fixon_000001_qtd_gr`, `fixon_000001_somar`, `fixon_000001_qtd_submnu`, `fixon_000001_cols`, `fixon_000001_rows`, `fixon_000001_fieldcls`, `fixon_000001_url_painel`, `fixon_000001_xtype`, `fixon_000001_type`, `fixon_000001_size`) VALUES
  (NULL, 'fixon_000590', 'fixon_000590_codigo', NULL, 'Código', NULL, 1, 0, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000590', 'fixon_000590_data', NULL, 'Data', NULL, 1, 1, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000590', 'fixon_000590_hora', NULL, 'Hora', NULL, 1, 2, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000590', 'fixon_000590_caixa', NULL, 'Caixa', NULL, 1, 3, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000590', 'fixon_000590_saldo', NULL, 'Saldo', NULL, 1, 4, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
  ";
  $mysqli->query($sql);
  //modo nnew
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000590_saldo';");
  //modo edit
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000590_saldo';");
}

register_deactivation_hook( $plugin_file, "fixon_000590d" );
function fixon_000590d() {
    global $wpdb;
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000002 where fixon_000002_tabela = 'fixon_000590';");
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = 'fixon_000590';");
    // $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000590");
}

function fixon_000590($atts, $content = null){
  $ret = '';
  $ret .= '';
  $ret .= do_shortcode('[fixon_botao label="novo" target="?op=nnew" role="master|super"]');
  $ret .= do_shortcode('[fixon_busca target_det="?op=view" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_nnew md="fixon_000590" on_op="nnew" un_show="fixon_000590_codigo fixon_000590_data fixon_000590_hora" role="master|super" ]');
  $ret .= do_shortcode('[fixon_insert md="fixon_000590" on_op="insert" role="master|super"]');
  $ret .= do_shortcode('[fixon_list md="fixon_000590" on_op="empty" un_show="" col_url="fixon_000590_caixa:<a href=?op=view&cod=__fixon_000590_codigo__>__this__</a>" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_view md="fixon_000590" cod=__cod__ on_op="view" un_show="" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_botao label="edit" on_op="view" target="?op=edit&cod=__cod__" role="master|super"]');
  $ret .= do_shortcode('[fixon_edit md="fixon_000590" cod=__cod__ on_op="edit" un_show="fixon_000590_codigo fixon_000590_data fixon_000590_hora" role="master|super"]');
  $ret .= do_shortcode('[fixon_update md="fixon_000590" cod=__cod__ on_op="update" role="master|super"]');
  $ret .= "<br>";
  $ret .= do_shortcode('[fixon_botao label="Registrar nova entrada ou saída deste caixa" target="/fluxo-de-caixa/?op=nnew&fixon_000600_caixa=__cod__" on_op="view" role="master|super"]');

  

    return $ret;
}
add_shortcode("fixon_000590", "fixon_000590");
