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

// Load the forbidden-passwords.php language file
if ( file_exists( PUN_ROOT.'plugins/forbidden-passwords/lang/'.$pun_user['language'].'/forbidden-passwords.php' ) )
{
  require PUN_ROOT.'plugins/forbidden-passwords/lang/'.$pun_user['language'].'/forbidden-passwords.php';
}
else
{
  require PUN_ROOT.'plugins/forbidden-passwords/lang/English/forbidden-passwords.php';
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

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define( 'PUN_PLUGIN_LOADED', 1 );

// Plugin version
define( 'PLUGIN_VERSION', '0.1' );

// We need all the config unserialized
$fp_config = unserialize( $pun_config['o_forbidden_passwords'] );

// Number of passwords in the database
$num_passwords = count( $forbidden_passwords );

//
// The rest is up to you!
//
// Save the options
if ( isset( $_POST['set_options'] ) )
{
  $updated = FALSE;

  $fp_options = array(
    'use_strtolower' => !empty( $_POST['use_strtolower'] ) ? intval( $_POST['use_strtolower'] ) : '0',
  );

  if ( serialize( $fp_options ) != $pun_config['o_forbidden_passwords'] )
  {
    $query = 'UPDATE `'.$db->prefix."config` SET `conf_value` = '".$db->escape( serialize( $fp_options ) )."' WHERE `conf_name` = 'o_forbidden_passwords'";

    $db->query( $query ) or error( 'Unable to update board config post '. print_r( $db->error() ),__FILE__, __LINE__, $db->error() );

    $updated = TRUE;
  }

  if ( $updated )
  {
    // Regenerate the config cache
    require_once PUN_ROOT.'include/cache.php';
    generate_config_cache();
    redirect( $_SERVER['REQUEST_URI'], $lang_fp['data saved'] );
  }
} // end set_options

// Begin save passwords
if ( isset( $_POST['save_passwords'] ) )
{
  $updated = FALSE;

  $fp_diff = array_diff( $_POST['password'], $forbidden_passwords );

  if ( !empty( $fp_diff ) )
  {
    foreach ( $fp_diff as $key => $value )
    {
      // if the value is not empty we have to update the password else we have to delete it
      if ( !empty( $value ) )
      {
        $query = 'UPDATE `'.$db->prefix."forbidden_passwords` SET `password` = '".$db->escape( $value )."' WHERE `id` = '".$db->escape( $key )."'";

        $db->query( $query ) or error( 'Unable to update password '. print_r( $db->error() ),__FILE__, __LINE__, $db->error() );
      }
      elseif ( empty( $value ) )
      {
        $query = 'DELETE FROM `'.$db->prefix."forbidden_passwords` WHERE `id` = '".$db->escape( $key )."'";

        $db->query( $query ) or error( 'Unable to delete password '. print_r( $db->error() ),__FILE__, __LINE__, $db->error() );
      }
    }
    $updated = TRUE;
  }

  if ( $updated )
  {
    // Regenerate the forbidden passwords cache
    require_once PUN_ROOT.'plugins/forbidden-passwords/cache.php';
    generate_fp_cache();
    redirect( $_SERVER['REQUEST_URI'], $lang_fp['data saved'] );
  }
} // End save passwords

// Begin save new password
if ( isset( $_POST['save_new_password'] ) )
{
  $updated = FALSE;

  $new_password = !empty( $_POST['new_password'] ) ? $_POST['new_password'] : '';

  if ( !empty( $new_password ) )
  {
    $query = 'INSERT INTO `'.$db->prefix."forbidden_passwords` ( password ) VALUES ( '".$db->escape( $new_password )."' )";

    $db->query( $query ) or error( 'Unable to insert "'.$new_password.'" into the database '. print_r( $db->error() ),__FILE__, __LINE__, $db->error() );

    $updated = TRUE;
  }

  if ( $updated )
  {
    // Regenerate the forbidden passwords cache
    require_once PUN_ROOT.'plugins/forbidden-passwords/cache.php';
    generate_fp_cache();
    redirect( $_SERVER['REQUEST_URI'], $lang_fp['data saved'] );
  }
} // End save new password

// Display the admin navigation menu
generate_admin_menu( $plugin );

?>
<div id="forbidden_passwords" class="plugin blockform">
  <h2><span><?php echo $lang_fp['forbidden passwords'] ?> - v<?php echo PLUGIN_VERSION ?></span></h2>
  <div class="box">
    <div class="inbox">
      <p><?php echo $lang_fp['forbidden passwords info'] ?></p>
    </div>
  </div>
</div>
<div class="plugin blockform">
  <h2 class="block2"><span><?php echo $lang_fp['options'] ?></span></h2>
  <div class="box">
    <form id="fp_options" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="inform">
      <fieldset>
        <legend></legend>
        <div class="infldset">
          <p>
            <?php $checked = ( $fp_config['use_strtolower'] == '1' ) ? ' checked="checked"' : ''; ?>
            <label for="use_strtolower"><?php echo $lang_fp['use strtolower'] ?> <input type="checkbox" id="use_strtolower" name="use_strtolower" value="1" <?php echo $checked ?> /> <?php echo $lang_fp['use strtolower info'] ?></label>
          </p>
        </div>	<!-- end class="infldset" -->
      </fieldset>
      <p class="submittop">
        <input type="submit" name="set_options" value="<?php echo $lang_fp['save options'] ?>"/>
      </p>
    </div>
    </form>
  </div>
</div>

<div class="plugin blockform">
  <h2 class="block2"><span><?php echo $lang_fp['new password'] ?></span> </h2>
  <div class="box">
    <form id="fp_new" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="inform">
      <fieldset>
        <legend></legend>
        <div class="infldset">
          <p>
            <label for="new_password"><?php echo $lang_fp['new password'] ?> <input type="text" id="new_password" name="new_password" value="" /></label>
          </p>
        </div>
      </fieldset>
      <p class="submittop">
        <input type="submit" name="save_new_password" value="<?php echo $lang_fp['save new password'] ?>"/>
      </p>
    </div>
  </div>
</div>

<div class="plugin blockform">
  <h2 class="block2"><span><?php echo $lang_fp['forbidden passwords database'] ?></span> <small>( <?php echo $num_passwords ?> )</small></h2>
  <div class="box">
    <div class="inbox">
      <p style="text-align: center">If you want to delete a password, just leave the input box empty and save.</p>
    </div>
    <form id="fp_database" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="inform">
      <p class="submittop">
        <input type="submit" name="save_passwords" value="<?php echo $lang_fp['save passwords'] ?>"/>
      </p>
      <fieldset>
        <legend></legend>
        <div class="infldset">
          <table class="aligntop" style="border-spacing:0;border-collapse:collapse;">

            <?php

              asort( $forbidden_passwords );
              $i = 1;
              $input_in_a_row = 5;
              foreach ( $forbidden_passwords as $key => $value )
              {
                if ( $i == 1 )
                  echo '<tr>';

                echo '<td><input name="password['.intval( $key ).']" type="text" value="'.pun_htmlspecialchars( $value ).'" /></td>';

                echo ( $i % $input_in_a_row == 0 ) ? '</tr>' : '';
                echo ( $i % ($input_in_a_row*10) == 0 ) ? '<tr><td colspan="'.$input_in_a_row.'" style="text-align: center"><input type="submit" name="save_passwords" value="'.$lang_fp['save passwords'].'"/></td></tr>' : '';
                $i++;
              }

            ?>

          </table>
        </div>
      </fieldset>
      <p class="submittop">
        <input type="submit" name="save_passwords" value="<?php echo $lang_fp['save passwords'] ?>"/>
      </p>
      <p>
        <a href="#punadmin" style="float: right;">Back to top</a>
      </p>
      <p style="clear:right"></p>
    </div>
  </div>      <!-- end class="box" -->
</div>        <!-- end class="blockform" -->