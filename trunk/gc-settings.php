<?php
/* Graceric
*  Author: ericfish
*  File: /gc-setting.php
*  Usage: include libararies
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

require (ABSPATH . WPINC . '/functions.php');
require (ABSPATH . WPINC . '/comment-functions.php');
require (ABSPATH . WPINC . '/xajax.class.php');
require (ABSPATH . WPINC . '/inputfilter.class.php');

require (ABSPATH . WPINC . '/dbquery.class.php');
require (ABSPATH . WPINC . '/feedcreator.class.php');

// Template values
define('TEMPLATEPATH', ABSPATH . '/gc-themes/default');
define('TPPATH', 'gc-themes/default');
?>
