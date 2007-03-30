<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/index.php
*  Usage: Default Index Template
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

	<!-- // loop start -->
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
		<div class="date">	
			<img src="./<?=TPPATH?>/pic/sq.gif" width="7" height="7"> <?php the_date("l, F d, Y"); ?>
		</div>

		<div class="blogbody">
		<P><font color="#999999"><b><?php the_post_title(); ?></b></font>
		
		<span class='archivepage'><font color="#999999">
		(tags:<?php get_post_tags();?>)
		</font></span></P>
		
			<P>
				<?php the_content(); ?>
			</P>
			
			<div id="comments">
				<p>
					<?php the_comment(); ?>
				</p>
				<BR>
			</div>
		</div>

	<?php endwhile; else: ?>
		<?php header("location:404.php"); ?>
	<?php endif; ?>

				<span class="lastpost">
					<? recent_post_links(); ?>
				</span>
				<BR><BR><BR><BR><BR><BR>
	</div>

<?php get_footer(); ?>