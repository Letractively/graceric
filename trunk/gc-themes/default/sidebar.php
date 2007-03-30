<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/sidebar.php
*  Usage: Default Sidebar Template
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


	<div id="contentleft">
		<span class="time">
			<?php if(is_page()||is_home()): ?>JOURNAL
			<?php else: ?>
			<a href="./">journal</a>
			<?php endif; ?>
			
			<?php if(is_archive()): ?><BR><BR>ARCHIVE
			<?php else: ?>
			<BR><BR><a href="./?archive">archive</a>
			<?php endif; ?>
			
			<?php if(is_search()): ?><BR><BR>SEARCH
			<?php else: ?>
			<BR><BR><a href="./?search">search</a>
			<?php endif; ?>
			
			<?php if(is_about()): ?><BR><BR>ABOUT
			<?php else: ?>
			<BR><BR><a href="./?about">about</a>
			<?php endif; ?>
			
			<?php if(is_links()): ?><BR><BR>LINKS
			<?php else: ?>
			<BR><BR><a href="./?links">links</a>
			<?php endif; ?>
			
			<?php if(is_tags()): ?><BR><BR>TAGS
			<?php else: ?>
			<BR><BR><a href="./?tags">tags</a>
			<?php endif; ?>
			
			<BR>
			
			<BR><BR><a href="./?feed" target=_blank>rss</A>
			
			
			<BR><BR><BR>
			<BR><BR><a href="http://www.ericfish.com" target=_blank>ericfish</A>
			<BR><BR><a href="http://creativecommons.org/licenses/by-nc-sa/2.0/" target=_blank>CC Copyright</a>
			<BR><BR><BR>
		</span>
	</div>