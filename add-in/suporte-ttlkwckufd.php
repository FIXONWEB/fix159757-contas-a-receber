<?php
/*
CONTAS A PAGAR
CODIGO DE SUPORTE: ttlkwckufd
*/ 
if ( ! defined( 'ABSPATH' ) ) { exit; }


register_activation_hook( $plugin_file, 'fixon_000510a' );
function fixon_000510a() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."fixon_000510` (
    `fixon_000510_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
    `fixon_000510_data` date,
    `fixon_000510_hora` varchar(10),
    `fixon_000510_pessoa` int,
    `fixon_000510_pessoa_descri` varchar(60),
    `fixon_000510_descricao` varchar(100),
    `fixon_000510_vencimento` date,
    `fixon_000510_valor` float(7,2),
    `fixon_000510_pago` float(7,2),
    `fixon_000510_situacao` varchar(20) NOT NULL DEFAULT 'NO PRAZO',

    PRIMARY KEY (`fixon_000510_codigo`)
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

  'fixon_000510', 
  'fixon_000510_codigo', 
  20, 
  'ASC', 
  ''
  );
  ";
  $wpdb->query($sql);

  $sql = "
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000510_bi` BEFORE INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000510` FOR EACH ROW begin
      if new.fixon_000510_data is null then set new.fixon_000510_data  = (SELECT DATE(CURRENT_TIMESTAMP())); end if;
      if new.fixon_000510_hora = '' then set new.fixon_000510_hora  = (SELECT TIME(CURRENT_TIME())); end if;
      set new.fixon_000510_situacao = 'NO PRAZO';
      set new.fixon_000510_pessoa_descri = (select fixon_000200_nome from ".$GLOBALS['wpdb']->prefix."fixon_000200 where fixon_000200_codigo = new.fixon_000510_pessoa);
  end;
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000510_ai` AFTER INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000510` FOR EACH ROW begin
  	update ".$GLOBALS['wpdb']->prefix."fixon_000200 set fixon_000200_a_pagar = fixon_000200_a_pagar + new.fixon_000510_valor where fixon_000200_codigo = new.fixon_000510_pessoa;
  end;
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000510_ad` AFTER DELETE ON `".$GLOBALS['wpdb']->prefix."fixon_000510` FOR EACH ROW begin
  	update ".$GLOBALS['wpdb']->prefix."fixon_000200 set fixon_000200_a_pagar = fixon_000200_a_pagar - old.fixon_000510_valor where fixon_000200_codigo = old.fixon_000510_pessoa;
  end;
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000510_au` AFTER UPDATE ON `".$GLOBALS['wpdb']->prefix."fixon_000510` FOR EACH ROW begin
  	set new.fixon_000510_pessoa_descri = (select fixon_000200_nome from ".$GLOBALS['wpdb']->prefix."fixon_000200 where fixon_000200_codigo = new.fixon_000510_pessoa);
  	update ".$GLOBALS['wpdb']->prefix."fixon_000200 set fixon_000200_a_pagar = fixon_000200_a_pagar - old.fixon_000510_valor where fixon_000200_codigo = old.fixon_000510_pessoa;
  	update ".$GLOBALS['wpdb']->prefix."fixon_000200 set fixon_000200_a_pagar = fixon_000200_a_pagar + new.fixon_000510_valor where fixon_000200_codigo = new.fixon_000510_pessoa;
  end;
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->multi_query($sql);


	// fixon_create_fields("fixon_000510");

  $sql = "
  INSERT INTO `".$GLOBALS['wpdb']->prefix."fixon_000001` (`fixon_000001_codigo`, `fixon_000001_tabela`, `fixon_000001_campo`, `fixon_000001_value`, `fixon_000001_label`, `fixon_000001_hidelabel`, `fixon_000001_show`, `fixon_000001_ordem`, `fixon_000001_ctr_new`, `fixon_000001_ctr_edit`, `fixon_000001_ctr_view`, `fixon_000001_ctr_list`, `fixon_000001_ctr_loc`, `fixon_000001_ctr_lst`, `fixon_000001_ctr_vitrine`, `fixon_000001_dm`, `fixon_000001_tipo`, `fixon_000001_height`, `fixon_000001_largura`, `fixon_000001_altura`, `fixon_000001_tamanho`, `fixon_000001_align`, `fixon_000001_hidden`, `fixon_000001_black`, `fixon_000001_cls`, `fixon_000001_style`, `fixon_000001_cls_cp`, `fixon_000001_cls_view`, `fixon_000001_cls_vitrine`, `fixon_000001_clslabel`, `fixon_000001_ctcls`, `fixon_000001_itemcls`, `fixon_000001_formato`, `fixon_000001_renderer`, `fixon_000001_cmb_tp`, `fixon_000001_cmb_source`, `fixon_000001_cmb_codigo`, `fixon_000001_cmb_descri`, `fixon_000001_access_pub`, `fixon_000001_access_usr`, `fixon_000001_access_adm`, `fixon_000001_access_root`, `fixon_000001_url`, `fixon_000001_url_md`, `fixon_000001_url_op`, `fixon_000001_param`, `fixon_000001_modo`, `fixon_000001_cp_url`, `fixon_000001_ativo`, `fixon_000001_qtd_gr`, `fixon_000001_somar`, `fixon_000001_qtd_submnu`, `fixon_000001_cols`, `fixon_000001_rows`, `fixon_000001_fieldcls`, `fixon_000001_url_painel`, `fixon_000001_xtype`, `fixon_000001_type`, `fixon_000001_size`) VALUES
  (NULL, 'fixon_000510', 'fixon_000510_codigo', NULL, 'Código', NULL, 1, 0, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_data', NULL, 'Data', NULL, 1, 1, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_hora', NULL, 'Hora', NULL, 1, 2, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_pessoa', NULL, 'Fornecedor', NULL, 1, 3, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_pessoa_descri', NULL, 'Fornecedor Nome', NULL, 1, 4, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_descricao', NULL, 'Descrição', NULL, 1, 5, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_vencimento', NULL, 'Vencimento', NULL, 1, 6, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_valor', NULL, 'Valor', NULL, 1, 7, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_pago', NULL, 'Pago', NULL, 1, 8, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000510', 'fixon_000510_situacao', NULL, 'Situação', NULL, 1, 9, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
  ";
  $wpdb->query($sql);

  // fixon_000510_pessoa_descri
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000510_pessoa_descri'");
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000510_pago'");
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000510_situacao'");
  

}

register_deactivation_hook( $plugin_file, "fixon_000510d" );
function fixon_000510d() {
    global $wpdb;
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000002 where fixon_000002_tabela = 'fixon_000510';");
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = 'fixon_000510';");
    // $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000510");
}


function fixon_000510($atts, $content = null){
  $ret = '';
  $ret .= '';
  $ret .= do_shortcode('[fixon_botao label="novo" target="?op=nnew" role="master|super"]');
  $ret .= do_shortcode('[fixon_busca target_det="?op=view" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_nnew md="fixon_000510" on_op="nnew" un_show="fixon_000510_codigo fixon_000510_data fixon_000510_hora" role="master|super"]');
  $ret .= do_shortcode('[fixon_insert md="fixon_000510" on_op="insert" role="master|super"]');
  $ret .= do_shortcode('[fixon_list md="fixon_000510" on_op="empty" un_show="" col_url="fixon_000510_pessoa_descri:<a href=?op=view&cod=__fixon_000510_codigo__>__this__</a>" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_view md="fixon_000510" cod=__cod__ on_op="view" un_show="" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_botao label="edit" on_op="view" target="?op=edit&cod=__cod__" role="master|super"]');
  $ret .= do_shortcode('[fixon_edit md="fixon_000510" cod=__cod__ on_op="edit" un_show="fixon_000510_codigo fixon_000510_data fixon_000510_hora" role="master|super"]');
  $ret .= do_shortcode('[fixon_update md="fixon_000510" cod=__cod__ on_op="update" role="master|super"]');
  $ret .= "<br>";
  $ret .= do_shortcode('[fixon_botao label="Registrar pagamento" target="/outros/baixa-de-contas-a-pagar/?op=nnew&fixon_000511_conta_a_pagar=__cod__" on_op="view" role="master|super"]');
  return $ret;
}
add_shortcode("fixon_000510", "fixon_000510");
