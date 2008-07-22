<?php
/* Graceric
*  Author: ericfish
*  File: /admin/logout.php
*  Usage: Clear session and Logout
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id$
*  $LastChangedDate$
*  $LastChangedRevision$
*  $LastChangedBy$
*  $URL$
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');
user_logout();
user_redirect('login.php');
?>