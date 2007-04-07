<?php
/* Graceric
*  Author: ericfish
*  File: /gc-commentsrss2.php
*  Usage: Lastest comments rss feed
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

if (empty($feed)) {
	$blog = 1;
	$feed = 'rss2';
	$doing_rss = 1;
	require('gc-header.php');
}

header('Content-type: text/xml; charset=' . get_settings('charset'), true);
$more = 1;

?>
<?php echo '<?xml version="1.0" encoding="'.get_settings('charset').'"?'.'>'; ?>

<!-- generator="graceric blog" -->
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
>

<channel>
	<title><?php echo(bloginfo_rss('blog_title').": Lastest Comments"); ?></title>
	<link><?php bloginfo_rss('base_url') ?></link>
	<description><?php bloginfo_rss("blog_subtitle") ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodifiedcomment(), false); ?></pubDate>
	<generator>http://www.ericfish.com/graceric/</generator>
	<language><?php echo get_option('rss_language'); ?></language>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<item>
		<title><?php echo("New Blog Comment By ");comment_author_rss(); ?></title>
		<link><?php comment_link_rss(); ?></link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', comment_date_rss('Y-m-d H:i:s', false), false); ?></pubDate>
		<dc:creator><?php comment_author_rss() ?></dc:creator>

		<guid><?php comment_link_rss(); ?></guid>

		<description><![CDATA[<?php comment_text_rss() ?>]]></description>

	</item>
	<?php endwhile; ?>
	<?php endif; ?>
</channel>
</rss>
