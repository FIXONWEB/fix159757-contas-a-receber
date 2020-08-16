<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }

register_activation_hook( $plugin_file, 'fixon_suportea' );
function fixon_suportea() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;

  add_role( 'master', 'Master', array( 'read' => true, 'level_0' => true ) );
  add_role( 'monitor', 'Monitor', array( 'read' => true, 'level_0' => true ) );
  add_role( 'cliente', 'Cliente', array( 'read' => true, 'level_0' => true ) );
  add_role( 'super', 'Super', array( 'read' => true, 'level_0' => true ) );

  // tabela que controla os modulos
  $sql = "
  CREATE TABLE IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000002` (
  `fixon_000002_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fixon_000002_tabela` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000002_sql_sort` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000002_sql_limit` int(11) DEFAULT NULL,
  `fixon_000002_sql_dir` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000002_ativo` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`fixon_000002_codigo`),
  UNIQUE KEY `fixon_000002_codigo` (`fixon_000002_codigo`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
  ";
  $wpdb->query($sql);

  // tabela que controla os campos 

  $sql = "
  CREATE TABLE IF NOT EXISTS `".$GLOBALS['wpdb']->prefix."fixon_000001` (
  `fixon_000001_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `fixon_000001_tabela` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_campo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_label` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_hidelabel` int(11) DEFAULT NULL,
  `fixon_000001_show` int(11) DEFAULT '1',
  `fixon_000001_ordem` int(11) DEFAULT NULL,
  `fixon_000001_ctr_new` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_edit` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_view` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_list` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_loc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_lst` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctr_vitrine` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_dm` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_tipo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_height` int(11) DEFAULT NULL,
  `fixon_000001_largura` int(11) DEFAULT NULL,
  `fixon_000001_altura` int(11) DEFAULT NULL,
  `fixon_000001_tamanho` int(11) DEFAULT NULL,
  `fixon_000001_align` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_hidden` int(11) DEFAULT NULL,
  `fixon_000001_black` int(11) DEFAULT NULL,
  `fixon_000001_cls` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_style` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cls_cp` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cls_view` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cls_vitrine` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_clslabel` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ctcls` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_itemcls` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_formato` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_renderer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cmb_tp` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cmb_source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cmb_codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cmb_descri` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_access_pub` int(11) DEFAULT '0',
  `fixon_000001_access_usr` int(11) DEFAULT '0',
  `fixon_000001_access_adm` int(11) DEFAULT '0',
  `fixon_000001_access_root` int(11) DEFAULT '0',
  `fixon_000001_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_url_md` int(11) DEFAULT NULL,
  `fixon_000001_url_op` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_param` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_modo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_cp_url` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_ativo` varchar(1) COLLATE utf8_unicode_ci DEFAULT 's',
  `fixon_000001_qtd_gr` int(11) DEFAULT '0',
  `fixon_000001_somar` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_qtd_submnu` int(11) DEFAULT '0',
  `fixon_000001_cols` int(11) DEFAULT NULL,
  `fixon_000001_rows` int(11) DEFAULT NULL,
  `fixon_000001_fieldcls` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_url_painel` text COLLATE utf8_unicode_ci,
  `fixon_000001_xtype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fixon_000001_size` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`fixon_000001_codigo`),
  UNIQUE KEY `fixon_000001_codigo` (`fixon_000001_codigo`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
  ";
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->query($sql);
}

function fixon_date_mysql_br($data){
  // return "-";
  $ex = explode("-", $data);
  if(count($ex)==3){
    list($dia,$mes,$ano) = explode("-", $data);
    return $ano."/".$mes."/".$dia;
  }else{
    return $data;
  }
}

function fixon_db_exe($sql,$op='rows',$conn=1){
  $ret = array();
  if($op=='rows'){
    $rr = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
    $ret['rows'] = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
    $rows['total'] = count($rr);
    $ret['r'] = $rows['total'];
    $ret['sql'] = $sql;
    return $ret;
  }
  return $GLOBALS['wpdb']->query($sql);
}

function fixon_db_data($sql,$op='rows',$cnn=''){
  $ret = array();
  if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $wpmsc_grupo = get_user_meta($user_id,  'wpmsc_grupo', true );
    if($wpmsc_grupo){
      $grupo_db_host = get_post_meta( $wpmsc_grupo, 'grupo_db_host', true );
      $grupo_db_name = get_post_meta( $wpmsc_grupo, 'grupo_db_name', true );
      $grupo_db_user = get_post_meta( $wpmsc_grupo, 'grupo_db_user', true );
      $grupo_db_pass = get_post_meta( $wpmsc_grupo, 'grupo_db_pass', true );
      $mysqli = new mysqli($grupo_db_host, $grupo_db_user, $grupo_db_pass, $grupo_db_name);
      //echo '<!--'.$sql.' '.$grupo_db_name.'-->';
      $result = mysqli_query($mysqli, $sql);
      if($op=='rows'){
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
          $rows[] = $row;
        }
        $ret['rows']  = $rows;
        $ret['total'] = count($ret['rows']);
        $ret['r']     = $ret['total'];
        $ret['sql']   = $sql;
        return $ret;
      }
    }
  }
  
  if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $wpmsc_user_grupo = get_user_meta($user_id,  'wpmsc_user_grupo', true );
    if ($wpmsc_user_grupo) {

      $mysqli = fixon_mysqli_no_grupo($wpmsc_user_grupo);
      $result = mysqli_query($mysqli, $sql);
      if($op=='rows'){
        $rows = array();
        if($result)
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
          $rows[] = $row;
        }

        $ret['rows']  = $rows;
        $ret['total'] = count($ret['rows']);
        $ret['r']     = $ret['total'];
        $ret['sql']   = $sql;
        return $ret;
      }

    }

  }
  
  if($op=='rows'){
    //$row = $wpdb->get_results($sql, 'ARRAY_A');
    $rr = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
    $ret['rows'] = $GLOBALS['wpdb']->get_results($sql, 'ARRAY_A');
    $rows['total'] = count($rr);
    $ret['r'] = $rows['total'];
    $ret['sql'] = $sql;
    return $ret;
  }
  return $GLOBALS['wpdb']->query($sql);

}

class fixon_suporte_update {
    public $current_version;
    public $update_path;
    public $plugin_slug;
    public $slug;
    public $plugin_key;
    function __construct($current_version, $update_path, $plugin_slug, $plugin_key){
        $this->current_version = $current_version;
        $this->update_path = $update_path;
        $this->plugin_slug = $plugin_slug;
        $this->plugin_key = $plugin_key;
        list ($t1, $t2) = explode('/', $plugin_slug);
        $this->slug = str_replace('.php', '', $t2);
        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));
        add_filter('plugins_api', array(&$this, 'check_info'), 10, 3);
    }
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        $remote_version = $this->getRemote_version();
        if (version_compare($this->current_version, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $remote_version;
            $obj->url = $this->update_path;
            $obj->package = $this->update_path;
            $transient->response[$this->plugin_slug] = $obj;
        }
        return $transient;
    }
    public function check_info($false, $action, $arg) {
        if ($arg->slug === $this->slug) {
            $information = $this->getRemote_information();
            return $information;
        }
        return false;
    }
    public function getRemote_version() {
        $this->update_path = $this->update_path."?p=".$this->plugin_slug."&v=".$this->current_version."&k=".$this->plugin_key;
        $request = wp_remote_post(
            $this->update_path, 
                array('body' => array(
                    'action' => 'version'
                )
            )
        );
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
    public function getRemote_information() {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return unserialize($request['body']);
        }
        return false;
    }
    public function getRemote_license() {
        $request = wp_remote_post($this->update_path, array('body' => array('action' => 'license')));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return $request['body'];
        }
        return false;
    }
}
include_once("function_md.php");
include_once("shortcode.php");
include_once("function_get_md.php");
include_once("paging.php");
include_once("access.php");


function fixon_directory_to_array($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
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
                    $arrayItems = array_merge($arrayItems, fixon_object_to_array($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
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

function fixon_gera_senha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
  $lmin = 'abcdefghijklmnopqrstuvwxyz';
  $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $num = '1234567890';
  $simb = '!@#$%*-';
  $retorno = '';
  $caracteres = '';
  $caracteres .= $lmin;
  if ($maiusculas) $caracteres .= $lmai;
  if ($numeros) $caracteres .= $num;
  if ($simbolos) $caracteres .= $simb;
  $len = strlen($caracteres);
  for ($n = 1; $n <= $tamanho; $n++) {
    $rand = mt_rand(1, $len);
    $retorno .= $caracteres[$rand-1];
  }
  return $retorno;
}


function fixon_object_to_array($data){
  if ((! is_array($data)) and (! is_object($data))) return 'xxx'; //$data;
  $result = array();
  $data = (array) $data;
  foreach ($data as $key => $value) {
      if (is_object($value)) $value = (array) $value;
      if (is_array($value)) 
      $result[$key] = fixon_object_to_array($value);
      else
          $result[$key] = $value;
  }
  return $result;
}


function fixon_select_vai($ct,$op){
  if($op=="list"){
    if($ct=='combobox')   return true;
    if($ct=='label')    return true;
    if($ct=='label_user')   return true;
    if($ct=='hidden')   return true;
    return false;
  }
  if($op=="view"){
    if($ct=='label')    return true;
    if($ct=='hidden')   return true;
    return false;
  }
  if($op=="novo"){
    if($ct=='textfield')  return true;
    if($ct=='numberfield')  return true;
    if($ct=='datefield')  return true;
    if($ct=='combobox')   return true;
    if($ct=='textarea')   return true;
    if($ct=='htmleditor')   return true;
    if($ct=='ckeditor')   return true;
    if($ct=='radio')    return true;
    if($ct=='multcheckbox') return true;
    if($ct=='checkbox')   return true;
    if($ct=='checkbox')   return true;
    if($ct=='hidden')     return true;
    if($ct=='file')     return true;
    return false;
  }
  if($op=="edit"){
    // if($ct=='Label')     return true;
    if($ct=='textfield')  return true;
    if($ct=='numberfield')  return true;
    if($ct=='datefield')  return true;
    if($ct=='combobox')   return true;
    if($ct=='textarea')   return true;
    if($ct=='checkbox')   return true;
    return false;
  }
  if($op=="editu"){
    if($ct=='textfield')  return true;
    if($ct=='numberfield')  return true;
    if($ct=='datefield')  return true;
    if($ct=='combobox')   return true;
    if($ct=='textarea')   return true;
    if($ct=='checkbox')   return true;
    if($ct=='htmleditor')   return true;
    if($ct=='ckeditor')   return true;
    return false;
  }
  return false;
}

function fixon_date_fb_br($data_fb){
  if($data_fb){
    if(is_array($data_fb)){
      $ano = $data_fb[1];
      $mes = $data_fb[2];
      $dia = $data_fb[3];
    }else{
      list($ano,$mes,$dia) = explode("-", $data_fb);
    }
    return $dia."/".$mes."/".$ano;
  }else{
    return 'null';
  }
}

function fixon_moeda_br($valor){
  if(!$valor) $valor = 0;
  return number_format($valor, 2, ',', '.');
  return $valor;
}

function fixon_prefix($de_sistema=false){
  return $GLOBALS["wpdb"]->prefix;
}


function fixon_create_fields($tabela) {

  if(!$tabela) {echo 'tabela'; exit;}
  global $wpdb;

  $sql = "SHOW COLUMNS FROM ".$GLOBALS['wpdb']->prefix.$tabela;
  $tb = fixon_db_exe($sql,'rows');
  $tabela_len = strlen($tabela);
  $sql_name = "";
  $sql_value = "";

  $sql = "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = '".$tabela."';\n";

  $campos = array();
  for ($i=0; $i < $tb['r']; $i++) {
    $tb['rows'][$i]['label']  = $tb['rows'][$i]['Field'];
    $tb['rows'][$i]['tam']  = 10;
    $tb['rows'][$i]['tipo']  = 'string';
    $ctr_new = 'textfield';
    $ctr_edit = 'textfield';
    if(substr($tb['rows'][$i]['Type'], 0, 7) == 'varchar'){
      $tb['rows'][$i]['tipo']  = 'string';
      $tb['rows'][$i]['tam']  = 50;
    }
    if(substr($tb['rows'][$i]['Type'], 0, 5) == 'float'){
      $tb['rows'][$i]['tipo']  = 'float';
      $tb['rows'][$i]['tam']  = 20;
    }
    if(substr($tb['rows'][$i]['Type'], 0, 4) == 'date'){
     $tb['rows'][$i]['tipo']  = 'date'; 
     $tb['rows'][$i]['tam']  = 20;
    }
    if(substr($tb['rows'][$i]['Type'], 0, 3) == 'int'){
     $tb['rows'][$i]['tipo']  = 'int';
     $tb['rows'][$i]['tam']  = 20;
    }
    if(substr($tb['rows'][$i]['Type'], 0, 4) == 'text'){
      $tb['rows'][$i]['tipo']  = 'blob';
      $tb['rows'][$i]['tam']  = 50;

      $ctr_new = 'textarea';
      $ctr_edit = 'textarea';

    }
    $sql .= "
      insert into ".$GLOBALS['wpdb']->prefix."fixon_000001 (
        fixon_000001_tabela,
        fixon_000001_campo, 
        fixon_000001_tipo, 
        fixon_000001_ordem, 

        fixon_000001_ctr_new,
        fixon_000001_ctr_edit,
        fixon_000001_ctr_view, 
        fixon_000001_ctr_list, 

        fixon_000001_label, 
        fixon_000001_ativo 
      
      ) values (
        '".$tabela."',
        '".$tb['rows'][$i]['Field']."',  
        '".$tb['rows'][$i]['tipo']."', 
    ".$i.", 

        '".$ctr_new."', 
        '".$ctr_edit."', 
        'label', 
        'label', 

        '".substr($tb['rows'][$i]['Field'], 13) ."',
        's'
        
      );
    ";
    
  }

  // Ao usar a funcao dbDelta() e necessario carregar este ficheiro
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  // Funcao que cria a tabela na bd e executa as otimizacoes necessarias
  // dbDelta( $sql );
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $mysqli->multi_query($sql);
}



function fixon_moeda_br_to_us($valor){
  $valor = preg_replace('/,/', '.',$valor);
  return $valor;
}


function fixon_date_br_php($data_br){
  if($data_br){
    if(is_array($data_br)){
      $ano = $data_br[3];
      $mes = $data_br[2];
      $dia = $data_br[1];
    }else{
      @list($dia,$mes,$ano) = explode("/", $data_br); 
    }
    $vai = true;
    if(!is_numeric($ano)) $vai = false;
    if(!is_numeric($mes)) $vai = false;
    if(!is_numeric($dia)) $vai = false;
    if($ano=='00') $vai = false;
    if($mes=='00') $vai = false;
    if($dia=='00') $vai = false;
    if($vai){
      if(!checkdate($mes, $dia, $ano)) $vai = false;
    }
    if(strlen($ano)<>4) $vai = false;
    
    if($vai){
      return $ano."-".$mes."-".$dia;
    }else{
      return 'null';
    }
  }else{
    return 'null';
  }
}

function fixon_remove_acento($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

// add_action('my_hourly_event', 'do_this_hourly');
 
// function my_activation() {
//     if ( !wp_next_scheduled( 'my_hourly_event' ) ) {
//         wp_schedule_event(time(), 'hourly', 'my_hourly_event');
//     }
// }
// add_action('wp', 'my_activation');
 
// function do_this_hourly() {
//     // do something every hour
// }