<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// Tong so bai viet 
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_rows` where `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")"));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number 
    );
}

// So bai viet thanh vien gui toi  
/*$sql = "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_users_send`";
$array_data = nv_db_cache( $sql, '', $mod );
$number = isset( $array_data[0]['number'] ) ? intval( $array_data[0]['number'] ) : 0;
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_users_send'], 'value' => $number 
    );
}*/

// So bai viet cho dang 
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_rows` where `status`= 0 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")"));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_pending'], 'value' => $number 
    );
}

// So bai viet da het han  
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_rows` where `exptime` > 0 AND `exptime`<" . NV_CURRENTTIME.""));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number 
    );
}

// So bai viet sap het han   
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_rows` where `status` = 1 AND `exptime`>" . NV_CURRENTTIME.""));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_exptime'], 'value' => $number 
    );
}

// Tong so binh luan duoc dang   
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comments` where `status` = 1"));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number 
    );
}

// So binh luan cho duyet   
list( $number ) = $db->sql_fetchrow( $db->sql_query("SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comments` where `status` = 0"));
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_comment_pending'], 'value' => $number 
    );
}

?>