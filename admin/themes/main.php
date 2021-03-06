<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['theme_manager'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$theme_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );
$theme_mobile_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme_mobile'] );
$theme_list = array_merge( $theme_list, $theme_mobile_list );

$i = 1;
$number_theme = sizeof( $theme_list );

$errorconfig = array();

$array_site_cat_theme = array();
if( $global_config['idsite'] )
{
	$result = $db->sql_query( "SELECT theme FROM `" . $db_config['dbsystem'] . "`.`" . $db_config['prefix'] . "_site_cat` AS t1 INNER JOIN `" . $db_config['dbsystem'] . "`.`" . $db_config['prefix'] . "_site` AS t2 ON t1.`cid`=t2.`cid` WHERE t2.`idsite`=" . $global_config['idsite'] );
	$row = $db->sql_fetch_assoc( $result );
	if( ! empty( $row['theme'] ) )
	{
		$array_site_cat_theme = explode( ',', $row['theme'] );

		$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `func_id`=0";
		$result = $db->sql_query( $sql );
		while( list( $theme ) = $db->sql_fetchrow( $result ) )
		{
			$array_site_cat_theme[] = $theme;
		}
		$array_site_cat_theme = array_unique( $array_site_cat_theme );
	}
}

foreach( $theme_list as $value )
{
	// Load thumbnail image
	if( ! $xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $value . '/config.ini' ) )
	{
		$errorconfig[] = $value;
		continue;
	}
	//kiem tra giao dien co danh cho subsite hay ko
	if( ! empty( $array_site_cat_theme ) AND ! in_array( $value, $array_site_cat_theme ) )
	{
		continue;
	}

	$info = $xml->xpath( 'info' );

	if( $global_config['site_theme'] == $value )
	{
		$xtpl->parse( 'main.loop.active' );
	}
	else
	{
		$xtpl->parse( 'main.loop.deactive' );
	}

	$xtpl->assign( 'ROW', array(
		'name' => ( string )$info[0]->name,
		'website' => ( string )$info[0]->website,
		'author' => ( string )$info[0]->author,
		'thumbnail' => ( string )$info[0]->thumbnail,
		'description' => ( string )$info[0]->description,
		'value' => $value
	) );

	$position = $xml->xpath( 'positions' );
	$positions = $position[0]->position;
	$pos = array();

	for( $j = 0, $count = sizeof( $positions ); $j < $count; ++$j )
	{
		$pos[] = $positions[$j]->name;
	}

	$xtpl->assign( 'POSITION', implode( ' | ', $pos ) );
	$dash = 0;
	if( ! in_array( $value, $theme_mobile_list ) AND $global_config['site_theme'] != $value )
	{
		$xtpl->parse( 'main.loop.link_active' );
		$dash++;
	}
	if( defined( "NV_IS_GODADMIN" ) )
	{
		$dash++;
		$xtpl->parse( 'main.loop.link_delete' );
	}
	if( $dash == 2 )
	{
		$xtpl->parse( 'main.loop.dash' );
	}
	if( $i % 2 == 0 and $i < $number_theme )
	{
		$xtpl->parse( 'main.loop.endtr' );
	}
	else
	{
		$xtpl->parse( 'main.loop.endtd' );
	}

	++$i;

	$xtpl->parse( 'main.loop' );
}

if( ! empty( $errorconfig ) )
{
	$xtpl->assign( 'ERROR', implode( "<br />", $errorconfig ) );
	$xtpl->parse( 'main.error' );
}

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>