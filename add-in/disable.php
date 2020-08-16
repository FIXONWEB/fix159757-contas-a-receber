<?php

register_deactivation_hook( $plugin_file, "fixon_000001d" );
function fixon_000001d() {
    global $wpdb;
    // $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000002 where fixon_000002_tabela = 'fixon_000600';");
    // $wpdb->query( "delete from ".$GLOBALS['wpdb']->prefix."fixon_000001 where fixon_000001_tabela = 'fixon_000600';");
    $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000002");
    $wpdb->query( "drop table if exists ".$GLOBALS['wpdb']->prefix."fixon_000001");
}