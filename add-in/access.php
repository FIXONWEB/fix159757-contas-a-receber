<?php

function fixon_is_access($grupo){
  if(is_super_admin()) return true;
  $role = get_user_meta( get_current_user_id(), 'role', true );
  if($role){
    $grupos = explode(',', $grupo);
    foreach ($grupos as $key => $value) {
      if($value==$role) return true;
    }
  }
  return false;
}


function fixon_is_role($role){
  // if(is_super_admin()) return true;
  $role = trim($role);
  if(preg_match("/|/", $role)) $t = explode("|", $role);
  if(preg_match("/,/", $role)) $t = explode(",", $role);
  $ret = 0;
  foreach ($t as $key => $value) {
    if( get_role($value) ) {
      if(current_user_can( trim($value) ))  $ret = 1;
    }
  }
  return $ret;
}
