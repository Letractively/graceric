<?php
/* Graceric
*  Author: ericfish
*  File: /admin/saveabout.php
*  Usage: Save About Content
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
// postback and save
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

$postArray = &$_POST;
$post_content = $postArray['EditorAccessibility'];

saveAboutOption($post_content);

header("location:index.php");

?>