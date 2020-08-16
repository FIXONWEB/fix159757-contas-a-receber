<?php 

function fixon_add_param($querystring, $ParameterName, $ParameterValue){
    $queryStr = null; 
    $paramStr = null;
    if (strpos($querystring, '?') !== false)
        list($queryStr, $paramStr) = explode('?', $querystring);
    else if (strpos($querystring, '=') !== false)
        $paramStr = $querystring;
    else
        $queryStr = $querystring;
    $paramStr = $paramStr ? '&' . $paramStr : '';
    $paramStr = preg_replace ('/&' . $ParameterName . '(\[\])?=[^&]*/', '', $paramStr);
    if(is_array($ParameterValue)) {
        foreach($ParameterValue as $key => $val) {
            $paramStr .= "&" . urlencode($ParameterName) . "[]=" . urlencode($val);
        }
    } else {
        $paramStr .= "&" . urlencode($ParameterName) . "=" . urlencode($ParameterValue);
    }
    $paramStr = ltrim($paramStr, '&');
    return $queryStr ? $queryStr . '?' . $paramStr : $paramStr;
}

function fixon_remove_param($querystring, $ParameterName){
  $paramStr = '';
  $queryStr = '';
    if (strpos($querystring, '?') !== false)
        list($queryStr, $paramStr) = explode('?', $querystring);
    else if (strpos($querystring, '=') !== false)
        $paramStr = $querystring;
    else
        $queryStr = $querystring;
    $paramStr = $paramStr ? '&' . $paramStr : '';
    $paramStr = preg_replace ('/&' . $ParameterName . '(\[\])?=[^&]*/', '', $paramStr);
    $paramStr = ltrim($paramStr, '&');
    return $queryStr ? $queryStr . '?' . $paramStr : $paramStr;
}


function fixon_paginacao($atts, $content = null) {
  extract(shortcode_atts(array(
    "md" => '0'
  ), $atts));

  $get_start = isset($_GET['start']) ? sanitize_text_field($_GET['start']) : 0;
  $get_limit = isset($_GET['limit']) ? sanitize_text_field($_GET['limit']) : 20;

  if(!$md) {echo "paginação - $md nao especificado";exit;}

  $total = isset($_SESSION['md'.$md.'_total']) ? $_SESSION['md'.$md.'_total'] : 0;

  $start = $get_start;
  $limit = $get_limit;

  $start_preview  = $start - $limit;
  $start_next   = $start + $limit;

  if($start_preview < 0 ) $start_preview = 0;
  if($start_next > $total ) $start_next = $start_next;

  $paginas = ceil($total / $limit);
  $pagina = 1;
  if(($start+1) > $limit){
    $pagina = ceil(($start+1) / $limit) ;
  }

  $tt = $start+$limit;
  $pagina_last = $paginas * $limit;

  $limit_end = $start + $limit;
  if($limit_end > $total) $limit_end = $total;

//----ini



  $cls = "";
  $csl_last = "";
  $csl_preview = "";


  if (($pagina_last+$limit) > $total) {
    $tt = $total;

    if(($start+$limit) >= $total) {
      $csl = "disabled";
      $csl_last = "disabled";
    }
    if(!$start){
      $csl_preview = "disabled";
    }
  }
  //----end


  // $qs = $_SERVER["QUERY_STRING"];
  $qs = $_SERVER["REQUEST_URI"];

  // $link       = $url.$_SERVER["QUERY_STRING"];
  //REQUEST_URI
  $link       = $url.$_SERVER["REQUEST_URI"];



  $ret = '';
  // $ret .= '<h4>Paginação</h4>';
  // $ret .= '<div></div>';
  $ret .= '<div class="pd10">';
  $ret .= '  <a class="btn btn-primary fleft '.$csl_preview.'" href="?start=0&limit='.$limit.'>"><span class="glyphicon glyphicon-fast-backward"></span></a>';
  $ret .= '  <a class="btn btn-primary fleft '.$csl_preview.'" href="?start='.$start_preview.'&limit='.$limit.'"><span class="glyphicon glyphicon-backward"></span></a>';
  $ret .= '  <a class="btn btn-primary fleft '.$csl_last.'" href="?start='.$start_next.'&limit='.$limit.'"><span class="glyphicon glyphicon-forward"></span></a>';
  $ret .= '  <a class="btn btn-primary fleft '.$csl_last.'" href="?start='.$pagina_last.'&limit='.$limit.'"><span class="glyphicon glyphicon-fast-forward"></span></a>';
  $ret .= '  <div class="w20 h30 fleft">  </div>';
  $ret .= '  <a class="btn btn-primary  fleft'.$csl_last.'" href=""><span class=" glyphicon glyphicon-refresh"></span></a>';

  $ret .= '';




  // $ret .= '  <div class="clear"></div>';
  // $ret .= '  <div class="hide_">';
  $ret .= '   <div class="fleft pd10">';
  $ret .= '     Total de registros: '.$total.' ';
  $ret .= '   </div>';

  $ret .= '   <div class="fleft pd10 ">';
  $ret .= '     Páginas : '.$paginas.' ';
  $ret .= '   </div>';
  $ret .= '   <div class="fleft pd10">';
  $ret .= '     Página atual: '.$pagina.' ';
  $ret .= '   </div>';
  $ret .= '   <div class="fleft pd10"> ';
  $ret .= '     Mostrando de: '.$start.' a '.($start + $limit).' ';
  $ret .= '   </div>';
  $ret .= '   <div class="fleft pd10"> ';
  $ret .= '     (registros por páginas: '.$limit.') ';
  $ret .= '   </div>';
  // $ret .= '  </div>';

  $ret .= '</div>';

  return $ret;

/**/

}
add_shortcode("fixon_paginacao", "fixon_paginacao");
