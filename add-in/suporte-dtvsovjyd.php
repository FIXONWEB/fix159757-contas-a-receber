<?php
/*
FLUXO DE CAIXA
CODIGO DE SUPORTE: dtvsovjyd
*/ 
if ( ! defined( 'ABSPATH' ) ) { exit; }
register_activation_hook( $plugin_file, 'fixon_000600a' );
function fixon_000600a() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."fixon_000600` (
    `fixon_000600_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
    `fixon_000600_data` date,
    `fixon_000600_hora` varchar(10),
    `fixon_000600_pessoa` int,
    `fixon_000600_caixa` int,
    `fixon_000600_pessoa_descricao` varchar(60),
    `fixon_000600_caixa_descricao` varchar(60),
    `fixon_000600_es` varchar(1),
    `fixon_000600_descricao` varchar(60),
    `fixon_000600_entrada` float(7,2),
    `fixon_000600_saida` float(7,2),
    `fixon_000600_saldo` float(7,2),

    PRIMARY KEY (`fixon_000600_codigo`)
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

  'fixon_000600', 
  'fixon_000600_codigo', 
  10, 
  'DESC', 
  ''
  );
  ";
  $wpdb->query($sql);

  $sql = "
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000600_bi` BEFORE INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000600` FOR EACH ROW begin
      if new.fixon_000600_data is null then set new.fixon_000600_data  = (SELECT DATE(CURRENT_TIMESTAMP())); end if;
      if new.fixon_000600_hora = '' then set new.fixon_000600_hora  = (SELECT CURRENT_TIME()); end if;
      if new.fixon_000600_entrada > 0 then set new.fixon_000600_es  = 'e'; end if;
      if new.fixon_000600_saida > 0 then set new.fixon_000600_es  = 's'; end if;

      set new.fixon_000600_caixa_descricao = (select fixon_000590_caixa from ".$GLOBALS['wpdb']->prefix."fixon_000590 where fixon_000590_codigo = new.fixon_000600_caixa);

      set new.fixon_000600_saldo = (select fixon_000600_saldo from ".$GLOBALS['wpdb']->prefix."fixon_000600 order by fixon_000600_codigo desc limit 1 );
      if new.fixon_000600_saldo is null then 
      	if new.fixon_000600_entrada > 0 then
      		set new.fixon_000600_saldo  = new.fixon_000600_entrada; 
      	end if;
      	if new.fixon_000600_saida > 0 then
      		set new.fixon_000600_saldo  = new.fixon_000600_saida; 
      	end if;
      else 
      	if new.fixon_000600_entrada > 0 then
      		set new.fixon_000600_saldo  = new.fixon_000600_saldo + new.fixon_000600_entrada; 
      	end if;
      	if new.fixon_000600_saida > 0 then
      		set new.fixon_000600_saldo  = new.fixon_000600_saldo - new.fixon_000600_saida; 
      	end if;
      end if;

  end;
  CREATE TRIGGER IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000600_ai` AFTER INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000600` FOR EACH ROW begin
    if new.fixon_000600_entrada > 0 then
      update ".$wpdb->prefix."fixon_000590 set fixon_000590_saldo = fixon_000590_saldo + new.fixon_000600_entrada where fixon_000590_codigo = new.fixon_000600_caixa;
    end if;
    if new.fixon_000600_saida > 0 then
      update ".$wpdb->prefix."fixon_000590 set fixon_000590_saldo = fixon_000590_saldo - new.fixon_000600_saida where fixon_000590_codigo = new.fixon_000600_caixa;
    end if;
  end;
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->multi_query($sql);
	// fixon_create_fields("fixon_000600");

  $sql = "
  INSERT INTO `".$GLOBALS['wpdb']->prefix."fixon_000001` (`fixon_000001_codigo`, `fixon_000001_tabela`, `fixon_000001_campo`, `fixon_000001_value`, `fixon_000001_label`, `fixon_000001_hidelabel`, `fixon_000001_show`, `fixon_000001_ordem`, `fixon_000001_ctr_new`, `fixon_000001_ctr_edit`, `fixon_000001_ctr_view`, `fixon_000001_ctr_list`, `fixon_000001_ctr_loc`, `fixon_000001_ctr_lst`, `fixon_000001_ctr_vitrine`, `fixon_000001_dm`, `fixon_000001_tipo`, `fixon_000001_height`, `fixon_000001_largura`, `fixon_000001_altura`, `fixon_000001_tamanho`, `fixon_000001_align`, `fixon_000001_hidden`, `fixon_000001_black`, `fixon_000001_cls`, `fixon_000001_style`, `fixon_000001_cls_cp`, `fixon_000001_cls_view`, `fixon_000001_cls_vitrine`, `fixon_000001_clslabel`, `fixon_000001_ctcls`, `fixon_000001_itemcls`, `fixon_000001_formato`, `fixon_000001_renderer`, `fixon_000001_cmb_tp`, `fixon_000001_cmb_source`, `fixon_000001_cmb_codigo`, `fixon_000001_cmb_descri`, `fixon_000001_access_pub`, `fixon_000001_access_usr`, `fixon_000001_access_adm`, `fixon_000001_access_root`, `fixon_000001_url`, `fixon_000001_url_md`, `fixon_000001_url_op`, `fixon_000001_param`, `fixon_000001_modo`, `fixon_000001_cp_url`, `fixon_000001_ativo`, `fixon_000001_qtd_gr`, `fixon_000001_somar`, `fixon_000001_qtd_submnu`, `fixon_000001_cols`, `fixon_000001_rows`, `fixon_000001_fieldcls`, `fixon_000001_url_painel`, `fixon_000001_xtype`, `fixon_000001_type`, `fixon_000001_size`) VALUES
  (NULL, 'fixon_000600', 'fixon_000600_codigo', NULL, 'Código', NULL, 1, 0, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_data', NULL, 'Data', NULL, 1, 1, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_hora', NULL, 'Hora', NULL, 1, 2, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_caixa', NULL, 'Caixa', NULL, 1, 3, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'int', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_caixa_descricao', NULL, 'Caixa descrição', NULL, 1, 4, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_es', NULL, 'E/S', NULL, 1, 5, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_descricao', NULL, 'Descrição', NULL, 1, 6, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_entrada', NULL, 'Entrada Valor R$', NULL, 1, 7, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_saida', NULL, 'Saida Valor R$', NULL, 1, 8, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
  (NULL, 'fixon_000600', 'fixon_000600_saldo', NULL, 'Saldo', NULL, 1, 9, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
  ";
  $wpdb->query($sql);
  // $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000600_caixa_descricao';");
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000600_saldo';");
  $wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000600_es';");
}

register_deactivation_hook( $plugin_file, "fixon_000600d" );
function fixon_000600d() {
    global $wpdb;
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000002 where fixon_000002_tabela = 'fixon_000600';");
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = 'fixon_000600';");
    // $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000600");
}


function fixon_000600($atts, $content = null){
  $ret = '';
  $ret .= '';
  $ret .= do_shortcode('[fixon_botao label="novo" target="?op=nnew" role="master|super"]');
  $ret .= do_shortcode('[fixon_busca target_det="?op=view" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_nnew md="fixon_000600" on_op="nnew" un_show="fixon_000600_codigo fixon_000600_data fixon_000600_hora" combo_ajax="fixon_000600_caixa:fixon_000590:fixon_000590_codigo:fixon_000590_caixa:fixon_000600_caixa:fixon_000600_caixa_descricao" role="master|super"]');
  $ret .= do_shortcode('[fixon_insert md="fixon_000600" on_op="insert" role="master|super"]');
  $ret .= do_shortcode('[fixon_list md="fixon_000600" on_op="empty" un_show="" col_url="fixon_000600_nome:<a href=?op=view&cod=__fixon_000600_codigo__>__this__</a>" sql_order="fixon_000600_codigo" sql_dir="DESC" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_view md="fixon_000600" cod=__cod__ on_op="view" un_show="" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_botao label="edit" on_op="view" target="?op=edit&cod=__cod__"  role="master|super"]');
  $ret .= do_shortcode('[fixon_edit md="fixon_000600" cod=__cod__ on_op="edit" un_show="fixon_000600_codigo fixon_000600_data fixon_000600_hora" role="master|super"]');
  $ret .= do_shortcode('[fixon_update md="fixon_000600" cod=__cod__ on_op="update" role="master|super"]');
    return $ret;
}
add_shortcode("fixon_000600", "fixon_000600");

      //combo_ajax="fixon_000520_pessoa:tabela:campo1:campo2:target_campo1:target_campo2"
      //combo_ajax="campo_que_chama_o_combo:tabela:tabela_campo1:tabela_campo2:target_campo1:target_campo1"
