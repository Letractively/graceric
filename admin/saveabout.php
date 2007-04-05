<?php
// postback and save
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

$postArray = &$_POST;
$post_content = $postArray['EditorAccessibility'];

saveAboutOption($post_content);

header("location:index.php");

?>