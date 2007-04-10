<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/header.php
*  Usage: Default Header Template
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml/DTD/xhtml-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?get_blog_charset();?>" />
  <meta name="author" content="<?get_blog_author();?>" />
  <meta name="copyright" content="<?get_blog_author();?>" />
  <meta name="title" content="<?php get_blog_title(); ?>" />
  <meta name="description" content="<?php get_blog_subtitle(); ?>" />
  <meta name="keywords" content="<?php get_blog_keywords(); ?>" />
  <link rel="stylesheet" href="./<?=TPPATH?>/frame.css" type="text/css" />
  <link rel="stylesheet" href="./<?=TPPATH?>/color.css" type="text/css" />
  <link id="RSSLink" title="RSS" type="application/rss+xml" rel="alternate" href="<?get_blog_rsslink();?>"></link>
  <title><?php the_title(); ?></title>
  
<?php 
global $xajax;
if(isset($xajax)) $xajax->printJavascript(WPINC.'/');
?>
<script src="./<?=TPBASEPATH?>/ajax.js" type="text/javascript"></script>
</head>

<body>
<div id="frame">
	<div id="contentheader">
		<A href="<?get_blog_base_url();?>" class="grey"><img src="./<?=TPPATH?>/pic/title.gif" border="0"></A>
	</div>