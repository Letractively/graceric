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
<?php
if(user_is_auth())
{
    $xajax = new xajax();
    //$xajax->debugOn(); // Uncomment this line to turn debugging on
    $xajax->registerFunction("saveSpamComment");
    $xajax->processRequests();
}
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
		<P><? get_lock_icon();?> <font color="#999999"><b><?php the_post_title(); ?></b></font>
		
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
		<?php echo("<div class=\"blogbody\"><P>您访问的页面不存在，<br/>请去<a href=\"./?search\">搜索</a>页面查找相关的内容。</P></div><br/><br/><br/><br/><br/><br/><br/>"); ?>
	<?php endif; ?>

				<span class="lastpost">
					<? recent_post_links(); ?>
				</span>
				<BR><BR><BR><BR><BR><BR>
	</div>

<?php get_footer(); ?>