<?php
#die('parou');

/*
CLIENTES
CODIGO DE SUPORTE: gshcuenp
*/ 

if ( ! defined( 'ABSPATH' ) ) { exit; }

register_activation_hook( $plugin_file, 'fixon_gshcuenpa' );
function fixon_gshcuenpa() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."fixon_000200` (

    `fixon_000200_codigo` bigint(20) NOT NULL AUTO_INCREMENT,
    `fixon_000200_data` date DEFAULT NULL,
    `fixon_000200_hora` varchar(12) DEFAULT NULL,
    `fixon_000200_nome` varchar(60),
    `fixon_000200_cpf` varchar(60),
    `fixon_000200_rg` varchar(60),
    `fixon_000200_telefone` varchar(60),
    `fixon_000200_email` varchar(60),
    `fixon_000200_nascimento` date,
    `fixon_000200_endereco` varchar(60),
    `fixon_000200_bairro` varchar(60),
    `fixon_000200_cidade` varchar(60),
    `fixon_000200_uf` varchar(2),
    `fixon_000200_cep` varchar(15),
    `fixon_000200_foto` varchar(200),
    `fixon_000200_a_pagar` float(7,2),
    `fixon_000200_a_receber` float(7,2),
    `fixon_000200_pago` float(7,2),
    `fixon_000200_recebido` float(7,2),


    PRIMARY KEY (`fixon_000200_codigo`)
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

  'fixon_000200', 
  'fixon_000200_codigo', 
  20, 
  'DESC', 
  ''
  );
  ";
  $wpdb->query($sql);

  $sql = "
  CREATE TRIGGER `".$GLOBALS['wpdb']->prefix."fixon_000200_bi` BEFORE INSERT ON `".$GLOBALS['wpdb']->prefix."fixon_000200` FOR EACH ROW begin
      if new.fixon_000200_data IS NULL then set new.fixon_000200_data  = (SELECT DATE(CURRENT_TIMESTAMP())); end if;
      if new.fixon_000200_hora = '' then set new.fixon_000200_hora  = (SELECT TIME(CURRENT_TIME())); end if;
  end
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->query($sql);

  // fixon_create_fields("fixon_000200");
$sql = "
INSERT INTO `".$GLOBALS['wpdb']->prefix."fixon_000001` (`fixon_000001_codigo`, `fixon_000001_tabela`, `fixon_000001_campo`, `fixon_000001_value`, `fixon_000001_label`, `fixon_000001_hidelabel`, `fixon_000001_show`, `fixon_000001_ordem`, `fixon_000001_ctr_new`, `fixon_000001_ctr_edit`, `fixon_000001_ctr_view`, `fixon_000001_ctr_list`, `fixon_000001_ctr_loc`, `fixon_000001_ctr_lst`, `fixon_000001_ctr_vitrine`, `fixon_000001_dm`, `fixon_000001_tipo`, `fixon_000001_height`, `fixon_000001_largura`, `fixon_000001_altura`, `fixon_000001_tamanho`, `fixon_000001_align`, `fixon_000001_hidden`, `fixon_000001_black`, `fixon_000001_cls`, `fixon_000001_style`, `fixon_000001_cls_cp`, `fixon_000001_cls_view`, `fixon_000001_cls_vitrine`, `fixon_000001_clslabel`, `fixon_000001_ctcls`, `fixon_000001_itemcls`, `fixon_000001_formato`, `fixon_000001_renderer`, `fixon_000001_cmb_tp`, `fixon_000001_cmb_source`, `fixon_000001_cmb_codigo`, `fixon_000001_cmb_descri`, `fixon_000001_access_pub`, `fixon_000001_access_usr`, `fixon_000001_access_adm`, `fixon_000001_access_root`, `fixon_000001_url`, `fixon_000001_url_md`, `fixon_000001_url_op`, `fixon_000001_param`, `fixon_000001_modo`, `fixon_000001_cp_url`, `fixon_000001_ativo`, `fixon_000001_qtd_gr`, `fixon_000001_somar`, `fixon_000001_qtd_submnu`, `fixon_000001_cols`, `fixon_000001_rows`, `fixon_000001_fieldcls`, `fixon_000001_url_painel`, `fixon_000001_xtype`, `fixon_000001_type`, `fixon_000001_size`) VALUES
(NULL, 'fixon_000200', 'fixon_000200_codigo', NULL, 'Código', NULL, 1, 0, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_data', NULL, 'Data', NULL, 1, 1, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_hora', NULL, 'Hora', NULL, 1, 2, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_nome', NULL, 'Nome', NULL, 1, 3, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_cpf', NULL, 'CPF', NULL, 1, 4, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_rg', NULL, 'RG', NULL, 1, 5, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_telefone', NULL, 'Telefone', NULL, 1, 6, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_email', NULL, 'E-mail', NULL, 1, 7, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_nascimento', NULL, 'Nascimento', NULL, 1, 8, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_endereco', NULL, 'Endereço', NULL, 1, 9, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_bairro', NULL, 'Bairro', NULL, 1, 10, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_cidade', NULL, 'Cidade', NULL, 1, 11, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_uf', NULL, 'UF', NULL, 1, 12, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_cep', NULL, 'CEP', NULL, 1, 13, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_foto', NULL, 'Foto', NULL, 1, 14, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'string', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_a_pagar', NULL, 'A pagar', NULL, 1, 15, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_a_receber', NULL, 'A receber', NULL, 1, 16, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_pago', NULL, 'Pago', NULL, 1, 17, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(NULL, 'fixon_000200', 'fixon_000200_recebido', NULL, 'Recebido', NULL, 1, 18, 'textfield', 'textfield', 'label', 'label', NULL, NULL, NULL, NULL, 'float', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 's', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
";
$mysqli->query($sql);

$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000200_foto';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000200_a_pagar';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000200_a_receber';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000200_pago';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_new = 'oculto' where fixon_000001_campo = 'fixon_000200_recebido';");
//modo edit
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000200_foto';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000200_a_pagar';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000200_a_receber';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000200_pago';");
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_edit = 'oculto' where fixon_000001_campo = 'fixon_000200_recebido';");
//modo edit
$wpdb->query("update ".$GLOBALS['wpdb']->prefix."fixon_000001 set fixon_000001_ctr_view = 'oculto' where fixon_000001_campo = 'fixon_000200_foto';");
}


register_deactivation_hook( $plugin_file, "fixon_gshcuenpd" );
function fixon_gshcuenpd() {
    global $wpdb;
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000002 where fixon_000002_tabela = 'fixon_000200';");
    $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = 'fixon_000200';");
    // $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000200");
    // $wpdb->query( "DROP TABLE IF EXISTS ".$GLOBALS['wpdb']->prefix."fixon_000002");
    // $wpdb->query( "DROP TABLE IF EXISTS ".$GLOBALS['wpdb']->prefix."fixon_000001");
}



function fixon_000200($atts, $content = null){
#	echo "---";
  $op = isset($_GET['op']) ? $_GET['op'] : '';
  $ret = '';
  $ret .= '';
  $ret .= do_shortcode('[fixon_botao label="novo" target="?op=nnew" role="master|super"]');
  $ret .= do_shortcode('[fixon_busca target_det="?op=view" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_nnew md="fixon_000200" on_op="nnew" un_show="fixon_000200_codigo fixon_000200_data fixon_000200_hora" role="master|super" ]');
  $ret .= do_shortcode('[fixon_insert md="fixon_000200" on_op="insert" role="master|super"]');
  $ret .= do_shortcode('[fixon_list md="fixon_000200" on_op="empty" un_show="" col_url="fixon_000200_nome:<a href=?op=view&cod=__fixon_000200_codigo__>__this__</a>" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_view md="fixon_000200" cod=__cod__ on_op="view" un_show="" role="master|super|monitor"]');
  $ret .= do_shortcode('[fixon_botao label="edit" on_op="view" target="?op=edit&cod=__cod__" role="master|super" ]');
  $ret .= do_shortcode('[fixon_edit md="fixon_000200" cod=__cod__ on_op="edit" un_show="fixon_000200_codigo fixon_000200_data fixon_000200_hora" role="master|super"]');
  $ret .= do_shortcode('[fixon_update md="fixon_000200" cod=__cod__ on_op="update" role="master|super"]');
  $ret .= "<br>";
  $ret .= do_shortcode('[fixon_botao label="nova conta a receber" target="/contas-a-receber/?op=nnew&fixon_000520_pessoa=__cod__" on_op="view" role="master|super"]');
  $ret .= "<br>";
  $ret .= do_shortcode('[fixon_botao label="nova conta a pagar" target="/contas-a-pagar/?op=nnew&fixon_000510_pessoa=__cod__" on_op="view" role="master|super"]');

  if (($op=="view") && fixon_is_role("master|super|monitor")) {
    $ret .= "<br>";
    $ret .= "<h3>CONTAS A RECEBER DESTE CLIENTE</h3>";
    $ret .= do_shortcode('[fixon_list md="fixon_000520" on_op="view" un_show="" col__url="fixon_000520_pessoa_descri:<a href=?op=view&cod=__fixon_000520_codigo__>__this__</a>" criterio="fixon_000520_pessoa=__cod__" role="master|super|monitor"]');
    $ret .= "<br>";
    $ret .= "<h3>CONTAS A PAGAR DESTE CLIENTE</h3>";
    $ret .= do_shortcode('[fixon_list md="fixon_000510" on_op="view" un_show="" col__url="fixon_000510_pessoa_descri:<a href=?op=view&cod=__fixon_000510_codigo__>__this__</a>"  criterio="fixon_000510_pessoa=__cod__" role="master|super|monitor"]');
  }
  return $ret;

  // https://8xwdjyd7p2.cloud.fixon.biz/contas-a-receber/?op=nnew&fixon_000521_pessoa=__cod__
}
add_shortcode("fixon_000200", "fixon_000200");


