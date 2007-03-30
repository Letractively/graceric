<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/search.php
*  Usage: Default Search Template
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

// Instantiate the xajax object.  No parameters defaults requestURI to this page, method to POST, and debug to off

$xajax = new xajax(); 

//$xajax->debugOn(); // Uncomment this line to turn debugging on

// Specify the PHP functions to wrap. The JavaScript wrappers will be named xajax_functionname
$xajax->registerFunction("processForm");

// Process any requests.  Because our requestURI is the same as our html page,
// this must be called before any headers or HTML output have been sent
$xajax->processRequests();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml/DTD/xhtml-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=GB2312" />
  <link rel="stylesheet" href="./<?=TPPATH?>/frame.css" type="text/css" />
  <link rel="stylesheet" href="./<?=TPPATH?>/color.css" type="text/css" />
  <link rel="alternate" type="application/atom+xml" title="_blank" href="atom.xml" />
  <title><?php the_title(); ?></title>
  <?php $xajax->printJavascript(WPINC.'/'); ?>
  <script type="text/javascript">
		function submitSearch()
		{
			if(window.navigator.userAgent.indexOf("MSIE")>=1) {
			xajax.$('submit').disabled=true;
			xajax.$('submit').value="please wait...";
			}
			xajax_processForm(xajax.getFormValues("searchForm"));
			return false;
		}
  </script>
</head>

<body>
<div id="frame">
	<div id="contentheader">
		<A href="./"><img src="./<?=TPPATH?>/pic/title.gif" border="0"></A>
	</div>

<?php get_leftbar(); ?>

	<div id="contentcenter">
	<div class="time">
	(space separated keywords)
	<br/>
	<!-- Begin PicoSearch Query Box --> 
  <form id="searchForm" action="javascript:void(null);" onsubmit="submitSearch();">
   <input class="formfield2" id="keyword" name="keyword" size="25" />
   &nbsp;&nbsp;&nbsp;<input style="font-size:9px; font-weight: bold;" type="submit" name="submit" value="Search"/>
  </form>
  
    </div>	
    
	<div id="div1" name="div1" class="archivepage">&#160;</div>
	
	<div class="time">
	This page is powered by <a target='_blank' href='http://www.xajaxproject.org/'>xajax</a>.
	</div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

	
	
	</div>

<?php get_footer(); ?>