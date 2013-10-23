<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

/**
************************************************************************
*  Author: kierownik
*  Date: 2013-10-23
*  Description: Makes it possible that users cannot use some kind passwords
*  Copyright (C) Daniel Rokven ( rokven@gmail.com )
*  License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
*
************************************************************************
**/

// Make sure no one attempts to run this script "directly"
if ( !defined( 'PUN' ) )
{
  exit;
}

//
// Generate the forbidden_passwords cache PHP script
//
function generate_fp_cache()
{
	global $db;

	// Get the forum fp from the DB
	$result = $db->query( 'SELECT id, password FROM '.$db->prefix.'forbidden_passwords', true ) or error( 'Unable to fetch forum topic_icon', __FILE__, __LINE__, $db->error() );

	$output = array();
	while ( $cur_fp_item = $db->fetch_row( $result ) )
	{
		$output[$cur_fp_item[0]] = $cur_fp_item[1];
	}

	// Output fp as PHP code
	$content = '<?php'."\n\n".'define( \'PUN_FORBIDDEN_PASSWORD_LOADED\', 1 );'."\n\n".'$forbidden_passwords = '.var_export($output, true).';'."\n\n".'?>';
	fluxbb_write_cache_file( 'cache_forbidden_passwords.php', $content );
}

//
// Delete forbidden_password cache
//
function clear_fp_cache()
{
	if ( file_exists( FORUM_CACHE_DIR.'cache_forbidden_passwords.php' ) )
	{
		@unlink( FORUM_CACHE_DIR.'cache_forbidden_passwords.php' );
	}
}

//
// Safely write out a cache file.
//
if ( !function_exists( 'fluxbb_write_cache_file' ) )
{
	function fluxbb_write_cache_file($file, $content)
	{
		$fh = @fopen(FORUM_CACHE_DIR.$file, 'wb');
		if (!$fh)
			error('Unable to write cache file '.pun_htmlspecialchars($file).' to cache directory. Please make sure PHP has write access to the directory \''.pun_htmlspecialchars(FORUM_CACHE_DIR).'\'', __FILE__, __LINE__);

		flock($fh, LOCK_EX);
		ftruncate($fh, 0);

		fwrite($fh, $content);

		flock($fh, LOCK_UN);
		fclose($fh);

		if (function_exists('apc_delete_file'))
			@apc_delete_file(FORUM_CACHE_DIR.$file);
	}
}