<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/archive.php
*  Usage: Default Archive Template
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

<?php get_header(); ?>

<?php get_leftbar(); ?>

	<div id="contentcenter">

	<div class="time">	 

<?php get_archives(); ?>
	
	</div>	
	
	</div>

<?php get_footer(); ?>