<?php

function fixon_md_edit($md,$cod,$cnn){
  global $wpdb;
  $ret_md_edit = array();
  $campo = array();
  $rules = array();
  $ret_md_edit['campo'] = $campo;
  $ret_md_edit['rules'] = $rules;
  $modulo_conf = fixon_get_modulo_conf($md);
  $sql = "select * from ".fixon_prefix(true)."fixon_000001 where fixon_000001_tabela = '".$md."' and fixon_000001_ativo = 's'order by fixon_000001_ordem";
  $tb = fixon_db_exe($sql,'rows');
  $rows = $tb['rows'];
  $items = array();
  $i=0;
  $r=0;
  foreach ($rows as $row){
    $vai = fixon_select_vai($row['fixon_000001_ctr_edit'],'edit');
    if($vai){
      $campo[$i]["inputId"]     = $row['fixon_000001_campo'];
      $campo[$i]["type"]        = $row['fixon_000001_tipo'];
      $campo[$i]["name"]        = $row['fixon_000001_campo'];
      $campo[$i]["xtype"]       = strtolower($row['fixon_000001_ctr_edit']);
      $campo[$i]["fieldLabel"]  = (($row['fixon_000001_label']));
      $campo[$i]['value']       = '';
      $campo[$i]['black']       = $row['fixon_000001_black'];
      // if($campo[$i]["xtype"]=='textfield') $campo[$i]["type"] = 'text';
      // if($campo[$i]["xtype"]=='combobox'){
      //   $campo[$i]["type"] = 'select';
      //   $sql2  = "select ";
      //   $sql2 .= $row['fixon_000001_cmb_codigo'].", ".$row['fixon_000001_cmb_descri']." ";
      //   $sql2 .= "from ".$row['fixon_000001_cmb_source']." ";
      //   $sql2 .= "order by ".$row['fixon_000001_cmb_descri']." ";
      //   $sql2 .= " ;";
      //   $tb2 = fixon_db_exe($sql2,'rows');
      //   $rows2 = $tb2['rows'];
      //   $j = 0;
      //   $cmb_store = "";
      //   $c1 = ($row['fixon_000001_cmb_codigo']);
      //   $c2 = ($row['fixon_000001_cmb_descri']);
      //   foreach ($rows2 as $row2){
      //     $campo[$i]['store'][$j]['cod'] = $row2[$c1];
      //     $campo[$i]['store'][$j]['value'] = ($row2[$c2]);
      //     $campo[$i]['store'][$j]['selected'] = '';
      //     $j++;
      //   }
      // }
      if(!$campo[$i]['black']){
        $name = $campo[$i]["name"];
        $rules[$name]['required'] = true;
        $r++;
      }
      $i++;
    }
  }
  $tabela = $tb['rows'][0]['fixon_000001_tabela'];
  $tabela_name = fixon_prefix(true).$tb['rows'][0]['fixon_000001_tabela'];
  $tabela_cliente = fixon_prefix(false).$tb['rows'][0]['fixon_000001_tabela'];
  $tabela_campo = $tb['rows'][0]['fixon_000001_tabela'];

  $grupo = '';//$modulo_conf['grupo'];
  // if(!$grupo) $grupo = get_grupo_id($md);
  $sql = "select ";
  for ($i=0;$i<count($campo);$i++){
    if($i>0) $sql .= ',';
    $sql .= $campo[$i]["name"];
  }

  $de_sistema = '';//($modulo_conf['de_sistema']=='s') ? true : false;
  $tabela_cliente = fixon_prefix($de_sistema).$md;

  $sql .= ' from '.$tabela_cliente." ";
  $sql .= "where ";
  $sql .= $tabela_campo."_codigo = ".$cod." ";
  $tb = fixon_db_data($sql,'rows',$cnn);
  $r=0;
  for ($i=0;$i<count($campo);$i++){
    $ccampo = ($campo[$i]["name"]);
    $value = isset($tb['rows'][0][$ccampo]) ? $tb['rows'][0][$ccampo] : '';
    $type = $campo[$i]["type"];
    $xtype = $campo[$i]['xtype'];
    if($campo[$i]["xtype"]=='combobox'){
      for ($ii=0;$ii<count($campo[$i]["store"]);$ii++){
        if($campo[$i]["store"][$ii]['cod']==$value){
          $campo[$i]["store"][$ii]['selected'] = 'selected';
        }
      }
    }
    if(($type=='text') || ($type=='string')){
      $value = ($value);
    }
    if($type=='float'){
      $value = fixon_moeda_br($value);
    }
    if($type=='date'){
      if($value=='null'){
        $value = '';
      }else{
        $value = fixon_date_mysql_br($value);
      }
    }
    $campo[$i]["value"] = $value;
  }
  $ret_md_edit['campo'] = $campo;
  $ret_md_edit['rules'] = $rules;
  return $ret_md_edit;
}



function fixon_md_view($md,$cod,$cnn,$df){
  global $wpdb;

  $ret_md_edit = array();
  $campo = array();
  $rules = array();
  $ret_md_edit['campo'] = $campo;
  $ret_md_edit['rules'] = $rules;
  $modulo_conf = fixon_get_modulo_conf($md);
  $sql = "select * from ".fixon_prefix(true)."fixon_000001 where fixon_000001_tabela = '".$md."' and fixon_000001_ativo = 's' order by fixon_000001_ordem";
  
  // echo '<pre>';
  // // print_r();
  // echo '</pre>';
  // die('__die__fixon_md_view');


  $tb = fixon_db_exe($sql,'rows');
  $rows = $tb['rows'];
  // echo '<pre>';
  // print_r($rows);

  // echo '<pre>';
  // print_r($tb);
  // echo '</pre>';
  // die('__die__fixon_md_view');

  $items = array();
  $i=0;
  $r=0;
  foreach ($rows as $row){


    $vai = fixon_select_vai($row['fixon_000001_ctr_view'],'view');
    if($vai) {
      if($row['fixon_000001_renderer']){
        // $vai = $row['fixon_000001_renderer'];
        $vai = wpmsc_role_logic($row['fixon_000001_renderer']);
      }

      //fixon_000001_renderer
    }


    if($vai){
      $campo[$i]["inputId"]   = $row['fixon_000001_campo'];
      $campo[$i]["type"]      = $row['fixon_000001_tipo'];
      $campo[$i]["name"]      = $row['fixon_000001_campo'];
      $campo[$i]["xtype"]     = strtolower($row['fixon_000001_ctr_edit']);
      $campo[$i]["fieldLabel"]  = (($row['fixon_000001_label']));
      $campo[$i]['value']     = '';
      $campo[$i]['black']     = $row['fixon_000001_black'];
      $campo[$i]['url']       = $row['fixon_000001_url'];
      $campo[$i]['url_painel']= $row['fixon_000001_url_painel'];
      // if($campo[$i]["xtype"]=='textfield') $campo[$i]["type"] = 'text';
      // if($campo[$i]["xtype"]=='combobox'){
      //   $campo[$i]["type"] = 'select';
      //   $sql2  = "select ";
      //   $sql2 .= $row['fixon_000001_cmb_codigo'].", ".$row['fixon_000001_cmb_descri']." ";
      //   $sql2 .= "from ".$row['fixon_000001_cmb_source']." ";
      //   $sql2 .= "order by ".$row['fixon_000001_cmb_descri']." ";
      //   $sql2 .= " ;";
      //   $tb2 = fixon_db_exe($sql2,'rows');
      //   $rows2 = $tb2['rows'];
      //   $j = 0;
      //   $cmb_store = "";
      //   $c1 = ($row['fixon_000001_cmb_codigo']);
      //   $c2 = ($row['fixon_000001_cmb_descri']);
      //   foreach ($rows2 as $row2){
      //     $campo[$i]['store'][$j]['cod'] = $row2[$c1];
      //     $campo[$i]['store'][$j]['value'] = ($row2[$c2]);
      //     $campo[$i]['store'][$j]['selected'] = '';
      //     $j++;
      //   }
      // }
      if(!$campo[$i]['black']){
        $name = $campo[$i]["name"];
        $rules[$name]['required'] = true;
        $r++;
      }
      $i++;
    }
  }
  $tabela = $tb['rows'][0]['fixon_000001_tabela'];
  $tabela_name = fixon_prefix(true).$tb['rows'][0]['fixon_000001_tabela'];
  $tabela_cliente = fixon_prefix(false).$tb['rows'][0]['fixon_000001_tabela'];
  $tabela_campo = $tb['rows'][0]['fixon_000001_tabela'];
  // $grupo = $modulo_conf['grupo'];
  // if(!$grupo) $grupo = get_grupo_id($md);

// echo $tabela;
  $de_sistema = '';//($modulo_conf['de_sistema']=='s') ? true : false;
  $tabela_cliente = fixon_prefix($de_sistema).$md;

  $col_replace = $df['col_replace'];
  // echo $col_replace;
  // exit;

  
  if($col_replace){
    $resplace = explode(",", $col_replace);
    foreach ($resplace as $keyc => $valuec) {
      $arrray = explode(":", $valuec);
      foreach ($campo as $key => $value) {
        if ($value['name']==$arrray[0]) {
          $campo[$key]['name'] = $arrray[1];
          $campo[$key]['type'] = 'string';
        }
      }
    }
  }

  $sql = "select ";
  for ($i=0;$i<count($campo);$i++){
    if($i>0) $sql .= ',';
    $sql .= $campo[$i]["name"];
  }
  $sql .= ' from '.$tabela_cliente." ";
  $sql .= ' '.$df['inner']." ";

  

  

  $sql .= "where ";
  $sql .= $tabela_campo."_codigo = ".$cod." ";

  $tb = fixon_db_data($sql,'rows',$cnn);
  
  $r=0;
  for ($i=0;$i<count($campo);$i++){
    $ccampo = ($campo[$i]["name"]);
    $value = isset($tb['rows'][0][$ccampo]) ? $tb['rows'][0][$ccampo] : '';
    $type = $campo[$i]["type"];
    $xtype = $campo[$i]['xtype'];
    if($campo[$i]["xtype"]=='combobox'){
      for ($ii=0;$ii<count($campo[$i]["store"]);$ii++){
        if($campo[$i]["store"][$ii]['cod']==$value){
          $campo[$i]["store"][$ii]['selected'] = 'selected';
        }
      }
    }
    if(($type=='text') || ($type=='string')){
      $value = ($value);
    }
    if($type=='float'){
      $value = fixon_moeda_br($value);
    }
    if($type=='date'){
      if($value=='null'){
        $value = '';
      }else{
        $value = fixon_date_mysql_br($value);
        // fixon_date_mysql_br
      }
    }


    $campo_codigo = $tabela_campo.'_codigo';
    if($ccampo==$campo_codigo){
      $campo[$i]["value"] = str_pad($campo[$i]["value"], 6, "0", STR_PAD_LEFT);
    }




    $campo[$i]["value"] = $value;
  }



  //troca url - ini
  $tabela = $md;
  $tabela_name = fixon_prefix(true).$tabela;
  $tabela_cliente = fixon_prefix(false).$tabela;
  $tabela_campo = $tabela;

  // echo '<pre>';
  // print_r($campo);

  $campo_codigo = $tabela_campo.'_codigo';
  for ($i=0;$i <  count($campo); $i++){
    if($campo[$i]['name'] == $campo_codigo){
      $codigo = $campo[$i]['value'];
    }
  }

  for ($i=0;$i<count($campo);$i++){
    if($campo[$i]['url']){

      $url_painel = $campo[$i]['url_painel'];
      $vai = 0;
      if($url_painel){
        $vai = wpmsc_role_logic($url_painel);
      }
      if($vai){



        $value = $campo[$i]['url'];
        // $value = html_entity_decode($campo[$i]["value"]);
        // $value = preg_replace("/__tcod__/i",  $rows['row'][$i][$campo_codigo], $value);
        $value = preg_replace("/__cod__/i",  $codigo, $value);
        $value = preg_replace("/__xxx__/i",  '__yyy__', $value);
        $value = preg_replace("/__this__/i", $campo[$i]["value"], $value);
        $value = preg_replace("/__pai__/i",  fixon_get_pai(), $value);
        // $value = html_entity_decode($value);
        for ($iii=0;$iii <  count($campo); $iii++){
          $campoiii = strtolower($campo[$iii]["name"]);
          if (preg_match("/__".$campoiii."__/i", $value)) {
            $value = preg_replace("/__".$campoiii."__/i", $campo[$iii]["value"], $value);
          }
        }
        $campo[$i]["value"] = $value;
      }
    }
  }
  //troca url - end
  $ret_md_edit['campo'] = $campo;
  $ret_md_edit['rules'] = $rules;
  return $ret_md_edit;
}


function fixon_md_insert($md,$values=array(),$cnn,$insert_add){
  global $wpdb;


  // if($insert_add){
  //   echo $insert_add;
  //   die();
  // }


  $modulo_conf = fixon_get_modulo_conf($md);
  $sql = "select * from ".fixon_prefix(true)."fixon_000001 where fixon_000001_tabela = '".$md."' and fixon_000001_ativo = 's' order by fixon_000001_ordem ";
  $tb = fixon_db_exe($sql,'rows');
  $rows = $tb['rows'];
  // echo '<pre>';
  // print_r($rows);
  // echo '</pre>';
  $i=0;
  $campo = array();
  foreach ($rows as $row){
    $vai = fixon_select_vai($row['fixon_000001_ctr_new'],'novo');
    if($vai){
      $name = $row['fixon_000001_campo'];
      // if(isset($values[$name])){
        $campo[$i]['name']    = $row['fixon_000001_campo'];
        $campo[$i]['type']    = $row['fixon_000001_tipo'];
        $campo[$i]['value']   = isset($values[$name]) ? sanitize_text_field($values[$name]) : '';
        $campo[$i]['xtype']   = strtolower($row['fixon_000001_ctr_new']);

        if($row['fixon_000001_tipo']=='file'){

        }
        $i++;
      // }
    }
  }
  // echo '<pre>';
  // print_r($campo);
  // echo '</pre>';

  $modulo_conf = fixon_get_modulo_conf($md);
  $tabela = fixon_prefix(true).$md;
  $tabela_cliente = fixon_prefix(false).$md;
  // $de_sistema = ($modulo_conf['fixon_000002_de_sistema']=='s') ? true : false;
  $i_old = $i;
  for ($i=0;$i<$i_old;$i++){
    if(($campo[$i]['xtype']=="checkbox") && ($campo[$i]['type']=='string')){
      if(!$campo[$i]['value']) {
        $campo[$i]['value'] = 'N';
      } else {
        $campo[$i]['value'] = 'S';
      }
    }
    if(($campo[$i]['xtype']=="checkbox") && ($campo[$i]['type']=='int')){
      if(!$campo[$i]['value']) {
        $campo[$i]['value'] = 0;
      } else {
        $campo[$i]['value'] = 1;
      }
    }
    if($campo[$i]['type']=='date'){
      if(!$campo[$i]['value']){
        $campo[$i]['value'] = 'null';
      }else{
        $campo[$i]['value'] = fixon_date_br_php($campo[$i]['value']);
        $campo[$i]['value'] = "'".$campo[$i]['value']."'";
      }
    }
    // if($campo[$i]['type']=='blob')    $campo[$i]['value'] = "'".scm_utf8_to_win1252($campo[$i]['value'])."'";
    if($campo[$i]['type']=='blob')    $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
    if(($campo[$i]['type']=='string') || ($campo[$i]['type']=='varchar')){
      $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
      $de_sistema = '';//$modulo_conf['de_sistema'];
    }
    if($campo[$i]['type']=='file'){
      $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
      $de_sistema = $modulo_conf['de_sistema'];

$filelame = $campo[$i]['name'];
// echo '---'.ABSPATH.'-----';
$uploaddir = ABSPATH.'/uploads/';
// get_home_path() 

$extensao_t = explode('.', $_FILES[$filelame]['name']); 
// echo '<pre>';
// print_r($extensao_t);
// echo '</pre>';
$extensao_c = count($extensao_t);
$extensao_c = $extensao_c -1;
// echo '<pre>';
// echo '<h1>extensao_c: '.$extensao_c.'</h1>';
// echo '</pre>';

$extensao = $extensao_t[$extensao_c] ;
$extensao = strtolower($extensao);
if($extensao){
  $vai = 0;
  if($extensao=='png') $vai = 1;
  if($extensao=='jpg') $vai = 1;
  if(!$vai) {
    echo '<div style="color:red;"><h3>TIPO DE ARQUIVO NÃO PERMITIDO</h3></div>';
    die();
  }
}
// echo '<pre>';
// echo '<h1>extensao: '.$extensao.'</h1>';
// echo '</pre>';


// $uploadfile = $uploaddir . basename($_FILES[$filelame]['name']);
$gera_senha = fixon_gera_senha();
$uploadfile = $uploaddir . $gera_senha.'.'.$extensao ;
// echo '<h1>uploadfile: '.$uploadfile.'</h1>';

// echo '<h1>---'.$filelame.'----</h1>';
// echo '<h1>---'.$_FILES['userfile']['tmp_name'].'----</h1>';

// echo '<pre>';
// print_r($_FILES);
// echo '</pre>';
$url_foto = '<img src="'.site_url().'/uploads/'.$gera_senha.'.'.$extensao.'">'; //'.$gera_senha.'.'.strtolower($extensao);

if (move_uploaded_file($_FILES[$filelame]['tmp_name'], $uploadfile)) {
    echo "Arquivo válido e enviado com sucesso.\n";
    $campo[$i]['value'] = "'".$url_foto."'";
    // $url_foto
} else {
    echo "Nao foi possível fazer o upload do arquivo!\n";
}

    }

    if($campo[$i]['type']=='int')   {if(!$campo[$i]['value']) $campo[$i]['value'] = 0;}
    if($campo[$i]['type']=='float')   {
      if(!$campo[$i]['value']) $campo[$i]['value'] = 0;
      $campo[$i]['value'] = fixon_moeda_br_to_us($campo[$i]['value']);
    }
  }
  $c = $i;

  $insadd_key ='';
  $insadd_value = '';

  $de_sistema = '';//($modulo_conf['de_sistema']=='s') ? true : false;
  $tabela_cliente = fixon_prefix($de_sistema).$md;

    if($insert_add){
      $insadd = explode(",", $insert_add);
      $insadd_i = 0;
      foreach ($insadd as $key => $value) {
        $insadditem = explode("=", $value);
          $insadd_key .= '';
         $insadd_value .= '';
         $insadd_key .= $insadditem[0];
        $insadd_value .= $insadditem[1];
        $insadd_i++;
      }
    }


  $sql = "insert into ".$tabela_cliente." ";
  $sql_insert = '';
  $sql_values = '';
  for ($i=0;$i<count($campo);$i++){
    if($i > 0){
      $sql_insert .= ",";
      $sql_values .= ",";
    }
    $sql_insert .= $campo[$i]['name'];
    $sql_values .= $campo[$i]['value'];
  }
  // if(($modulo_conf['grupalizar']) && ($modulo_conf['conexao'] >=2)){
  //   $sql_insert .= ", ".$modulo_conf['tabela'].'_id_sysempresa ';
  //   $sql_insert .= ", ".$modulo_conf['tabela'].'_id_sysusuario ';
  //   $sql_values .= ", ".get_grupo_id($md);
  //   $sql_values .= ", ".get_membro_codigo($md);
  // }

  $sql_insert .= $insadd_key;
  $sql_values .= $insadd_value;


  $sql .= "(".$sql_insert.")";
  $sql .= " values ";
  $sql .= "(".$sql_values.")";
  $ret = fixon_db_data($sql,'insert',$cnn);

  // if($insert_add){
    // echo $sql;
    // die();
  // }
}




function fixon_md_update($md,$cod,$cnn){
  global $wpdb;
  $sql = "select * from ".fixon_prefix(true)."fixon_000001 where ((fixon_000001_tabela = '".$md."' ) and  ( fixon_000001_ativo = 's' )) order by fixon_000001_ordem ";
  $tb = fixon_db_exe($sql,'rows');
  $return_update = array();
  $i=0;
  $campo = array();
  $rows = $tb['rows'];

  foreach ($rows as $row){
    $vai = fixon_select_vai($row['fixon_000001_ctr_edit'],'edit');
    if($vai){
      $name = $row['fixon_000001_campo'];
      $nameU = strtoupper($name);
      $nameL = strtolower($name);
      $vai2 = false;
      $vai2 = isset($_REQUEST[$nameL]) ? true : false;
      if($vai2){
        $campo[$i]['name'] = $row['fixon_000001_campo'];
        $campo[$i]['type']    = $row['fixon_000001_tipo'];
        $campo[$i]['value']   = sanitize_text_field($_REQUEST[$name]);
        $i++;
      }
    }
  }
  $modulo_conf = fixon_get_modulo_conf($md);
  $tabela = $md;
  $tabela_name = fixon_prefix(true).$tabela;
  $tabela_cliente = fixon_prefix(false).$tabela;
  $tabela_campo = $tabela;

  $i_old = $i;
  for ($i=0;$i<$i_old;$i++){
    if($campo[$i]['type']=='date'){
        $campo[$i]['value'] = fixon_date_br_php($campo[$i]['value']);
        $campo[$i]['value'] = "'".$campo[$i]['value']."'";
    }

    if($campo[$i]['type']=='blob')    $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
    if(($campo[$i]['type']=='string') || ($campo[$i]['type']=='varchar')){
      $de_sistema = '';//$modulo_conf['de_sistema'];
      $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
    }
    if(($campo[$i]['type']=='file')){
      $de_sistema = $modulo_conf['de_sistema'];
      $campo[$i]['value'] = "'".($campo[$i]['value'])."'";
    }

    if($campo[$i]['type']=='int')   {if(!$campo[$i]['value']) $campo[$i]['value'] = 0;}
    if($campo[$i]['type']=='float')   {
      if(!$campo[$i]['value']) $campo[$i]['value'] = 0;
      $campo[$i]['value'] = fixon_moeda_br_to_us($campo[$i]['value']);
    }
    if($campo[$i]['type']=='float')   {$campo[$i]['value'] =  fixon_moeda_br_to_us($campo[$i]['value']);}
  }
  $return_update['campo'] = $campo;

  $de_sistema = '';//($modulo_conf['de_sistema']=='s') ? true : false;
  $tabela_cliente = fixon_prefix($de_sistema).$md;

  $sql = "update ".$tabela_cliente." set ";
  for ($i=0;$i<count($campo);$i++){
    if($i>0) $sql .=", ";
    $sql .= $campo[$i]['name'].' = '.$campo[$i]['value'];
  }
  $sql .= " where ".$tabela_campo."_codigo = ".$cod." ";

  $ret = fixon_db_data($sql,'update',$cnn);
  return  $ret;
}


function fixon_md_delete($md,$cod){
  global $wpdb;
  $modulo_conf = fixon_get_modulo_conf($md);
  $tabela = $md;
  $tabela_name = fixon_prefix(true).$tabela;
  $tabela_cliente = fixon_prefix(false).$tabela;
  $tabela_campo = $tabela;
  $sql = "delete from ".$tabela_cliente." where ".$tabela_campo."_codigo = ".$cod.";";
  return fixon_db_data($sql,'delete');
}


function fixon_md_duplique($md,$cod,$cnn){
  $md_edit = fixon_md_edit($md,$cod,$cnn);
  $campos = $md_edit['campo'];

  $values = array();
  for ($i=0; $i < count($campos); $i++) {
    $campo = $campos[$i]['name'];
    $value = $campos[$i]['value'];
    $values[$campo] = $value;
  }
  fixon_md_insert($md,$values,$cnn);
  return $values;

}