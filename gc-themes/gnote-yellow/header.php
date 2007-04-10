<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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

  <!-- Hack to avoid flash of unstyled content in IE -->
  <script> </script>
</head>

<body id="twocolumn-left">
  <div id="container">
    <div class="wrapper">
      <div id="header">
        <div class="wrapper">
          <h1 id="page-title"><div id='g_title'><A class="g_title" href="<?get_blog_base_url();?>"><?php get_blog_title(); ?></A></div></h1>
          <div style="clear: both"></div>
          <p class="description"><div id='g_description'><p><?php get_blog_subtitle(); ?></p></div></p>
          <div style="clear: both"></div>
        </div>
      </div>
      <!-- /wrapper --><!-- /header -->
      
      