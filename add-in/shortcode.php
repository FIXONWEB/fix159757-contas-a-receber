<?php

function fixon_list($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "manut" => '0',
    "criterio" => '',
    "criterio2" => '',
    "style" => '',
    "class" => '',
    "on_op" => '',
    "title" => '',
    "access" => '',
    "role" => '',
    "un_show" => '',
    "config" => '',
    "join" => '',
    "inner" => '',
    "cnn" => '',
    "die_col" => '',
    "col_replace" => '',
    "die_sql" => '' ,
    "col_url" => '',
    "col_x0" => '',
    "col_add" => '',
    "sql_order" => '',
    "sql_dir" => '',
  ), $atts));
//col_add='depois_de|antes_de,coluna_name,label'
  if($access){if(!fixon_is_access($access)) return '';}
  if($role){ if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';

    }
  }

  $cfg = array();

  $busca = fixon_get_busca();
  if($busca){
    if(is_numeric($busca)){
      do_shortcode('[fixon_buscando]');
      exit;
    }

  }

// ---'.bloginfo('url').'---



  $get_url_if_op = fixon_get_op();

  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{

    }
  
  }
  $df = array();
  
  $df['sql_order'] = $sql_order;
  $df['sql_dir'] = $sql_dir;
  $df['col_add'] = $col_add;

  $df['md'] = $md;
  $md = preg_replace("/__md__/", fixon_get_md() , $md);
  
  

  $col  = fixon_get_md_col($md,$cnn,$df);
  // return 'xxx';
  //_die_fixon_list

  // print_r($col);
  if($col_replace){
    $resplace = explode(",", $col_replace);
    foreach ($resplace as $keyc => $valuec) {
      $arrray = explode(":", $valuec);
      foreach ($col as $key => $value) {
        if ($value['dataIndex']==$arrray[0]) {
          $col[$key]['dataIndex'] = $arrray[1];
          $col[$key]['filter_type'] = 'string';
        }
      }
    }
  }


  if($die_col){
    echo "<pre>";
    print_r($col);
    echo "<pre>";
    return '';
  }

  if(!count($col)) return '';

  $modulo_conf    = fixon_get_modulo_conf($md, $cnn);
  $tabela         = $modulo_conf['tabela'];
  $campo_codigo   = $tabela."_codigo";
  $fields         = fixon_get_fields($md, $cnn,$df);

    // echo "<pre>";
    // print_r($fields);
    // echo "<pre>";
    // die('_die_fixon_list');

  $df['join'] = $join;
  $df['die_col'] = $die_col;
  $df['col_replace'] = $col_replace;
  $df['die_sql'] = $die_sql;
  $df['inner'] = $inner;

  $criterio = preg_replace("/__cod__/", fixon_get_cod() , $criterio);
  $criterio = preg_replace("/__pai__/", fixon_get_pai() , $criterio);
  $criterio = preg_replace("/__prefix__/", fixon_prefix(false) , $criterio);
  $criterio = preg_replace("/__pessoa_by_user__/", get_user_meta( get_current_user_id(), "pessoa_by_user", true ) , $criterio);
 
  $df['criterio'] = base64_encode($criterio);
  $data = fixon_get_md_rows($md, $fields, $col, $df, $cnn);
  
  if(isset($data['msg'])){
    if($data['msg']) return $data['msg'];
  }

  $_SESSION['md'.$md.'_total'] = $data['total'];

  $manut = '';//$modulo_conf['show_cp_option'];
  if( $on_op) $manut = false;

  //paginacai -ini

  $ret = "";
  $url = '';//$_SERVER["REDIRECT_URL"].'?';

//gambiarra pra consertar  a paginação quando nginxs
  $q = (isset($_GET["q"]) ? sanitize_text_field($_GET["q"]) : '');
  if($q){
    $link       = $q.'?';//$url.$_SERVER["REQUEST_URI"];
  } else {
    $link       = $url.$_SERVER["QUERY_STRING"];
  }
  
  $start      = isset($_GET['start']) ? sanitize_text_field($_GET['start']) : 0;
  $limit      = isset($_GET['limit']) ? sanitize_text_field($_GET['limit']) : $modulo_conf['limit'];//20; //por paginas ou limit
  $total      = $data['total'];//149;//$data['total']
  $supertotal = 0;
  $total2 = $total - $limit;
 
  $rfirst     = fixon_add_param($link,'start',"0");//0;//fixon_remove_param($link, 'start');//
  $rprevious  = fixon_add_param($link,'start',($start-$limit < 0 ? 0 : $start-$limit));//0;//fixon_add_param($link,'start',10)
  $rnext      = fixon_add_param($link,'start',$start+$limit) ;
  $rlast      = fixon_add_param($link,'start',($total2));// $supertotal - $limit;//90;//fixon_add_param($link,'start',($supertotal-10))

  $limit_10   = fixon_add_param($link,'limit',"10");
  $limit_25   = fixon_add_param($link,'limit',"25");
  $limit_50   = fixon_add_param($link,'limit',"50");
  $limit_100  = fixon_add_param($link,'limit',"100");

  echo '<hr>';
  echo '<div style="clear:toth;"></div>';
  $ret = '<div style="clear:toth;"></div>';
  $ret .= $title;
  $ret .= '  <div class="" style="overflow-y:auto;border:solid 0px gray;">';
  $ret .= '<table style="'.$style.'table-layout: auto;" class="" >';
  if(($config) && (preg_match("/no_col_title/i", $config))){
  } else{
    $ret .= '<thead>';
    $ret .= '<tr>';
    $ret .= '<th class="fixon_th" style="white-space: nowrap;"></th>';
    for ($i=0; $i < count($col); $i++){
      if($col[$i]['ctr_list'] == 'label'){
        if(($un_show) && (preg_match("/".$col[$i]['dataIndex']."/i", $un_show))){
        } else {
          // if(($un_show) && (preg_match("/".$col[$i]['dataIndex']."/i", $un_show))){
          $col[$i]['text'] = preg_replace("/_/", " ", $col[$i]['text']);
          $ret .= '<th class="fixon_th" style="white-space: nowrap">'.$col[$i]['text'].'</th>';
        }
      }
    }
    $ret .= '</tr>';
    $ret .= '</thead>';
  }
  $ret .= '<tbody>';
  $ret_col_x0_1 = '';
  for ($i=0; $i < count($data['row']); $i++){
    $ret .= '<tr class="wpmsc_tr">';
    if ($col_url) {
      $t566_codigo_name = isset($col[0]['codigo_name']) ? $col[0]['codigo_name'] : '';
      if($t566_codigo_name){
        $t566_v_codigo_name = $data['row'][$i][$t566_codigo_name];
        $ok = 0;
        if($col_url){
          $col_url = preg_replace("/__tcod__/i", $t566_v_codigo_name, $col_url);
          $col_url = preg_replace("/__pai__/i", fixon_get_pai(), $col_url);
          $col_url = preg_replace("/__cod__/i", fixon_get_cod(), $col_url);
          $col_url_arr = explode(",", $col_url);
          foreach ($col_url_arr as $ckey => $cvalue) {
            $is_role_true = true;
            $col_url_arr_item = explode(":", $cvalue);
            $is_role_in = isset( $col_url_arr_item[2] ) ? $col_url_arr_item[2] : '';
            if($is_role_in){
              $is_role_true = fixon_is_role($is_role_in);
            } else {
              $is_role_true = 1;
            }
            if($is_role_true) {
              foreach ($col as $key => $value) {
                if ($value['dataIndex']==$col_url_arr_item[0]) {
                  $tcampo = $value['dataIndex'];
                  $tvalue = $col_url_arr_item[1];
                  $tvalue = preg_replace("/__this__/i", $data['row'][$i][$tcampo], $tvalue);
                  foreach ($col as $ttkey => $ttvalue) {
                    $tttcampo = $ttvalue['dataIndex'];
                    $tttvalue = $data['row'][$i][$tttcampo];
                    if (preg_match("/__".$tttcampo."__/", $tvalue)) {
                      $tvalue = preg_replace("/__".$tttcampo."__/", $data['row'][$i][$tttcampo],$tvalue);
                    }
                  }
                  $trole = isset($col_url_arr_item[2]) ? $col_url_arr_item[2] : "";
                  $data['row'][$i][$tcampo] = $tvalue; 
                }
              }
            }
          }
        }
      }
    }

    $ret_col_x0 = '';
    $ret_col_x0_label = '';
    
    $tmp3 = '';

    if ($col_x0) {
      $ret_col_x0 = $col_x0;
      $ret_col_x0_label = '...';
      foreach ($col as $key => $value) {
        $x0_campo = $value['dataIndex'];
        $x0_value = $data['row'][$i][$x0_campo];
        $ret_col_x0 = preg_replace("/__".$value['dataIndex']."__/", $x0_value, $ret_col_x0);
      }
      $ret_col_x0 = preg_replace("/__cod__/", fixon_get_cod() , $ret_col_x0);
      $ret_col_x0 = preg_replace("/__pai__/", fixon_get_pai() , $ret_col_x0);
      $tmp = explode("|", $ret_col_x0);
      foreach ($tmp as $key => $tmp_value) {
        $tmp2 = explode("&", $tmp_value);
        $role = '';
        $is_role = '';
        $tmp3_1 = '';
        foreach ($tmp2 as $key => $tmp2_value) {
          // echo "tmp2_value: ".$tmp2_value;
          // echo "<br>";
          if ( substr($tmp2_value, 0, 4)== 'role') {
            $role = substr($tmp2_value, 5);
            $is_role = fixon_is_role($role);
          } else {
            $tmp3_1 .= $tmp2_value."&";
          }
        }
        // echo "role: $role <br>";
        // echo "is_role: $is_role <br>";
        if($is_role){
          $tmp3 .= $tmp3_1."|";
        }
      }


      // echo "<br>";
      // echo "tmp3: ".$tmp3."<br>";
      // echo '</pre>';


    }
    // $ret .= '   <td class="fixon_col_0" data-fixon_col_0="'.$ret_col_x0.'" style="white-space: nowrap;">'.$ret_col_x0_label;
    $ret .= '   <td class="fixon_col_0 fixon_td" data-fixon_col_0="'.$tmp3.'" style="white-space: nowrap;">'.$ret_col_x0_label;
    // $ret .= '   <td style="white-space: nowrap;;"> ';


    $ret .= isset($col_x0a[$i]) ? $col_x0a[$i] : '';
    $ret .= '</td>';
    for ($c=0; $c < count($col); $c++) {  $campo = $col[$c]['dataIndex'];
     
      if($col[$c]['ctr_list'] == 'label'){
        if(($un_show) && (preg_match("/".$campo."/i", $un_show))){
          //$ret .= '<td style="border:1px solid;"></td>';
        }else{
          if(($config) && (preg_match("/no_cel_url/i", $config))){
            $data['row'][$i][$campo] = strip_tags($data['row'][$i][$campo]);//'--=--';
          }
          $ret .= '<td class="'.$col[$c]['dataIndex'].' fixon_td" style="white-space: nowrap;color:#000000;">'.$data['row'][$i][$campo].'</td>';
          // $ret .= '<td class="irow-sit-" style="color:#000000;">'.$data['row'][$i][$campo].'</td>';
        }
      }
    }
    $ret .= '</tr>';
  }
  $ret .= '</tbody>';
  $ret .= '</table>';
  $ret .= '</div>';
  //show paginacao - ini
  if(($config) && (preg_match("/no_count_reg/i", $config))){
  } else {
    $ret .= '<div style="text-align:center"> ';
    $ret .= '<big>'.$total.' registro(s).</big>';
    $ret .= '</div>';
  }
  //show paginacao - end

  // return $ret;
  //show total  - ini
  if(($config) && (preg_match("/no_sum_col/i", $config))){
  } else {

    $limit = 20;
    $q = (isset($_GET["q"]) ? sanitize_text_field($_GET["q"]) : '');
    if($q){
      $link       = $q.'?';//$url.$_SERVER["REQUEST_URI"];
    } else {
      $link       = $url.$_SERVER["QUERY_STRING"];
    }
    
    $start = isset($_GET['start']) ? $_GET['start'] : 0;


    $rnext      = fixon_add_param($link,'start',$start+$limit) ;
    $rprevious  = fixon_add_param($link,'start',$start-$limit) ;

    $nav_atual = isset($_GET['start']) ? $_GET['start'] : 0;
    $nav_inicio = 0;
    $nav_anterior = $nav_atual - 20;
    $nav_proximo = $nav_atual + 20;
    $nav_ultimo = ($data['total'] -20);

    if ($nav_anterior < 0) {$nav_anterior = 0;}
    if ($nav_proximo > $nav_ultimo ) { $nav_proximo = $nav_ultimo; }

    $url_inicio = fixon_add_param($link,'start',$nav_inicio) ;
    $url_anterior  = fixon_add_param($link,'start',$nav_anterior) ;
    $url_proximo = fixon_add_param($link,'start',$nav_proximo) ;
    $url_ultimo = fixon_add_param($link,'start',$nav_ultimo) ;

    
    if($total > $limit){
      $ret .= '<div style="text-align:center"> ';
      $ret .= '<a href="'.$url_inicio.'" >&nbsp;&lt;&lt;&nbsp;</a>';
      $ret .= '<a href="'.$url_anterior.'" >&nbsp;&lt;&nbsp;</a>';
      $ret .= '<small>&nbsp;'.$start.' a '.((($start + $limit) > $total) ? $total : ($start + $limit)).'&nbsp;</small>';
      $ret .= '<a href="'.$url_proximo.'" >&nbsp;&gt;&nbsp;</a>';
      $ret .= '<a href="'.$url_ultimo.'" >&nbsp;&gt;&gt;&nbsp;</a>';
      $ret .= '</div>';
    }
  }
  //show total  - end

  //return $ret;

    if(($config) && (preg_match("/no_paging/i", $config))){
      } else {
    if($total > $limit){
      $ret .= '<div style="text-align:center"> ';
      $ret .= 'limite ';
      $ret .= '<a href="'.$limit_10.'" class="btn btn-link '.$add_class.'">&nbsp;10&nbsp;</a>';
      $ret .= '<a href="'.$limit_25.'" class="btn btn-link '.$add_class.'">&nbsp;25&nbsp;</a>';
      $ret .= '<a href="'.$limit_50.'" class="btn btn-link '.$add_class.'">&nbsp;50&nbsp;</a>';
      $ret .= '<a href="'.$limit_100.'" class="btn btn-link '.$add_class.'">&nbsp;100&nbsp;</a>';
      $ret .= ' por pagina ';
      $ret .= '</div>';
    }
  }

  $ret .= '
  <script type="text/javascript">
    jQuery(function($){
      jQuery(".wpmsc_link_ajax").on("click",function(e){
        var url = jQuery(this).attr("href");
        // alert(url);
        jQuery( "#aba_ctu" ).load(url);
        return false;
      })
    });
  </script>
  ';
  return $ret;
}
add_shortcode("fixon_list", "fixon_list");



function fixon_edit($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "cnn" => '',
    "cod" => '0',
    "target_update" => '?op=update&cod=__cod__&pai=__pai__',
    "on_op" => '',
    "access" => '',
    "role" => '',
    "un_show" => '',
    "title" => ''
  ), $atts));


  if($access){if(!fixon_is_access($access)) return '';}
  if($role){ if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
      if(!$get_url_if_op)  return '';
      if($get_url_if_op<>$on_op) return '';
    }
  }

  $md = preg_replace("/__md__/", fixon_get_md() , $md);
  $cod = preg_replace("/__cod__/", fixon_get_cod() , $cod);
  $target_update = preg_replace("/__cod__/", fixon_get_cod() , $target_update);
  $target_update = preg_replace("/__md__/", fixon_get_md() , $target_update);
  $target_update = preg_replace("/__pai__/", fixon_get_pai(), $target_update);
  $ret = '';
  if(!$md) {$ret = "fixon_edit - md não especificado";}
  if(!$cod) {$ret = "fixon_edit - cod não especificado";}
  if($ret) {return $ret;exit;}
  
  $edit = fixon_md_edit($md,$cod,$cnn);

  // echo "<pre>";
  // print_r($edit);
  // echo "</pre>";
  // die("__die__fixon_edit");


  $ttop = isset($_REQUEST['op']) ? sanitize_text_field($_REQUEST['op']) : '';

  if($ttop=='duplicar'){
    $ret .= '
    <script type="text/javascript">
    jQuery(function(){
      jQuery("#fmdsubmit").css("visibility","hidden");
      jQuery("#fmdsubmit").remove();
      jQuery("#fmdduplique").css("visibility","visible");
      // alert(333);
    });
    </script>
    ';
  }


  $ret .= '';
  ?>
  <style type="text/css">
    .fixon_edit {
      display: grid;
      grid-template-columns: 25% 50% 25%;
    }
    .fixon_edit .fxn_ct {
      min-width: 100%;
    }
    .fixon_edit .fxn_ct input {
      border: 1px solid #000000;
      
    }

    .fixon_edit form input[type="text"], 
    .fixon_edit form input[type="password"], 
    .fixon_edit form input[type="email"], 
    .fixon_edit form input[type="url"], 
    .fixon_edit form input[type="date"], 
    .fixon_edit form input[type="month"], 
    .fixon_edit form input[type="time"], 
    .fixon_edit form input[type="datetime"], 
    .fixon_edit form input[type="datetime-local"], 
    .fixon_edit form input[type="week"], 
    .fixon_edit form input[type="number"], 
    .fixon_edit form input[type="search"], 
    .fixon_edit form input[type="tel"], 
    .fixon_edit form input[type="color"], 
    .fixon_edit form select, 
    .fixon_edit form textarea  {
      border: 1px solid gray;
      color: #000000;
      padding: 5px;
    }

    .fixon_edit label  {
      /*text-transform: uppercase;*/
      color: #000000;
      font-size: 10px;
      font-style : italic;
    }
  </style>
  <?php 
  $ret .= '<div class="fixon_edit">';
  $ret .= '<div></div>';
  $ret .= '<div>';

  $ret .= $title;
  $ret .= ' <form class="" action="'.$target_update.'" method="POST">';
  for ($i=0; $i < count($edit['campo']); $i++) {
    $edit['campo'][$i]['fieldLabel'] = preg_replace("/_/", " ", $edit['campo'][$i]['fieldLabel']);
    if(($un_show) && (preg_match("/".$edit['campo'][$i]['name']."/i", $un_show))){

    } else {
      $ret .= ' <div class="" style="margin-bottom:2px;padding-right:10px;" >';
      $ret .= '   <label>'.$edit['campo'][$i]['fieldLabel'].'</label>';
      $ret .= '   <div class="" style="min-height:30px">';
      if($edit['campo'][$i]['type']=='blob'){
        $ret .= ' <textarea class="form-control" autocomplete="off" id="'.$edit['campo'][$i]['name'].'" name="'.$edit['campo'][$i]['name'].'" >'.$edit['campo'][$i]['value'].'</textarea>';  
      }else{
        $ret .= '     <input type="text" style="" class="fxn_ct" name="'.$edit['campo'][$i]['name'].'" id="'.$edit['campo'][$i]['name'].'" value="'.$edit['campo'][$i]['value'].'" title="" autocomplete="off">';  
      }
      $ret .= '   </div>';
      $ret .= '   <div style=""></div>';
      $ret .= ' </div>';
    }
  }
  
  $ret .= ' <div style="height:15px;"></div>';

  $ret .= ' <div class="" style="margin-bottom:2px;padding-right:10px;" >';
  $ret .= '   <div class=""> </div>';
  $ret .= '   <div class="">';
  $ret .= '     <button id="fmdsubmit" type="submit" class="">Atualizar</button> ';
  $ret .= '     <button id="fmdduplique" type="submit" name="duplique" class="" style="visibility: hidden;">Duplicar</button> ';
  $ret .= '   </div>';
  $ret .= '   <div style="clear:both;"></div>';
  $ret .= ' </div>';
  $ret .= ' </form>';

  $ret .= '</div>';
  $ret .= '<div></div>';
  $ret .= '</div>';
  return $ret;
  //btn btn-primary
}
add_shortcode("fixon_edit", "fixon_edit");



function fixon_busca($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => 0,
    "op" => '',
    "cod" => 0,
    "target" => '',
    "target_det" => '',
    "on_op" => '',
    "access" => '',
    "role" => '',
    "style" => '',
    "class" => '',
    "placeholder" => 'BUSCA',
    "add_hidden" => '',
    "add_hidden_det" => '',
  ), $atts));

  $target = preg_replace("/__site_url__/",site_url() , $target);
  $target_det = preg_replace("/__site_url__/",site_url() , $target_det);

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){ if(!fixon_is_role($role)) return '';}

  $vai = true;
  if($on_op) {
    $vai = false;
    $t_op = fixon_get_op() ? fixon_get_op() : 'empty';
    if(($on_op=='empty') && ($t_op=='empty')) $vai = true;
  }
  if(!$vai) return '';

  $busca = isset($_GET['busca']) ? fixon_get_busca() : '';

  if(!$target_det) $target_det = $target;


  $ret = "";
  $ret .= "";
  $ret .= "
  <script>
  jQuery(function($){
    $('.fixon_busca').on('submit', function(){
      console.log('fixon_busca');
      var ifcod = $('.fixon_busca_cp').val();
      console.log(ifcod);
      var target_det = '".$target_det."';
      console.log('target_det:'+target_det);
      var target = '".$target."';
      if($.isNumeric( ifcod )) {
        // target_det = '".$target_det."?cod';
        // target_det = target_det+'&cod='+ifcod;
        // console.log('target_det:'+target_det);
        // $(this).attr('action', target_det);
        // $('.fixon_busca_cp').attr('name', 'cod');
        $('.fixon_busca_op').val('view');
        $('.fixon_busca_cod').val(ifcod);
        $('.fixon_busca_cp').remove();
        
        
      } else {
        // $(this).attr('action', target);
        // $('.fxn_busca_hidden').remove();
        $('.fixon_busca_cod').remove();
        $('.fixon_busca_op').remove();
      }
      // return false;
    });
  });
  </script>
  ";
  $ret .= '<form name="fixon_busca" action="'.$target.'" method="GET" class="fixon_busca '.$class.'" style="'.$style.'">';
  $ret .= '  <input type="text" class="fixon_busca_cp" value="'.$busca.'" name="busca" placeholder="'.$placeholder.'" style="text-align:center" autocomplete="off">';
  // if($add_hidden) {
    // EXEMPLO: add_hidden="op=view" 
    // $exp = explode("=", $add_hidden);
    $ret .= '  <input class="fixon_busca_cod" type="hidden" name="cod" value="">';
    $ret .= '  <input class="fixon_busca_op" type="hidden" name="op" value="">';
  // }

  $ret .= '</form>';
  return $ret;
}
add_shortcode("fixon_busca", "fixon_busca");



function fixon_delete($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "cod" => '0',
    "target_pos_delete" => '?',
    "on_op" => '',
    "access" => '',
    "role" => ''
  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';
    }
  }

    $target_pos_delete = preg_replace("/__cod__/", fixon_get_cod() , $target_pos_delete);
  $target_pos_delete = preg_replace("/__pai__/", fixon_get_pai() , $target_pos_delete);

  $cod = preg_replace("/__cod__/", fixon_get_cod() , $cod);
  $md = preg_replace("/__md__/", fixon_get_md() , $md);

  $delete = fixon_md_delete($md,$cod);

  $ret = '';
  $ret = "";
  $ret .= '';
  if($target_pos_delete){
    echo '<script type="text/javascript">';
    echo '    window.location.href = "'.html_entity_decode($target_pos_delete).'";';
    // echo  'window.location.href = "../md-detalhe/?md=1030&cod=511"';
    echo '</script>';
  }
  return $ret;
}
add_shortcode("fixon_delete", "fixon_delete");



function fixon_deletar($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "cod" => '0',
    "target_delete" => '?op=delete&cod=__cod__',
    "on_op" => '',
    "access" => '',
    "role" => ''
  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';

    }
  }

  $cod = preg_replace("/__cod__/", fixon_get_cod() , $cod);
  $md = preg_replace("/__md__/", fixon_get_md() , $md);

  $target_delete = preg_replace("/__cod__/", fixon_get_cod() , $target_delete);
  $target_delete = preg_replace("/__pai__/", fixon_get_pai() , $target_delete);

  $ret = "";
  // $ret .= "<h1 style='color:red;'>DELETAR</h1>";
  $ret .= "<h2 style='text-align:center;'>EXCLUSÃO DE REGISTRO</h2>";
  $ret .= do_shortcode('[fixon_view md='.$md.' cod=__cod__]');
  $ret .= '<div style="text-align:center;">';
  $ret .= do_shortcode('[fixon_botao label="CONFIRME A EXCLUSÃO DESTE REGISTRO" target="'.$target_delete.'" class="btn btn-danger"]');
  $ret .= '</div>';

  return $ret;
}
add_shortcode("fixon_deletar", "fixon_deletar");


function fixon_insert($atts, $content = null) {
  extract(shortcode_atts(array(
    "cnn" => '',
    "md" => '0',
    "cod" => '0',
    "target" => '',
    "target_pos_insert" => '?',
    "on_op" => '',
    "access" => '',
    "role" => '',
    "col_fix" => '',
    "insert_add" => '',
    "insert_add_user_meta" => '',
    "insert_add_option" => ''
  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){ if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';

    }
  }

  $target_pos_insert = html_entity_decode($target_pos_insert);

  $md = preg_replace("/__md__/", fixon_get_md() , $md);

  $target_pos_insert = preg_replace("/__cod__/", fixon_get_cod() , $target_pos_insert);
  $target_pos_insert = preg_replace("/__pai__/", fixon_get_pai() , $target_pos_insert);

  $ret = '';
  if(!$md) {$ret = "fixon_insert - md não especificado";}
  if($ret) {return $ret;exit;}

  $tmp_request = $_REQUEST;
  $fields = '';
  $values = '';


// echo "<br>---$col_fix---<br>";
  if($col_fix){

    $col_fix_arr = explode(',', $col_fix);
    foreach ($col_fix_arr as $key => $value) {
      $t = explode('=', $value);
      $fields .= $t[0];
      $values .= $t[1];
      $values = preg_replace("/__user__/i",  get_current_user_id(), $values);
      $tmp_request[$fields] = $values;

    }
  }
  // print('<pre>');
  // print_r($_REQUEST);
  // print('</pre>');
  // if($col_fix) die();

  $insert = fixon_md_insert($md, $tmp_request, $cnn, $insert_add, $insert_add_user_meta, $insert_add_option );
  $ret = "";
  $ret .= '';
  if($target_pos_insert){
    echo '<script type="text/javascript">';
    echo '    window.location.href = "'.$target_pos_insert.'";';
    echo '</script>';
    exit;
  }
  return $ret;
}
add_shortcode("fixon_insert", "fixon_insert");



function fixon_botao($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "cod" => '0',
    "target" => '',
    "label" => '',
    "janela" => '',//blank
    "class" => '',
    "style" => '',
    "on_op" => '',
    "access" => '',
    "role" => '',
    "rel" => '',
    "sub_menu" => ''


  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
      if(!$get_url_if_op)  return '';
      if($get_url_if_op<>$on_op) return '';
    }
  }

  $target = preg_replace("/__md__/", fixon_get_md() , $target);
  $target = preg_replace("/__cod__/", fixon_get_cod() , $target);
  $target = preg_replace("/__qs__/",$_SERVER['REQUEST_URI'] , $target);
  $target = preg_replace("/__site_url__/",site_url() , $target);
  $target = preg_replace("/__pai__/", fixon_get_pai() , $target);
  $target = preg_replace("/__hoje__/", date('d/m/Y') , $target);
  
  $to_janela = '';
  if($janela) $to_janela = 'target="'.$janela.'"';

  if($sub_menu){
    // echo '<pre>';
    // echo $sub_menu;
    // echo '<br>';
    // echo '</pre>';

    $tmp = explode("|", $sub_menu);

    $tmp4 = '';
    foreach ($tmp as $key => $tmp_value) {
      // echo $tmp_value;
      // echo "<br>";
      $tmp1 = explode("&", $tmp_value);
      $tmp3 = '';
      $is_role = '';
      $role = '';
      foreach ($tmp1 as $key => $tmp1_value) {
        // echo "--".$tmp1_value;
        // echo "<br>";
        if(substr($tmp1_value, 0,4)=="role"){
          $role = substr($tmp1_value, 5);
          $is_role = fixon_is_role($role);
          // echo "--=role: ".$role;
          // echo "<br>";
          // echo "--=is_role: ".$is_role;
          // echo "<br>";
        } else {
          $tmp3 .= $tmp1_value;
        }
      }
      if ($is_role) {
        $tmp4 .= $tmp3;
      }
      // echo "tmp3: ".$tmp3;
      // echo "<br>";
      // echo "<br>";
    }
    // echo "tmp4: ".$tmp4;
    // echo "<br>";


    return '<a rel="'.$rel.'" style="'.$style.'" class="fixon_botao '.$class.'" href="#" data-sub_menu="'.$tmp4.'" >'.$label.'</a>'.$content;  
  } else {
    return '<a rel="'.$rel.'" style="'.$style.'" class=" '.$class.'" href="'.$target.'" '.$to_janela.' >'.$label.'</a>'.$content;
  }
  

}
add_shortcode("fixon_botao", "fixon_botao");


function fixon_view($atts, $content = null) {
  extract(shortcode_atts(array(
    "cnn" => '',
    "md" => '0',
    "cod" => '0',
    "style" => '',
    "un_show" => '',
    "access" => '',
    "role" => '',
    "on_op" => '',
    "inner" => '',
    "col_replace" => '',
    "title" => ''
  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $df=array();
  $df['inner'] = $inner;
  $df['col_replace'] = $col_replace;

  // return $on_op;

  $get_url_if_op = fixon_get_op();
  if($on_op=="empty"){
    if($get_url_if_op) return '';
  } else{
    if($on_op){
      if($on_op<>$get_url_if_op) return '';
    }
  }
  // return '---'.$get_url_if_op.'---';
  // if($on_op) {
    // if($on_op=="empty"){
      // if($get_url_if_op) return '';
    // }else{

    // }
    // if(!$get_url_if_op)  return '';
    // if($get_url_if_op<>$on_op) return '';
  // }

  $df['md'] =$md;
  $cod = preg_replace("/__cod__/", fixon_get_cod(), $cod);
  $cod = preg_replace("/__pai__/", fixon_get_pai(), $cod);
  $md = preg_replace("/__md__/", fixon_get_md(), $md);
  $cod = preg_replace("/__pessoa_by_user__/", get_user_meta( get_current_user_id(), "pessoa_by_user", true ) , $cod);
  
  $ret ="";
  if(!$md) {$ret = "fixon_view - md não especificado";}
  if(!$cod) {$ret = "fixon_view - cod não especificado";}
  if($ret) {return $ret;exit;}
  $view = fixon_md_view($md,$cod,$cnn,$df);

  $ret = "";
  $ret .= '';
  $ret .= '
  <style type="text/css">
    .fixon_view {
      display: grid;
      grid-template-columns: 25% 50% 25%;
    }
  </style>
  ';
  $ret .= '<div class="fixon_view">';
  $ret .= '<div></div>';
  $ret .= '<div>';

  $ret .= $title;


  $ret .= ' <form action="" method="POST">';
  $ret .= '<div style="border-bottom:1px solid gray;"">';
  for ($i=0; $i < count($view['campo']); $i++) {
    if(($un_show) && (preg_match("/".$view['campo'][$i]['name']."/i", $un_show))){

    } else{
      $view['campo'][$i]['fieldLabel'] = preg_replace("/_/", " ", $view['campo'][$i]['fieldLabel']);
      // $view['campo'][$i]['fieldLabel'] = strtoupper($view['campo'][$i]['fieldLabel']);
      // $view['campo'][$i]['fieldLabel'] = strtoupper($view['campo'][$i]['fieldLabel']);
      $ret .= ' <div style="display: grid;grid-template-columns: 3fr 7fr;border-top:1px solid gray;" >';
      $ret .= '   <div style="text-align:right;font-style: italic;font-size: 12px;padding-right: 15px;">'.$view['campo'][$i]['fieldLabel'].':</div>';
      $ret .= '   <div  style="min-height:30px;font-weight: bolder;">';
      $ret .= '     '.$view['campo'][$i]['value'].' ';
      $ret .= '   </div>';
      $ret .= ' </div>';
    }
  }
  $ret .= ' </div>';
  $ret .= '</form>';


  $ret .= '</div>';
  $ret .= '<div></div>';
  $ret .= '</div>';
  return $ret;
}
add_shortcode("fixon_view", "fixon_view");


function fixon_update($atts, $content = null) {
  extract(shortcode_atts(array(
    "cnn" => '',
    "md" => '0',
    "cod" => '0',
    "on_op" => '',
    "target_pos_update" => '?op=view&cod=__cod__',
    "access" => '',
    "role" => '',

  ), $atts));

  $get_url_if_op = fixon_get_op();

  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';
    }
  }

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $md = preg_replace("/__md__/", fixon_get_md() , $md);
  $cod = preg_replace("/__cod__/", fixon_get_cod() , $cod);
  $target_pos_update = preg_replace("/__cod__/", fixon_get_cod() , $target_pos_update);
  $target_pos_update = preg_replace("/__md__/", fixon_get_md() , $target_pos_update);
  $target_pos_update = preg_replace("/__pai__/", fixon_get_pai() , $target_pos_update);
  if(!$md) {$ret = "fixon_update - md não especificado";}
  if(!$cod) {$ret = "fixon_update - cod não especificado";}

  if(isset($_POST['duplique'])) {
    if(!fixon_md_insert($md, $_REQUEST )) {echo "ERRO AO INSERIR";exit;}
      echo '<script type="text/javascript">';
      echo '    window.location.href = "?" ;';
      echo '</script>';
    exit;
  }

  $ret = fixon_md_update($md,$cod,$cnn);

  if($target_pos_update){

    $ret = "";
    $url = '';//$_SERVER["REDIRECT_URL"];
    $add_class = "wpmsc";
    /*
    if(substr($url,1,6)=='xxxwpmsc') {
      $add_class = "i".$md."update";
      
      $ret .= '
      <script type="text/javascript">
        // jQuery(function(){
        // alert("'.$url.$target_pos_update.'");
        //   jQuery(".i'.$md.'update").submit(function(e){
        //     e.preventDefault();
        //     url = jQuery(this).attr("action");
        //     alert(url);
        //     jQuery.ajax({
        //       method: "POST",
        //       url: url,
        //       data: jQuery(this).serialize()
        //     })
        //   // // alert(jQuery(this).serialize());
        //     // .done(function( html ) {
        //     //   // jQuery( "#aba_ctu" ).append( html );
        //     //   jQuery( "#aba_ctu div" ).remove();
        //     //   jQuery( "#aba_ctu" ).html("ok");
        //     // });
        //     return false;
        //   })
        // });
      </script>
      
      ';
      
    }else{
      */
      echo '<script type="text/javascript">';
      echo '    window.location.href = "'.html_entity_decode($url.$target_pos_update).'";';
      echo '</script>';
    //}
  }
  return '';
}
add_shortcode("fixon_update", "fixon_update");


function fixon_nnew($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0',
    "cod" => '0',
    "restrito" => 's',
    "target_insert" => '?op=insert&pai=__pai__',

    "label_submit" => 'Salvar',
    "title" => '',
    "access" => '',
    "role" => '',
    "on_op" => '',
    "un_show" => '',
    "class" => '',
    "add_class" => '',
    "access_manager" => '',
    "add_cp_class" => '', // add_cp_class="fixon_000000_nome:fixon_class_busca_nome:__site_url__/ajax_busca_nome/"
    "combo_ajax" => ''
  ), $atts));

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
      if(!$get_url_if_op)  return '';
      if($get_url_if_op<>$on_op) return '';
    }
  }

  // $rel = isset($_GET['rel']) ? $_GET['rel'] : '';

  $df['md'] =$md;
  $md = preg_replace("/__md__/", fixon_get_md() , $md);

  $target_insert = preg_replace("/__md__/", fixon_get_md() , $target_insert);
  $target_insert = preg_replace("/__cod__/", fixon_get_cod() , $target_insert);
  $target_insert = preg_replace("/__pai__/", fixon_get_pai() , $target_insert);

  $ret = '';
  if(!$md) {$ret = "nnew - md não especificado";}
  if($ret) {return $ret;exit;}

  $nnew = fixon_get_md_novo($md);
  
  $ret .= '';
  $ret .= '
  <style type="text/css">
    .fixon_nnew {
      display: grid;
      grid-template-columns: 30% 40% 30%;
    }
    .fixon_nnew label {
      color: #000000;
      font-size: 10px;
      font-style : italic;
    }
    .fxn_cp {
      margin-bottom: 10px;
    }
  </style>
  ';

  $ret .= '<div class="fixon_nnew">';
  $ret .= '<div></div>';
  $ret .= '<div>';

  // $ret .= $title;
  $ret .= '<form action="'.$target_insert.'" method="POST">';
  for ($i=0; $i < count($nnew['campo']); $i++) {

    $campo = $nnew['campo'][$i]['name'];
    $value = isset($_REQUEST[$campo]) ? sanitize_text_field($_REQUEST[$campo]) : '';
    // $value2 = isset($_REQUEST[$campo]) ? $_REQUEST[$campo] : '';
    // echo $campo."<br>value: ".$value."<br>";
    if(!$nnew['campo'][$i]['value']) $nnew['campo'][$i]['value'] = $value;
    if(($un_show) && (preg_match("/".$campo."/i", $un_show))){ }else{
      // ==== combo_ajax_url - imi ====
      // $combo_ajax_url = "";
      if($combo_ajax){
        // echo $combo_ajax;
        // echo "<br>";
        $combo_ajax_e = explode("|", $combo_ajax);
        foreach ($combo_ajax_e as $key => $value_combo) {
          $value_e = explode(":", $value_combo);
          $combo_ajax_url = "";
          if ($value_e[0]==$campo) {
            // echo $combo_ajax;
            // echo "<br>";
            $combo_ajax_tabela = $value_e[1];
            $combo_ajax_campo1 = $value_e[2];
            $combo_ajax_campo2 = $value_e[3];
            $combo_ajax_target1 = $value_e[4];
            $combo_ajax_target2 = $value_e[5];
            $combo_ajax_url = "<a href='#' data-tabela='".$combo_ajax_tabela."' data-campo1='".$combo_ajax_campo1."' data-campo2='".$combo_ajax_campo2."' data-target1='".$combo_ajax_target1."' data-target2='".$combo_ajax_target2."' class='fixon_combo_ajax'>...</a>";
          }
        }
      }
      //combo_ajax="fixon_000520_pessoa:tabela:campo1:campo2:target_campo1:target_campo2"
      //combo_ajax="campo_que_chama_o_combo:tabela:tabela_campo1:tabela_campo2:target_campo1:target_campo1"
      // ==== combo_ajax_url - end ====
      $ret .= ' <div class="fxn_cp" >';
      $ret .= '   <div class=""><label>'.$nnew['campo'][$i]['fieldLabel'].$combo_ajax_url.'</label></div>';
      $ret .= '   <div class="" style="margin:0px;padding:0px;border: 0px solid gray;">';
      if($nnew['campo'][$i]['type']=='blob'){
        $ret .= ' <textarea class="form--control" autocomplete="off" id="'.$nnew['campo'][$i]['name'].'" name="'.$nnew['campo'][$i]['name'].'" >'.$nnew['campo'][$i]['value'].'</textarea>';  
      }elseif($nnew['campo'][$i]['type']=='file'){
        $ret .= '<input style="min-height:100%;" class="" autocomplete="off" type="file" id="'.$nnew['campo'][$i]['name'].'" name="'.$nnew['campo'][$i]['name'].'"/>';
//<form enctype="multipart/form-data" action="__URL__" method="POST">
      }else{
        // $ret .= ' <input type="text" style="min-width:100%;" name="'.$nnew['campo'][$i]['name'].'" id="'.$nnew['campo'][$i]['name'].'" class="form--control" value="'.$nnew['campo'][$i]['value'].'" title="" autocomplete="off" >';  
        $ret .= ' <input type="text" style="min-width:100%;" name="'.$nnew['campo'][$i]['name'].'" id="'.$nnew['campo'][$i]['name'].'" class="form--control" value="'.$value.'" title="" autocomplete="off" >';  
        

      }
      $ret .= '   </div>';
      $ret .= ' </div>';
    }
  }

  $ret .= ' <div style="height:40px;"></div>';

  $ret .= ' <div class="dv_nnew_group">';
  $ret .= '   <div class="col1"></div>';
  $ret .= '   <div class="col2">';
  $ret .= '     <button type="submit" class="btn btn-primary" style="padding: 10px 60px;">'.$label_submit.'</button>';
  $ret .= '   </div>';
  $ret .= ' </div>';

  $ret .= '</form>';


  $ret .= '</div>';
  $ret .= '<div></div>';
  $ret .= '</div>';

  return $ret;
}
add_shortcode("fixon_nnew", "fixon_nnew");



function fixon_recent($atts, $content = null){
  global $wpdb;

  //if ( !is_user_logged_in() ) exit;
  extract(shortcode_atts(array(
    "target" => "",
    "md" => "0",
    "on_op" => ''
  ), $atts));

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{

    }
    if(!$get_url_if_op)  return '';
    if($get_url_if_op<>$on_op) return '';
  }

  $modulo_conf  = fixon_get_modulo_conf($md);

  $tabela_name = $GLOBALS['wpdb']->prefix.$modulo_conf['tabela'];
  $tabela_campo = $modulo_conf['tabela'];
  $campo_codigo  = $tabela_campo."_codigo";
  $sql = "select $campo_codigo from $tabela_name order by $campo_codigo desc limit 0, 1";
  $tb = fixon_db_exe($sql,'rows');
  if(!$tb['r']) exit;

  if (!$target) {
    return 'target';
  }
  
  if($tb['r']){
    $cod = $tb['rows'][0][$campo_codigo];
    $target = preg_replace("/__cod__/", $cod , $target);
    echo '<script type="text/javascript">';
    echo  'window.location.href = "'.$target.'"';
    echo '</script>';
    exit;
  }

}
add_shortcode("fixon_recent", "fixon_recent");


function fixon_text($atts, $content = null){
  extract(shortcode_atts(array(
    "on_op" => '',
    "access" => '',
    "role" => '',
    "url" => '',
    "id" => ''
  ), $atts));
  // return $on_op;

  if($access){if(!fixon_is_access($access)) return '';}
  if($role){if(!fixon_is_role($role)) return '';}
  

  $get_url_if_op = fixon_get_op();
  if($on_op) {
    if($on_op=="empty"){
      if($get_url_if_op) return '';
    }else{
     if(!$get_url_if_op)  return '';
     if($get_url_if_op<>$on_op) return '';

    }
  }
  return $content;
}
add_shortcode("fixon_text", "fixon_text");
