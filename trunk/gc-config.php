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
// ** 数据库设置 ** //
define('DB_NAME', 'gc');     // The name of the database
define('DB_USER', 'root');     // Your MySQL username
define('DB_PASSWORD', ''); // ...and password
define('DB_HOST', 'localhost');     // 99% chance you won't need to change this value

// Change the prefix if you want to have multiple blogs in a single database.
// 表前缀
$table_prefix  = 'gcdb_';   // example: 'gcdb_' or 'b2' or 'mylogin_'
$table_charset  = 'gb2312';   // example: 'utf8' or 'gb2312' or 'gbk'

/* Stop editing. */
/* 下面的内容不需要编辑. */

define('ABSPATH', dirname(__FILE__).'/');

define('WPINC', 'gc-includes');
require_once (ABSPATH . WPINC . '/gcdb.class.php');

// Table names
$gcdb->posts            = $table_prefix . 'posts';
$gcdb->users            = $table_prefix . 'users';
$gcdb->tags       		= $table_prefix . 'tags';
$gcdb->post2tag         = $table_prefix . 'post2tag';
$gcdb->comments         = $table_prefix . 'comments';
$gcdb->links            = $table_prefix . 'links';
$gcdb->options          = $table_prefix . 'options';
$gcdb->id          		= $table_prefix . 'id';
$gcdb->spams          	= $table_prefix . 'spams';
$gcdb->x          	    = $table_prefix . 'x';

$gcdb->query("SET NAMES '$table_charset'");

?>