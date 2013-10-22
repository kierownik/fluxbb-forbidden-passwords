<?php

/**
************************************************************************
*  Author: kierownik
*  Date: 2013-MM-DD
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

// Plugin root
if ( !defined( 'PLUGIN_ROOT' ) )
{
  define( 'PLUGIN_ROOT', PUN_ROOT.'/plugins/forbidden-passwords/');
}

// Load cached forbidden_passwords
if ( !defined( 'PUN_FORBIDDEN_PASSWORD_LOADED') )
{
  if ( file_exists( FORUM_CACHE_DIR.'cache_forbidden_passwords.php' ) )
  {
    include FORUM_CACHE_DIR.'cache_forbidden_passwords.php';
  }
  else
  {
    require_once PLUGIN_ROOT.'cache.php';

    generate_fp_cache();
    include FORUM_CACHE_DIR.'cache_forbidden_passwords.php';
  }
}

// Load the forbidden-passwords.php language file
if ( file_exists( PLUGIN_ROOT.'lang/'.$pun_user['language'].'/forbidden-passwords.php' ) )
{
  require PLUGIN_ROOT.'lang/'.$pun_user['language'].'/forbidden-passwords.php';
}
else
{
  require PLUGIN_ROOT.'lang/English/forbidden-passwords.php';
}

$fp_config = unserialize( $pun_config['o_forbidden_passwords'] );

if ( $fp_config['use_strtolower'] == '1' )
{
  $strtolower_password1 = strtolower( $password1 );
}

  $check = in_array( $strtolower_password1, $forbidden_passwords );

?>