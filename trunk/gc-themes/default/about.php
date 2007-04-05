<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/about.php
*  Usage: Default About Template
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

<div class="date">	
		<img src="./<?=TPPATH?>/pic/sq.gif" width="7" height="7"> <?php get_blog_about_title(); ?>
	</div>	
<BR>
    <!-- Begin .post -->

<div class="blogbody">
<p>
	<?php get_blog_about_text(); ?><br/><br/><br/><br/><br/><br/>
</p>
</div>

	</div>	

<?php get_footer(); ?>