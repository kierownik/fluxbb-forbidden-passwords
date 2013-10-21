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

// Some info about your mod.
$mod_title      = 'Forbidden Password';
$mod_version    = '0.1';
$release_date   = '2013-MM-DD';
$author         = 'Daniel Rokven';
$author_email   = 'rokven@gmail.com';

// Versions of FluxBB this mod was created for. A warning will be displayed, if versions do not match
$fluxbb_versions= array( '1.5.4', '1.5.3');

// Set this to FALSE if you haven't implemented the restore function (see below)
$mod_restore  = TRUE;

// We want the complete error message if the script fails
if ( !defined( 'PUN_DEBUG' ) )
  define( 'PUN_DEBUG', 1 );

// This following function will be called when the user presses the "Install" button
function install()
{
  global $db, $pun_config;

  // Begin create the forbidden_passwords table and populate it
  if ( !$db->table_exists( 'forbidden_passwords', TRUE ) )
  {
    // Schema to create the forbidden_passwords table
    $schema = array(
      'FIELDS'      => array(
        'id'        => array(
          'datatype'      => 'SERIAL',
          'allow_null'      => false
        ),
        'password'   => array(
          'datatype'      => 'VARCHAR( 200 )',
          'allow_null'    => false
        )
      ),
        'PRIMARY KEY'   => array( 'id' ),
        'INDEXES'     => array(
          'password_idx' => array( 'password' )
      )
    );

    // Create the forbidden_passwords table
    $db->create_table( 'forbidden_passwords', $schema ) or error( 'Unable to create table "forbidden_passwords"', __FILE__, __LINE__, $db->error() );

    // All the forbidden passwords in one array to insert into the forbidden_passwords table
    // The next list comes mostly from
    // http://www.whatsmypass.com/the-top-500-worst-passwords-of-all-time
    $fp_list = array(
       '1111', '1212', '1234', '1313', '2000', '2112', '2222', '3333', '4128', '4321', '4444', '5150', '5555', '6666', '6969', '7777', '11111', '12345', '111111', '112233', '121212', '123123', '123456', '131313', '232323', '654321', '666666', '696969', '777777', '987654', '1234567', '7777777', '8675309', '11111111', '12345678', 'aaaa', 'aaaaaa', 'abc123', 'abgrtyu', 'access', 'access14', 'action', 'albert', 'alex', 'alexis', 'amanda', 'amateur', 'andrea', 'andrew', 'angel', 'angela', 'angels', 'animal', 'anthony', 'apollo', 'apple', 'apples', 'arsenal', 'arthur', 'asdf', 'asdfgh', 'ashley', 'asshole', 'august', 'austin', 'baby', 'badboy', 'bailey', 'banana', 'barney', 'baseball', 'batman', 'beach', 'bear', 'beaver', 'beavis', 'beer', 'bigcock', 'bigdaddy', 'bigdick', 'bigdog', 'bigtits', 'bill', 'billy', 'birdie', 'bitch', 'bitches', 'biteme', 'black', 'blazer', 'blonde', 'blondes', 'blowjob', 'blowme', 'blue', 'bond007', 'bonnie', 'booboo', 'boobs', 'booger', 'boomer', 'booty', 'boston', 'brandon', 'brandy', 'braves', 'brazil', 'brian', 'bronco', 'broncos', 'bubba', 'buddy', 'bulldog', 'buster', 'butter', 'butthead', 'calvin', 'camaro', 'cameron', 'canada', 'captain', 'carlos', 'carter', 'casper', 'charles', 'charlie', 'cheese', 'chelsea', 'chester', 'chevy', 'chicago', 'chicken', 'chris', 'cocacola', 'cock', 'coffee', 'college', 'compaq', 'computer', 'cookie', 'cool', 'cooper', 'corvette', 'cowboy', 'cowboys', 'cream', 'crystal', 'cumming', 'cumshot', 'cunt', 'dakota', 'dallas', 'daniel', 'danielle', 'dave', 'david', 'debbie', 'dennis', 'diablo', 'diamond', 'dick', 'dirty', 'doctor', 'doggie', 'dolphin', 'dolphins', 'donald', 'dragon', 'dreams', 'driver', 'eagle', 'eagle1', 'eagles', 'edward', 'einstein', 'enjoy', 'enter', 'eric', 'erotic', 'extreme', 'falcon', 'fender', 'ferrari', 'fire', 'firebird', 'fish', 'fishing', 'florida', 'flower', 'flyers', 'football', 'ford', 'forever', 'frank', 'fred', 'freddy', 'freedom', 'fuck', 'fucked', 'fucker', 'fucking', 'fuckme', 'fuckyou', 'gandalf', 'gateway', 'gators', 'gemini', 'george', 'giants', 'ginger', 'girl', 'girls', 'golden', 'golf', 'golfer', 'gordon', 'great', 'green', 'gregory', 'guitar', 'gunner', 'hammer', 'hannah', 'happy', 'hardcore', 'harley', 'heather', 'hello', 'helpme', 'hentai', 'hockey', 'hooters', 'horney', 'horny', 'hotdog', 'house', 'hunter', 'hunting', 'iceman', 'iloveyou', 'internet', 'iwantu', 'jack', 'jackie', 'jackson', 'jaguar', 'jake', 'james', 'japan', 'jasmine', 'jason', 'jasper', 'jennifer', 'jeremy', 'jessica', 'john', 'johnny', 'johnson', 'jordan', 'joseph', 'joshua', 'juice', 'junior', 'justin', 'kelly', 'kevin', 'killer', 'king', 'kitty', 'knight', 'ladies', 'lakers', 'lauren', 'leather', 'legend', 'letmein', 'little', 'london', 'love', 'lover', 'lovers', 'lucky', 'maddog', 'madison', 'maggie', 'magic', 'magnum', 'marine', 'mark', 'marlboro', 'martin', 'marvin', 'master', 'matrix', 'matt', 'matthew', 'maverick', 'maxwell', 'melissa', 'member', 'mercedes', 'merlin', 'michael', 'michelle', 'mickey', 'midnight', 'mike', 'miller', 'mine', 'mistress', 'money', 'monica', 'monkey', 'monster', 'morgan', 'mother', 'mountain', 'movie', 'muffin', 'murphy', 'music', 'mustang', 'naked', 'nascar', 'nathan', 'naughty', 'ncc1701', 'newyork', 'nicholas', 'nicole', 'nipple', 'nipples', 'oliver', 'orange', 'ou812', 'packers', 'panther', 'panties', 'paris', 'parker', 'pass', 'password', 'patrick', 'paul', 'peaches', 'peanut', 'penis', 'pepper', 'peter', 'phantom', 'phoenix', 'player', 'please', 'pookie', 'porn', 'porno', 'porsche', 'power', 'prince', 'princess', 'private', 'purple', 'pussies', 'pussy', 'qazwsx', 'qwert', 'qwerty', 'qwertyui', 'rabbit', 'rachel', 'racing', 'raiders', 'rainbow', 'ranger', 'rangers', 'rebecca', 'redskins', 'redsox', 'redwings', 'richard', 'robert', 'rock', 'rocket', 'rosebud', 'runner', 'rush2112', 'russia', 'samantha', 'sammy', 'samson', 'sandra', 'saturn', 'scooby', 'scooter', 'scorpio', 'scorpion', 'scott', 'secret', 'sexsex', 'sexy', 'shadow', 'shannon', 'shaved', 'shit', 'sierra', 'silver', 'skippy', 'slayer', 'slut', 'smith', 'smokey', 'snoopy', 'soccer', 'sophie', 'spanky', 'sparky', 'spider', 'squirt', 'srinivas', 'star', 'stars', 'startrek', 'starwars', 'steelers', 'steve', 'steven', 'sticky', 'stupid', 'success', 'suckit', 'summer', 'sunshine', 'super', 'superman', 'surfer', 'swimming', 'sydney', 'taylor', 'teens', 'tennis', 'teresa', 'test', 'tester', 'testing', 'theman', 'thomas', 'thunder', 'thx1138', 'tiffany', 'tiger', 'tigers', 'tigger', 'time', 'tits', 'tomcat', 'topgun', 'toyota', 'travis', 'trouble', 'trustno1', 'tucker', 'turtle', 'united', 'vagina', 'victor', 'victoria', 'video', 'viking', 'viper', 'voodoo', 'voyager', 'walter', 'warrior', 'welcome', 'whatever', 'white', 'william', 'willie', 'wilson', 'winner', 'winston', 'winter', 'wizard', 'wolf', 'women', 'xavier', 'xxxx', 'xxxxx', 'xxxxxx', 'xxxxxxxx', 'yamaha', 'yankee', 'yankees', 'yellow', 'young', 'zxcvbn', 'zxcvbnm', 'zzzzzz',
    );

    asort( $fp_list );

    // Loop thru the $fp_list array to add them to the forbidden_passwords table
    foreach ( $fp_list AS $key )
    {
      $query = "SELECT password FROM `".$db->prefix."forbidden_passwords` WHERE `password`='".$db->escape( $key )."'";
      if ( !$db->num_rows( $db->query( $query ) ) )
      {
        $db->query( "INSERT INTO ".$db->prefix."forbidden_passwords ( password ) VALUES ( '".$db->escape( $key )."' ) " ) or error( 'Unable to add "'.$key.'" to forbidden_passwords table', __FILE__, __LINE__, $db->error() );
      }
    }
  }
  // End create the forbidden_passwords table and populate it

  // Begin check if "o_forbidden_passwords" exist in the config table
  $query = "SELECT * FROM `".$db->prefix."config` WHERE `conf_name`='o_forbidden_passwords'";

  if ( !$db->num_rows( $db->query( $query ) ) )
  {
    // Begin add options to the config table
    $fp_options = array(
      'use_strtolower'        => 1,
    );

    // Serialize the new config
    $fp_config = serialize( $fp_options );

    // Insert the new config in the new config field
    $db->query( "INSERT INTO ".$db->prefix."config (conf_name, conf_value) VALUES ( 'o_forbidden_passwords', '".$db->escape( $fp_config )."' ) " ) or error( 'Unable to add "o_forbidden_passwords" to config table', __FILE__, __LINE__, $db->error() );
    // End add options to the config table
  }
  // End check if "o_forbidden_passwords" exist in the config table

  // generate the forbidden_passwords cache and config cache
  require_once PUN_ROOT.'/plugins/forbidden-passwords/cache.php';
  generate_fp_cache();
}

// This following function will be called when the user presses the "Restore" button (only if $mod_restore is true (see above))
function restore()
{
  global $db, $db_type, $pun_config;

  // Drop the table forbidden_passwords
  if ( $db->table_exists( 'forbidden_passwords' ) )
  {
    $db->drop_table( 'forbidden_passwords' ) or error( 'Unable to drop table "forbidden_passwords"', __FILE__, __LINE__, $db->error() );
  }
  // End drop the table forbidden_passwords

  // Begin check if "o_forbidden_passwords" exist in the config table
  $query = "SELECT * FROM `".$db->prefix."config` WHERE `conf_name`='o_forbidden_passwords'";

  if ( $db->num_rows( $db->query( $query ) ) )
  {
    // Delete the "o_forbidden_passwords" from the config table
    $db->query( 'DELETE FROM '.$db->prefix.'config WHERE conf_name = "o_forbidden_passwords"' ) or error( 'Unable to delete "o_forbidden_passwords" from config table', __FILE__, __LINE__, $db->error() );
  }
  // End check if "o_forbidden_passwords" exist in the config table

  // Clear the forbidden_passwords cache and config cache
  require_once PUN_ROOT.'/plugins/forbidden-passwords/cache.php';
  clear_fp_cache();
}

/***********************************************************************/

// DO NOT EDIT ANYTHING BELOW THIS LINE!


// Circumvent maintenance mode
define( 'PUN_TURN_OFF_MAINT', 1 );
define( 'PUN_ROOT', './' );
require PUN_ROOT.'include/common.php';

// We want the complete error message if the script fails
if ( !defined('PUN_DEBUG' ) )
  define( 'PUN_DEBUG', 1 );

// Make sure we are running a FluxBB version that this mod works with
$version_warning = !in_array( $pun_config['o_cur_version'], $fluxbb_versions );

$style = ( isset( $pun_user ) ) ? $pun_user['style'] : $pun_config['o_default_style'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo pun_htmlspecialchars($mod_title) ?> installation</title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $style.'.css' ?>" />
</head>
<body>

<div id="punwrap">
<div id="puninstall" class="pun" style="margin: 10% 20% auto 20%">

<?php

if ( isset( $_POST['form_sent'] ) )
{
  if ( isset( $_POST['install'] ) )
  {
    // Run the install function ( defined above )
    install();

?>
<div class="block">
  <h2><span>Installation successful</span></h2>
  <div class="box">
    <div class="inbox">
      <p>Your database has been successfully prepared for <?php echo pun_htmlspecialchars( $mod_title ) ?>. See readme.txt for further instructions.</p>
      <p><a href="javascript: history.go(-1)">Go 1 step back</a></p>
      <p><a href="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ) ?>">Go to your site</a> | <a href="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ).'/admin_index.php' ?>">Go to your site's administration</a></p>
    </div>
  </div>
</div>
<?php

  }
  else
  {
    // Run the restore function ( defined above )
    restore();

?>
<div class="block">
  <h2><span>Restore successful</span></h2>
  <div class="box">
    <div class="inbox">
      <p>Your database has been successfully restored.</p>
      <p><a href="javascript: history.go(-1)">Go 1 step back</a></p>
      <p><a href="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ) ?>">Go to your site</a> | <a href="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ).'/admin_index.php' ?>">Go to your site's administration</a></p>
    </div>
  </div>
</div>
<?php

  }
}
else
{

?>
<div class="blockform">
  <h2><span>Mod installation</span></h2>
  <div class="box">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?foo=bar">
      <div><input type="hidden" name="form_sent" value="1" /></div>
      <div class="inform">
        <p>This script will update your database to work with the following modification:</p>
        <p><strong>Mod title:</strong> <?php echo pun_htmlspecialchars( $mod_title.' '.$mod_version ) ?></p>
        <p><strong>Author:</strong> <?php echo pun_htmlspecialchars( $author ) ?> (<a href="mailto:<?php echo pun_htmlspecialchars( $author_email ) ?>"><?php echo pun_htmlspecialchars( $author_email ) ?></a>)</p>
        <p><strong>Disclaimer:</strong> Mods are not officially supported by FluxBB. Mods generally can't be uninstalled without running SQL queries manually against the database. Make backups of all data you deem necessary before installing.</p>
<?php if ( $mod_restore ): ?>
        <p>If you've previously installed this mod and would like to uninstall it, you can click the Restore button below to restore the database.</p>
<?php endif; ?>
<?php if ( $version_warning ): ?>
        <p style="color: #a00"><strong>Warning:</strong> The mod you are about to install was not made specifically to support your current version of FluxBB (<?php echo $pun_config['o_cur_version']; ?>). This mod supports FluxBB versions: <?php echo pun_htmlspecialchars( implode( ', ', $fluxbb_versions ) ); ?>. If you are uncertain about installing the mod due to this potential version conflict, contact the mod author.</p>
<?php endif; ?>
      </div>
      <p class="buttons"><input type="submit" name="install" value="Install" /><?php if ( $mod_restore ): ?><input type="submit" name="restore" value="Restore" /><?php endif; ?></p>
    </form>
  </div>
</div>
<?php

}

?>

</div>
</div>

</body>
</html>
<?php

// End the transaction
$db->end_transaction();

// Close the db connection ( and free up any result data )
$db->close();