<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/feed.php
*  Usage: Default Feed Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: feed.php 13 2007-03-30 07:52:56Z ericfish $
*  $LastChangedDate: 2007-03-30 15:52:56 +0800 (Fri, 30 Mar 2007) $
*  $LastChangedRevision: 13 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/gc-themes/default/feed.php $
*/

global $db_query;
$feed = $db_query->query_vars['feed'];

// Remove the pad, if present.
$feed = preg_replace('/^_+/', '', $feed);

if ($feed == '' || $feed == 'feed') {
    $feed = 'rss2';
}

switch ($feed) {
    case 'rss2':
        require(ABSPATH . 'gc-rss2.php');
        break;
    case 'comment':
        require(ABSPATH . 'gc-commentsrss2.php');
        break;
    default:
        require(ABSPATH . 'gc-rss2.php');
        break;        
    }
?>