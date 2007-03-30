<?php
/* Graceric
*  Author: ericfish
*  File: /gc-header.php
*  Usage: template configuration
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

require_once( dirname(__FILE__) . '/gc-config.php');

require_once(ABSPATH.'gc-settings.php');

// Template redirection
if ( defined('WP_USE_THEMES') && constant('WP_USE_THEMES') ) {
	if (is_archive()&&is_month()) {
		include(TEMPLATEPATH . '/index.php');
	}
	elseif (is_archive()&&!is_month()) {
		include(TEMPLATEPATH . '/archive.php');
	}
	elseif (is_tags()) {
		include(TEMPLATEPATH . '/tags.php');
	}
	elseif (is_about()) {
		include(TEMPLATEPATH . '/about.php');
	}
	elseif (is_tag()) {
		include(TEMPLATEPATH . '/tag.php');
	}
	elseif (is_search()) {
		include(TEMPLATEPATH . '/search.php');
	}
	elseif (is_links()) {
		include(TEMPLATEPATH . '/links.php');
	}
	elseif (is_tag()) {
		include(TEMPLATEPATH . '/tag.php');
	}
	elseif (is_feed()) {
		include(TEMPLATEPATH . '/feed.php');
	}
	elseif( file_exists(TEMPLATEPATH . "/index.php") ) {
		include(TEMPLATEPATH . '/index.php');
	}
}

?>
