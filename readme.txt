##
##        Mod title:  Forbidden Passwords
##
##      Mod version:  1.0
##  Works on FluxBB:  1.5.4  => 1.5.0
##                    1.4.11 => 1.4.0
##
##     Release date:  2013-10-23
##
##           Author:  Daniel Rokven (rokven@gmail.com)
##
##      Description:  Makes it possible that users cannot use some kind passwords
##
##   Repository URL:  https://fluxbb.org/resources/mods/?????/
##
##   Affected files:  register.php
##                    profile.php
##
##       Affects DB:  Yes
##
##       DISCLAIMER:  Please note that "mods" are not officially supported by
##                    FluxBB. Installation of this modification is done at 
##                    your own risk. Backup your forum database and any and
##                    all applicable files before proceeding.
##

#
#---------[ 1. UPLOAD ]-------------------------------------------------------
#

install_mod.php to /
files/ to /

#
#---------[ 2. RUN ]----------------------------------------------------------
#

install_mod.php

#
#---------[ 3. DELETE ]-------------------------------------------------------
#

install_mod.php

#
#---------[ 4. OPEN ]---------------------------------------------------------
#

register.php

#
#---------[ 5. FIND ]---------------------------------------------------------
#

	check_username($username);

#
#---------[ 6. AFTER ADD ]----------------------------------------------------
#

	// Begin Forbidden Passwords
	include PUN_ROOT.'/plugins/forbidden-passwords/register.php';
	// End Forbidden Passwords

#
#---------[ 7. FIND ]---------------------------------------------------------
#

		$errors[] = $lang_prof_reg['Pass not match'];

#
#---------[ 8. AFTER ADD ]----------------------------------------------------
#

	else if ( $check )
	{
		$errors[] = sprintf( $lang_fp['password error'], $password1);
	}

#
#---------[ 4. OPEN ]---------------------------------------------------------
#

profile.php

#
#---------[ 9. FIND  ]--------------------------------------------------------
#

$new_password2 = pun_trim($_POST['req_new_password2']);

#
#---------[ 10. AFTER ADD ]---------------------------------------------------
#

		// Begin Forbidden Passwords
		include PUN_ROOT.'/plugins/forbidden-passwords/profile.php';
		// End Forbidden Passwords

#
#---------[ 11. FIND ]--------------------------------------------------------
#

message($lang_prof_reg['Pass too short']);

#
#---------[ 12. AFTER ADD ]---------------------------------------------------
#

		else if ( $check )
		{
			message( sprintf( $lang_fp['password error'], $new_password1 ) );
		}

#
#---------[ 13. GO TO ]-------------------------------------------------------
#

Go to the administration page where you can find the plugin options.