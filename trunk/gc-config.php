<?php
/* Graceric
*  Author: ericfish
*  File: /gc-config.php
*  Usage: database configuration
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

// ** MySQL settings ** //
define('DB_NAME', 'gc');     // The name of the database
define('DB_USER', '12721db');     // Your MySQL username
define('DB_PASSWORD', '1q2w3e4r'); // ...and password
define('DB_HOST', 'localhost');     // 99% chance you won't need to change this value

/* Stop editing. */

define('ABSPATH', dirname(__FILE__).'/');

define('WPINC', 'gc-includes');
require_once (ABSPATH . WPINC . '/gcdb.class.php');

// Table names
$gcdb->posts            = 'gcdb_posts';
$gcdb->users            = 'gcdb_users';
$gcdb->tags       		= 'gcdb_tags';
$gcdb->post2tag         = 'gcdb_post2tag';
$gcdb->comments         = 'gcdb_comments';
$gcdb->links            = 'gcdb_links';
$gcdb->options          = 'gcdb_options';
$gcdb->id          		= 'gcdb_id';
$gcdb->spams          	= 'gcdb_spams';

?>