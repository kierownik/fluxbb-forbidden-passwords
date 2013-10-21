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

// Load cached forbidden_passwords
if ( !defined( 'PUN_FORBIDDEN_PASSWORD_LOADED') )
{
  if ( file_exists( FORUM_CACHE_DIR.'cache_forbidden_passwords.php' ) )
  {
    include FORUM_CACHE_DIR.'cache_forbidden_passwords.php';
  }
  else
  {
    require_once PUN_ROOT.'plugins/forbidden-passwords/cache.php';

    generate_fp_cache();
    include FORUM_CACHE_DIR.'cache_forbidden_passwords.php';
  }
}

// Load the forbidden-passwords.php language file
if ( file_exists( PUN_ROOT.'plugins/forbidden-passwords/lang/'.$pun_user['language'].'/forbidden-passwords.php' ) )
{
  require PUN_ROOT.'plugins/forbidden-passwords/lang/'.$pun_user['language'].'/forbidden-passwords.php';
}
else
{
  require PUN_ROOT.'plugins/forbidden-passwords/lang/English/forbidden-passwords.php';
}

$fp_config = unserialize( $pun_config['o_forbidden_passwords'] );

if ( $fp_config['use_strtolower'] == '1' )
{
  $strtolower_new_password1 = strtolower( $new_password1 );
}

  $check = array_search( $strtolower_new_password1, $forbidden_passwords );

?>