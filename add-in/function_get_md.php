<?php

function fixon_get_md_novo($md){
  $ret_md_novo = array();
  $campo = array();
  $rules = array();
  $ret_md_novo['campo'] = $campo;
  $ret_md_novo['rules'] = $rules;
  $rows = fixon_get_fields($md);

  $i=0;
  $r=0;
  if(count($rows)){
    foreach ($rows as $row){
      $vai = fixon_select_vai($row['ctr_new'],'novo');
      if($vai){
        $campo[$i]["inputId"] = strtolower($row['name']);
        $campo[$i]["type"] = $row['tipo'];
        $campo[$i]["name"] = $row['name'];
        // $campo[$i]["ctr_new"] = $row['fixon_000001_ctr_new'];
        $campo[$i]["value"] = '';
        $campo[$i]["cls"] = $row['cls'];
        $campo[$i]["xtype"] = strtolower($row['ctr_new']);
        // $campo[$i]["fieldLabel"]  = strtoupper(($row['label']));
        $campo[$i]["fieldLabel"]  = (($row['label']));
        $campo[$i]['width'] = 550;
        $campo[$i]['black'] = ($row['black']) ? true : false;
        $campo[$i]['placeholde'] = '';//novo
        if($campo[$i]["xtype"]=='textfield') $campo[$i]["type"] = 'text';
        if($campo[$i]["xtype"]=='combobox'){
          $campo[$i]["type"] = 'select';
          $sql2  = "select  ".$row['cmb_codigo'].", ".$row['cmb_descri']." ";
          $sql2 .= "from ".$row['cmb_source']." ";
          $sql2 .= "order by ".$row['cmb_descri']." ";
          $sql2 .= " ;";

          $campo[$i]["sql_combo"] = $sql2;

          $tb2 = fixon_db_data($sql2,'rows');
          $rows2 = $tb2['rows'];

          $j = 1;
          $cmb_store = "";
          $c1 = ($row['cmb_codigo']);
          $c2 = ($row['cmb_descri']);
          $campo[$i]['store'][0]['cod'] = '';
          $campo[$i]['store'][0]['value'] = '';
          $campo[$i]['store'][0]['selected'] = 'selected';
          foreach ($rows2 as $row2){
            $campo[$i]['store'][$j]['cod'] = $row2[$c1];
            $campo[$i]['store'][$j]['value'] = ($row2[$c2]);
            $campo[$i]['store'][$j]['selected'] = '';
            $j++;
          }
        }
        if(!$campo[$i]['black']){
          $name = $campo[$i]["name"];
          $rules[$name]['required'] = true;
          $r++;
        }
        $i++;
      }
    }
    $ret_md_novo['campo'] = $campo;
    $ret_md_novo['rules'] = $rules;
  }
  return $ret_md_novo;
}




function fixon_get_list($mds){
  $mds = fixon_get_md_col($mds);
  $mds = fixon_get_md_rows($mds);
  return $mds;
}


function fixon_get_md_col($md,$cnn='',$df=array()){
  $sql = "
  select
    fixon_000001_codigo,
    fixon_000001_ctr_list,
    fixon_000001_campo,
    fixon_000001_tipo,
    fixon_000001_label,
    fixon_000001_largura,
    fixon_000001_tabela,
    fixon_000001_cmb_source,
    fixon_000001_cmb_descri,
    fixon_000001_hidden
  from ".fixon_prefix(true)."fixon_000001
  where fixon_000001_tabela = '".$md."' 
  order by fixon_000001_ordem
  ";
  $tb = fixon_db_exe($sql,'rows');
  $rows = $tb['rows'];
  $tabela = $md;
  $tabela_name = fixon_prefix(true).$tabela;
  $tabela_cliente = fixon_prefix(false).$tabela;
  $tabela_campo = $tabela;
  $col = array();
  $c=0;
  for ($i=0;$i<$tb['r'];$i++) {
    $vai = fixon_select_vai($rows[$i]['fixon_000001_ctr_list'],'list');
    if($vai){
      $col[$c]["cd"]=$rows[$i]['fixon_000001_codigo'];
      $col[$c]["codigo_name"]=$tabela.'_codigo';
      $col[$c]["text"]=($rows[$i]['fixon_000001_label']);
      // $col[$c]["text"] = strtoupper($col[$c]["text"]);
      $col[$c]["text"] = ($col[$c]["text"]);
      $col[$c]["dataIndex"]=$rows[$i]['fixon_000001_campo'];
      $col[$c]["width"]=$rows[$i]['fixon_000001_largura'];
      $col[$c]["hidden"]= ($rows[$i]['fixon_000001_hidden']==1) ? true : false;
      if($col[$c]["width"]) $col[$c]["width"] = $col[$c]["width"] * 1.5;
      $col[$c]["filter_type"]=$rows[$i]['fixon_000001_tipo'];
      $col[$c]["filter"]['type']=$rows[$i]['fixon_000001_tipo'];
      if($rows[$i]['fixon_000001_tipo']=='int') {
        $col[$c]["filter_type"] = 'numeric';
        $col[$c]["filter"]['type']  = 'numeric';
      }
      $col[$c]["inner"] = '';
      $col[$c]["ctr_list"] = $rows[$i]['fixon_000001_ctr_list'];
      if($rows[$i]['fixon_000001_ctr_list']=='combobox'){
        $col[$c]["dataIndex"] = $rows[$i]['fixon_000001_cmb_descri'];
        $col[$c]["type"] = 'string';
        $col[$c]["align"] = 'left';
        $col[$c]["filter_type"] = 'string';
        $col[$c]["filter"]['type']  = 'string';
        $len2 = strlen($rows[$i]['fixon_000001_tabela']);$len2++;
        $cp_fk2 = substr($rows[$i]['fixon_000001_campo'],$len2);
        $inner0 = $rows[$i]['fixon_000001_cmb_source'];
        $inner1 = $rows[$i]['fixon_000001_tabela'].".".$rows[$i]['fixon_000001_tabela']."_".$cp_fk2;
        $inner2 = $rows[$i]['fixon_000001_cmb_source'].".".$rows[$i]['fixon_000001_cmb_source']."_codigo";
        $inner = "inner join ".$inner0." on (".$inner1." = ".$inner2.") ";
        $col[$c]["inner"] = $inner;
      }
      $c++;
    }
  }
  


//codigo_name:text:
  if($df['col_add']){
    $col_add_a=explode(",", $df['col_add']);
    foreach ($col_add_a as $key => $value) {
      $tmp_field_config = explode(":", $value);

      $col[$c]['codigo_name'] = $tmp_field_config[0];// $value;
      $col[$c]['text'] = $tmp_field_config[2];
      $col[$c]['dataIndex'] = $tmp_field_config[0];
      $col[$c]['width'] = '';
      $col[$c]['hidden'] = '';
      $col[$c]['filter_type'] = 'string';//'numeric';//$tmp_field_config[3];//
      $col[$c]['filter']['type'] = 'string';//'numeric';//$tmp_field_config[3];//
      $col[$c]['inner'] = '';
      $col[$c]['ctr_list'] = 'label';
      $c++;
    }
  }
  return $col;
}


function fixon_get_fields($md, $cnn='', $df=array()){
  $fields = array();
  $sql = "
  select
    fixon_000001_codigo,
    fixon_000001_ctr_new,
    fixon_000001_ctr_list,
    fixon_000001_campo,
    fixon_000001_tipo,
    fixon_000001_formato,
    fixon_000001_cmb_descri,
    fixon_000001_tabela,
    fixon_000001_url,
    fixon_000001_url_md,
    fixon_000001_url_op,
    fixon_000001_label,
    fixon_000001_black,
    fixon_000001_url_painel,
    fixon_000001_cls

  from ".fixon_prefix(true)."fixon_000001 where fixon_000001_tabela = '".$md."'  and fixon_000001_ativo = 's' order by fixon_000001_ordem";

  $tb = fixon_db_exe($sql,'rows');

  $rows = $tb['rows'];
  $c = 0;
  for ($i=0;$i<$tb['r'];$i++){
    $vai = fixon_select_vai($rows[$i]['fixon_000001_ctr_list'],'list');
    if($vai){
      $fields[$c]['name'] = $tb['rows'][$i]['fixon_000001_campo'];
      $fields[$c]['type'] = $tb['rows'][$i]['fixon_000001_tipo'];
      $fields[$c]['ctr_new'] = $tb['rows'][$i]['fixon_000001_ctr_new'];

      $fields[$c]['url_painel'] = $tb['rows'][$i]['fixon_000001_url_painel'];

      if($fields[$c]['type']=='date'){
        $fields[$c]['dateFormat'] = 'Y-m-d';
      }
      $fields[$c]['formato']  = $tb['rows'][$i]['fixon_000001_formato'];
      if($rows[$i]['fixon_000001_ctr_list']=='combobox'){
        $fields[$c]['type'] = 'string';
        $fields[$c]["name"] = $rows[$i]['fixon_000001_cmb_descri'];
        $fields[$c]["filter"]['type']='string';
        $len2 = strlen($rows[$i]['fixon_000001_tabela']);$len2++;
        $cp_fk2 = substr($rows[$i]['fixon_000001_campo'],$len2);
      }
      $fields[$c]["url"]      = $rows[$i]['fixon_000001_url'];
      $fields[$c]["url_md"]   = $rows[$i]['fixon_000001_url_md'];
      $fields[$c]["url_op"]   = $rows[$i]['fixon_000001_url_op'];
      $fields[$c]["cls"]   = $rows[$i]['fixon_000001_cls'];
      $fields[$c]["tipo"]   = $rows[$i]['fixon_000001_tipo'];
      $fields[$c]["label"]   = $rows[$i]['fixon_000001_label'];
      $fields[$c]["black"]   = $rows[$i]['fixon_000001_black'];
      $fields[$c]["url_vai"]    = false;
      if($fields[$c]['url_md']){
        if($fields[$c]['url_op']){
          $fields[$c]["type"]     = 'string';
          $url_op = $fields[$c]['url_op'];
          $url_access = get_access($fields[$c]['url_md']);
          $url_access_op = isset($url_access[$url_op]) ? $url_access[$url_op] : false;
          if($url_access_op) $url_vai = $fields[$c]["url_vai"] = true;
        }
      }
      $c++;
    }
  }


  if(isset($df['col_add'])){
    if($df['col_add']){
      $col_add_a=explode(",", $df['col_add']);
      foreach ($col_add_a as $key => $value) {
        $tmp_field_config = explode(":", $value);
        $fields[$c]["name"] = $tmp_field_config[0];//$value;
        $fields[$c]["type"] = "string";//$tmp_field_config[4];//
        $fields[$c]["ctr_new"] = "numberfield";
        $fields[$c]["url_painel"] = '';
        $fields[$c]["formato"] = '';
        $fields[$c]["url"] = '';
        $fields[$c]["url_md"] = '';
        $fields[$c]["url_op"] = '';
        $fields[$c]["cls"] = '';
        $fields[$c]["tipo"] = "int";//$tmp_field_config[4];//
        $fields[$c]["label"] = "qtrrrd";
        $fields[$c]["black"] = 1;
        $fields[$c]["url_vai"] = '';
        $c++;
      }
    }
  }
  return $fields;
}


function fixon_get_modulo_conf($md){

  $sql = "select * from ".$GLOBALS['wpdb']->prefix."fixon_000002  where fixon_000002_tabela  = '".$md."' ;";
  $ret = array();
  $tb = fixon_db_exe($sql,'rows');

  if($tb['r']){
    $ret['sql_ordem']       = $tb['rows'][0]['fixon_000002_sql_sort'];
    $ret['sql_dir']       = $tb['rows'][0]['fixon_000002_sql_dir'];
    $ret['tabela']        = $tb['rows'][0]['fixon_000002_tabela'];
    $ret['limit']     =    20;//= ($tb['rows'][0]['fixon_000002_sql_limit']) ? $tb['rows'][0]['fixon_000002_sql_limit'] : 20;
    $ret['show_col_title']  =  '';//= $tb['rows'][0]['fixon_000002_show_col_title'];
  }
  return $ret;
}


function fixon_get_md_rows($md, $fields, $col, $df=array(),$cnn=""){
  global $wpdb;
  
  $udir = wp_upload_dir();
  $rows = array();
  $sql_ordem = '';
  $modulo_conf = fixon_get_modulo_conf($md);

  // $grupo = $modulo_conf['grupo'];
  $user = '';//$modulo_conf['user'];
  $tabela = $modulo_conf['tabela'];
  $tabela_name = fixon_prefix(true).$tabela;
  $tabela_cliente = fixon_prefix(false).$tabela;
  $tabela_campo = $tabela;

  $limit = $modulo_conf['limit'] ? $modulo_conf['limit'] : 20 ;
  $sort = array();
  $start = 0;
  $wh = '';
  for ($i=0; $i < count($col); $i++) {
    $campo = $col[$i]['dataIndex'];
    $value = isset($_REQUEST[$campo]) ? sanitize_text_field($_REQUEST[$campo]) : '';
    if($value){
      if($col[$i]['tipo'] == 'int'){
        $value = "'".($value)."'"; 
      }
      $wh .= ' and '.$campo." = ". $value." ";
    }
  }
  if(isset($_REQUEST['start']) ? sanitize_text_field($_REQUEST['start']) : 0) $start = $_REQUEST['start'];
  if(isset($_REQUEST['limit']) ? sanitize_text_field($_REQUEST['limit']) : 0) $limit = $_REQUEST['limit'];
  $sort = isset($_REQUEST['sort']) ? sanitize_text_field($_REQUEST['sort']) : '';
  if($sort){
    $sql_ordem = 'order by '.$sort;
  }
  $sql_ordem = 'order by '.$md."_codigo DESC";
  $filters = isset($_REQUEST['filter']) ? sanitize_text_field($_REQUEST['filter']) : null;
  if (is_array($filters)) {
      $encoded = false;
  } else {
      $encoded = true;
      $filters = json_decode($filters);
  }
  // criterio - ini
  $crit_e = array();
  $crit_cp = array();
  $crit_sql = '';
  $i=0;
  $criterio = isset($df['criterio']) ? $df['criterio'] : '';
  if($criterio){
    $criterio = base64_decode($criterio);
    $crit_e = explode("&", $criterio);
    foreach($crit_e as $value){
      $values = explode("=", $value);
      $crit_cp[$i]['campo'] = $values[0];
      $crit_cp[$i]['value'] = '"'.$values[1].'"';
      if($i) $crit_sql .=" and ";
      $operad = "=";
      $crit_sql .= $crit_cp[$i]['campo']." ".$operad." ".$crit_cp[$i]['value'];
      $i++;
    }
    $crit_sql = " AND (".$crit_sql.") ";
  }
  $rows['crit_sql'] = $crit_sql;
  // criterio - end

  $where = ' 0 = 0 ';
  $where .= $wh;
  $where .= $crit_sql;
  $qs = '';
  // -- filters  -- ini
  if (is_array($filters)) {
      for ($i=0;$i<count($filters);$i++){
          $filter = $filters[$i];
          if ($encoded) {
              $field = $filter->field;
              $value = $filter->value;
              $compare = isset($filter->comparison) ? $filter->comparison : null;
              $filterType = $filter->type;
          } else {
              $field = $filter['field'];
              $value = $filter['data']['value'];
              $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
              $filterType = $filter['data']['type'];
          }
          switch($filterType){
              case 'string' : $qs .= " and ".$field." like '%".$value."%'"; Break;
              case 'list' :
                  if (strstr($value,',')){
                      $fi = explode(',',$value);
                      for ($q=0;$q<count($fi);$q++){
                          $fi[$q] = "'".$fi[$q]."'";
                      }
                      $value = implode(',',$fi);
                      $qs .= " and ".$field." in (".$value.")";
                  }else{
                      $qs .= " and ".$field." = '".$value."'";
                  }
              Break;
              case 'boolean' : $qs .= " and ".$field." = ".($value); Break;
              case 'numeric' :
                $value = preg_replace("/__user__/i",  get_membro_codigo($md), $value);
                  switch ($compare) {
                      case 'eq' : $qs .= " and ".$field." = ".$value; Break;
                      case 'lt' : $qs .= " and ".$field." <= ".$value; Break;
                      case 'gt' : $qs .= " and ".$field." >= ".$value; Break;
                  }
              Break;
              case 'date' :
                  switch ($compare) {
                      case 'eq' : $qs .= " and ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
                      case 'lt' : $qs .= " and ".$field." <= '".date('Y-m-d',strtotime($value))."'"; Break;
                      case 'gt' : $qs .= " and ".$field." >= '".date('Y-m-d',strtotime($value))."'"; Break;
                  }
              Break;
          }
      }
      $where .= $qs;
  }
  // -- filters  -- end

  // TBARFILTER -- INI
  $tbarFilter = isset($_REQUEST['tbarFilter']) ? sanitize_text_field($_REQUEST['tbarFilter']) : '';
  if($tbarFilter){
    $filtro = '';
    for ($i=0;$i<count($fields);$i++){
      if($fields[$i]['type']=='string'){
        if($filtro) $filtro .= ' OR ';
        $filtro .= " ".$fields[$i]['name']." LIKE '%".$tbarFilter."%' ";
      }
    }
    $where .= ' and ('.$filtro.') ';
  }
  $busca = isset($_REQUEST['busca']) ? sanitize_text_field($_REQUEST['busca']) : '';
  if($busca){
    //SE TA BUSCANDO EM DETERMINADA COLUNA INDICADO PELO "COLUNA:TEXTO" - INI
    $if_busca_col = preg_match("/\:/", $busca);
    if($if_busca_col){
      $tmp0 = explode(":", $busca);
      $tmp_coluna = $tmp0[0];
      $tmp_value = $tmp0[1];
      $tmp_table_prefix = fixon_prefix(0);
      $tmp_table = $modulo_conf['tabela'];

      $where .= ' and ('.$tmp_table.'_'.$tmp_coluna.' = "'.$tmp_value.'") ';
      //SE TA BUSCANDO EM DETERMINADA COLUNA INDICADO PELO "COLUNA:TEXTO" - END
    }else{
      $filtro = '';
      for ($i=0;$i<count($fields);$i++){
        if(($fields[$i]['type']=='string') || ($fields[$i]['type']=='blob') || ($fields[$i]['type']=='varchar')){
          if($filtro) $filtro .= ' OR ';
          $filtro .= " ".$fields[$i]['name']." LIKE '%".$busca."%' ";
        }
      }
      $where .= ' and ('.$filtro.') ';
    }
  }
  // TBARFILTER -- END

  // ref_loc -- ini
  $ref_loc = isset($_REQUEST['ref_loc']) ? sanitize_text_field($_REQUEST['ref_loc']) : '';
  if($ref_loc=='undefined') $ref_loc = '';
  if($ref_loc){
    $filtro = '';
    $ff=0;
    for ($i=0;$i<count($fields);$i++){
      if($fields[$i]['type']=='string'){
        if($filtro) $filtro .= ' OR ';
        $filtro .= " ".$fields[$i]['name']." LIKE '%".$ref_loc."%' ";
        $ff++;
      }
    }
    if($ff){
      $where .= ' and ('.$filtro.') ';
    }
  }
  $i = 0;
  
  $sql_ordem = '';
  if($df["sql_order"]){
    $sql_ordem .= " order by ".$df["sql_order"];
  }
  if($df["sql_dir"]){
    $sql_ordem .= " ".$df["sql_dir"];
  }


  $field = '';
  for ($i=0;$i<count($fields);$i++){
    if($i>0) $field .= ',';
    $field .= $fields[$i]["name"];
  }
  $coluna = '';
  $inner = $df['inner'];
  
  for ($i=0;$i<count($col);$i++){
    if($i>0) $coluna .= ',';
    $coluna .= $col[$i]["dataIndex"];
  }
  $de_sistema = '';//($modulo_conf['de_sistema']=='s') ? true : false;
  $tabela_cliente = fixon_prefix($de_sistema).$md;
  $sql  = "";
  $sql .= "select ";
  $sql .= $coluna." ";
  if($df['col_add']){
    // $sql .= ', '.$df['col_add']." ";

  }
  $sql .= "from ".$tabela_cliente." ";
  
  $sql .= $inner." ";
  
  $sql .= " where ";
  $sql .= " ".$where;
  $sql .= $sql_ordem." ";
  $sql .= "limit ".$start.", ".$limit;
  $sql = preg_replace("/__user__/i",  get_current_user_id(), $sql);
  $sql = preg_replace("/__prefix__/i",  fixon_prefix(true), $sql);
  if($df['die_sql']){
    print($sql);
  }

  $tb = fixon_db_data($sql,'rows',$cnn);
  $rows['row'] = array();
  $campo_codigo = $tabela_campo.'_codigo';

  if((isset($tb['r'])) && ($tb['r']))
  for ($i=0;$i<$tb['r'];$i++){
    for ($ii=0;$ii<count($fields);$ii++){
      $campo = $col[$ii]['dataIndex'];
      $rows['row'][$i][$campo] = $tb['rows'][$i][($campo)];
      if($fields[$ii]['type']=='string'){
        $rows['row'][$i][$campo] =  strip_tags( $rows['row'][$i][$campo] );
        $rows['row'][$i][$campo] = ($rows['row'][$i][$campo]);//esse resolveu
      }
      if($fields[$ii]['type']=='date'){
        $rows['row'][$i][$campo] = fixon_date_mysql_br($rows['row'][$i][$campo]);
      }
      if($fields[$ii]['type']=='blob'){
        $rows['row'][$i][$campo] = ($rows['row'][$i][$campo]);//esse resolveu
      }
      if($col[$ii]['dataIndex']==$campo_codigo){
        $rows['row'][$i][$campo] = str_pad($rows['row'][$i][$campo], 6, "0", STR_PAD_LEFT);
      }
    }
  }




  //TROCA URL - INI
  $ret = "";

  $codigo = fixon_get_cod();
  if((isset($tb['r'])) && ($tb['r']))
  for ($i=0;$i<$tb['r'];$i++){
    for ($ii=0;$ii<count($fields);$ii++){
      $url_painel = $fields[$ii]['url_painel'];
      $vai = 1;
      if($url_painel){
        $vai = wpmsc_role_logic($url_painel);
      }
      if($vai){
        if($fields[$ii]['url']){
          $campo = $col[$ii]['dataIndex'];
          $value = $rows['row'][$i][$campo];
          $value = $fields[$ii]['url'];
          $campo_codigo = $tabela_campo.'_codigo';
          $value = html_entity_decode($value);
          $value = preg_replace("/__tcod__/i",  strip_tags($rows['row'][$i][$campo_codigo]), $value);
          $value = preg_replace("/__this_cod__/i",  $rows['row'][$i][$campo_codigo], $value);
          $value = preg_replace("/__cod__/i",  $rows['row'][$i][$campo_codigo], $value);
          $value = preg_replace("/__xxx__/i",  '__yyy__', $value);
          $value = preg_replace("/__codigo__/i",  $codigo, $value);
          $value = preg_replace("/__pai__/i",  fixon_get_pai(), $value);
          $value = preg_replace("/__this__/i", $rows['row'][$i][$campo], $value);
          $value = preg_replace("/__ucod__/", fixon_get_cod() , $value);
          $value = preg_replace("/__site_url__/",site_url() , $value);
          $value = preg_replace("/__upload_dir__/",$udir['baseurl'] , $value);

          $value = preg_replace("/__wpmsc_ajax_url__/",$url , $value);
          $value = preg_replace("/__wpmsc_class_url__/",$add_class , $value);

          $value = preg_replace("/__user__/i",  get_current_user_id(), $value);


          // $value = '--=--';
          $value = html_entity_decode($value);
          $rows['row'][$i][$campo] = $value;
          for ($iii=0;$iii<  count($fields); $iii++){
            $campoiii = strtolower($fields[$iii]["name"]);
            if (preg_match("/__".$campoiii."__/i", $value)) {
              $value = preg_replace("/__".$campoiii."__/i",   $rows['row'][$i][$campoiii]   , $value);
            }
          }
          $rows['row'][$i][$campo] = $value;
        }
      }
    }
  }
  //TROCA URL - END



//--- TOTAL



  $sql = "select count(".$col[0]["dataIndex"].") qtd ";
  $sql .= " from ".$tabela_cliente;
  $sql .= " ".$inner;
  $sql .= " where ";
  $sql .= $where;
  $sql = preg_replace("/__user__/i",  get_current_user_id(), $sql);
  $tb = fixon_db_data($sql,'rows',$cnn);
  $rows['total'] = isset($tb['rows'][0]['qtd']) ? $tb['rows'][0]['qtd'] : 0;
  $somas = array();
  for ($ii=0;$ii<count($fields);$ii++){
    $somas[$ii] = '-';
      // $field = strtoupper($fields[$ii]["name"]);
    $field = ($fields[$ii]["name"]);
  }
  $rows['db_host'] = get_the_author_meta('db_host', get_current_user_id());
  return $rows;
}



function fixon_get_pai(){
  return sanitize_text_field(isset($_GET['pai']) ? $_GET['pai'] : '');
}

function fixon_get_cod(){
   return sanitize_text_field(isset($_GET['cod']) ? $_GET['cod'] : ''); 
}

function fixon_get_op(){
  return sanitize_text_field(isset($_GET['op']) ? $_GET['op'] : '');  
}

function fixon_get_md(){
 return sanitize_text_field(isset($_GET['md']) ? $_GET['md'] : '');  
}

function fixon_get_busca(){
  return sanitize_text_field(isset($_GET['busca']) ? $_GET['busca'] : ''); 
}


function fixon_get_param($md){
  return "";
}