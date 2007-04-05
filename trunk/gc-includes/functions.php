<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/functions.php
*  Usage: Main Functions (Page defination related)
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

/*
* Page define functions
* Posts functions
* Template functions
* DateTime Format function
* Options functions
* Tags functions
* Archive functions
* Links functions
* About page functions
* Search functions
* Feed functions
* Security functions
*/

/***** Page define functions (check which page it is) *****/

function is_home () {
    global $db_query;
    return $db_query->is_home;
}

function is_page () {
    global $db_query;
    return $db_query->is_page;
}

function is_archive () {
    global $db_query;
    return $db_query->is_archive;
}

function is_tags () {
    global $db_query;
    return $db_query->is_tags;
}

function is_search () {
    global $db_query;
    return $db_query->is_search;
}

function is_about () {
    global $db_query;
    return $db_query->is_about;
}

function is_links () {
    global $db_query;
    return $db_query->is_links;
}

function is_tag () {
    global $db_query;
    return $db_query->is_tag;
}

function is_month () {
    global $db_query;
    return $db_query->is_month;
}

function is_feed () {
    global $db_query;
    return $db_query->is_feed;
}

/***** Posts functions (They will all be called by template pages) *****/

function have_posts() {
    global $db_query;

    return $db_query->have_posts();
}

function the_post() {
    global $db_query;
    $db_query->the_post();
}

function the_content() {
    global $post;
	$the_content = $post->post_content;
    echo $the_content;
}

function the_ID($echo = true) {
    global $post;
	$the_ID = $post->ID;
	if ($echo)
    	echo $the_ID;
	else
		return $the_ID;
}

function the_post_date($echo = true) {
    global $post;
	$the_post_date = $post->post_date;
	if ($echo)
    	echo $the_post_date;
	else
		return $the_post_date;
}


function the_post_title($echo = true) {
    global $post;
	$the_post_title = $post->post_title;
	if ($echo)
    	echo $the_post_title;
	else
		return $the_post_title;
}

function the_date($d='', $echo = true) {
    global $post;
    $the_date = '';

	if ($d=='') {
	$the_date .= mysql2date('d.m.y', $post->post_date);	// default date format
	} else {
	$the_date .= mysql2date($d, $post->post_date);
	}

    if ($echo) {
        echo $the_date;
    } else {
        return $the_date;
    }
}

function the_comment() {
	
	global $db_query,$error_message,$comm_name,$comm_e_mail,$comm_website;
	
	if (!$db_query->is_comment) {
		
		// $post_time = the_date("g:i A",false);
		$comment_link = show_comment_link();
		echo("<a name='comments'></a><span class='blogkommlink'>$comment_link</span>");
	}
	else {
		// if postback from comment
		if (isset($_REQUEST['button'])) {
			//$comm_name,$comm_e_mail,$comm_website,$comm_content,$notify_comment);
			$db_query->save_comment();
		}
		// Get comment template
		get_comment();
		
		echo $error_message;
	}
}

function the_title($echo=true) {
    global $gcdb,$db_query;
    if(is_page()) {	
		$post_id = $gcdb->escape($db_query->query_vars['q']);
		$request = "SELECT post_title FROM $gcdb->posts WHERE ID=$post_id";
		
		$post_title = $gcdb->get_var($request);
		$blog_title = get_option('blog_title').": ".$post_title;
    }
    elseif(is_archive()) {	
    	$month = "";
    	if(isset($db_query->query_vars['month']))
			$month .= $db_query->query_vars['month'];
		
		$blog_title = get_option('blog_title').": Archive ".$month;
    }
    elseif(is_search()) {	
		
		$blog_title = get_option('blog_title').": Search";
    }
    elseif(is_about()) {	
		
		$blog_title = get_option('blog_title').": About";
    }
    elseif(is_tags()) {	
		
		$blog_title = get_option('blog_title').": Tags";
    }
    elseif(is_links()) {	
		
		$blog_title = get_option('blog_title').": Links";
    }
    elseif(is_tag()) {	
		$the_tag = "";
		$the_tag .= $db_query->query_vars['tag'];
		
		$blog_title = get_option('blog_title')." -> tag -> ".$the_tag;
    }
    else {
    	$blog_title = get_option('blog_title');
    }
    
	if ($echo)
    	echo $blog_title;
	else
		return $blog_title;

}

/***** Get Template functions *****/

function get_blog_title() {
	echo(get_option('blog_title'));
}

function get_blog_subtitle() {
	echo(get_option('blog_subtitle'));
}

function get_blog_base_url() {
    echo(get_option('base_url'));
}

function get_blog_about_text() {
    echo(get_option('about_text'));
}

function get_blog_about_title() {
    echo(get_option('about_title'));
}

function get_blog_author() {
    echo(get_option('blog_author'));
}

function get_blog_keywords() {
    echo(get_option('keywords'));
}

// Get the rss link from option
function get_blog_rsslink()
{
    $rss_link = get_option('rss_link');
    if($rss_link==""){
        $rss_link = get_option('base_url')."/?feed";
    }
    echo $rss_link;
}

// if the post is private, display the lock icon
function get_lock_icon(){
    global $post;
    $show_in_home = $post->show_in_home;
    if($show_in_home=='no')
    {
        echo("<img align=\"absmiddle\" src=\"".TPBASEPATH."/lock.gif\" alt=\"[Private] Only you can see this post.\"/>");
    }
}

function get_header() {
	if ( file_exists( TEMPLATEPATH . '/header.php') )
		require_once( TEMPLATEPATH . '/header.php');
	else
		require_once( ABSPATH . 'gc-themes/default/header.php');
}

function get_leftbar() {
	if ( file_exists( TEMPLATEPATH . '/sidebar.php') )
		require_once( TEMPLATEPATH . '/sidebar.php');
	else
		require_once( ABSPATH . 'gc-themes/default/sidebar.php');
}

function get_comment() {
	if ( file_exists( TEMPLATEPATH . '/comment.php') )
		require_once( TEMPLATEPATH . '/comment.php');
	else
		require_once( ABSPATH . 'gc-themes/default/comment.php');
}

function get_footer() {
	if ( file_exists( TEMPLATEPATH . '/footer.php') )
		require_once( TEMPLATEPATH . '/footer.php');
	else
		require_once( ABSPATH . 'gc-themes/default/footer.php');
}

// Get prv post link and nxt post link
function recent_post_links(){
	
	global $gcdb,$db_query;
	$cuurent_post_date = the_post_date(false);
	
	if(isset($db_query->query_vars['tagid'])) {
		$tag_id = $gcdb->escape($db_query->query_vars['tagid']);
		
		$request_prv = "SELECT a.ID, a.post_title FROM $gcdb->posts a,$gcdb->post2tag b";
		$request_prv .= " WHERE a.post_date < '".$cuurent_post_date;
		$request_prv .= "' AND post_status = 'publish'";
		$request_prv .= " AND b.tag_id = ".$tag_id;
		$request_prv .= " AND a.ID = b.post_id";
		if(!user_is_auth())
		{
		     $request_prv .= " AND show_in_home = 'yes'";
		}
		$request_prv .= " ORDER BY a.post_date,a.ID DESC";
		$request_prv .= " LIMIT ".get_option('prev_links');
		$prv_posts = $gcdb->get_results($request_prv);
		
		$prv_posts_num = count($prv_posts);
		
		if($prv_posts_num==0)
		{
			return "";
		}
		
		else{
			if(is_home()||is_page()) {
			    for ($i=0;$i<$prv_posts_num;$i++) { 
			        $prv_post = $prv_posts[$i];	    
				    echo("&laquo;&laquo;&laquo; <a href='?q=$prv_post->ID&tagid=$tag_id' title='click to view previous blog'>$prv_post->post_title</a><br/><br/>");
			    }
			}
		}
	}
	
	else {
		$request_prv = "SELECT ID, post_title FROM $gcdb->posts";
		$request_prv .= " WHERE post_date < '".$cuurent_post_date;
		$request_prv .= "' AND post_status = 'publish'";
		if(!user_is_auth())
		{
		     $request_prv .= " AND show_in_home = 'yes'";
		}
		$request_prv .= " ORDER BY post_date DESC";
		$request_prv .= " LIMIT ".get_option('prev_links');
	
		$prv_posts = $gcdb->get_results($request_prv);
		
		$prv_posts_num = count($prv_posts);
		
		if($prv_posts_num==0)
		{
			return "";
		}
		
		else{
			if(is_home()||is_page()) {
			    for ($i=0;$i<$prv_posts_num;$i++) { 
			        $prv_post = $prv_posts[$i];
				    echo("&laquo;&laquo;&laquo; <a href='?q=$prv_post->ID' title='click to view previous blog'>$prv_post->post_title</a><br/><br/>");
			    }
			}
		}
	}
}

// Get the rss link from option
function get_blog_charset()
{
    $charset = get_option('charset');
    if($charset==""){
        $charset = "gb2312";
    }
    echo $charset;
}

/***** DateTime Format functions *****/

function mysql2date($dateformatstring, $mysqlstring, $translate = true) {
	global $month, $weekday, $month_abbrev, $weekday_abbrev;
	$m = $mysqlstring;
	if (empty($m)) {
		return false;
	}
	$i = mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4)); 
	if (!empty($month) && !empty($weekday) && $translate) {
		$datemonth = $month[date('m', $i)];
		$datemonth_abbrev = $month_abbrev[$datemonth];
		$dateweekday = $weekday[date('w', $i)];
		$dateweekday_abbrev = $weekday_abbrev[$dateweekday]; 		
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit($dateweekday_abbrev), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit($datemonth_abbrev), $dateformatstring);
	
		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @date($dateformatstring, $i);
	if (!$j) {
	// for debug purposes
	//	echo $i." ".$mysqlstring;
	}
	return $j;
}

// give it a date, it will give you the same date as GMT
function get_gmt_from_date($string) {
  // note: this only substracts $time_difference from the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_gmt = gmdate('Y-m-d H:i:s', $string_time - get_settings('gmt_offset') * 3600);
  return $string_gmt;
}

/* Options functions */

function get_settings($setting) {
	global $gcdb;

	$option = $gcdb->get_var("SELECT option_value FROM $gcdb->options WHERE option_name = '$setting'");

	if (!$option) :
		return false;
	endif;
/*
	@ $kellogs = unserialize($option);
	if ($kellogs !== FALSE)
		return $kellogs;
	else return $option;*/
    return $option;

}

function get_option($option) {
	return get_settings($option);
}

function update_option($option_name, $newvalue) {
	global $gcdb;
	
	if ( is_array($newvalue) || is_object($newvalue) )
		$newvalue = serialize($newvalue);

	$newvalue = trim($newvalue); // I can't think of any situation we wouldn't want to trim

    // If the new and old values are the same, no need to update.
    if ($newvalue == get_option($option_name)) {
        return true;
    }

	// If it's not there add it
	if ( !$gcdb->get_var("SELECT option_name FROM $gcdb->options WHERE option_name = '$option_name'") )
		add_option($option_name);

	$newvalue = $gcdb->escape($newvalue);
	$gcdb->query("UPDATE $gcdb->options SET option_value = '$newvalue' WHERE option_name = '$option_name'");
	return true;
}

function add_option($name, $value = '', $description = '', $autoload = 'yes') {
	global $gcdb;
	$original = $value;
	if ( is_array($value) || is_object($value) )
		$value = serialize($value);

	if( !$gcdb->get_var("SELECT option_name FROM $gcdb->options WHERE option_name = '$name'") ) {
		$name = $gcdb->escape($name);
		$value = $gcdb->escape($value);
		$description = $gcdb->escape($description);
		$gcdb->query("INSERT INTO $gcdb->options (option_name, option_value, option_description, autoload) VALUES ('$name', '$value', '$description', '$autoload')");
		
	}
	return;
}

function delete_option($name) {
	global $gcdb;
	// Get the ID, if no ID then return
	$option_id = $gcdb->get_var("SELECT option_id FROM $gcdb->options WHERE option_name = '$name'");
	if (!$option_id) return false;
	$gcdb->query("DELETE FROM $gcdb->options WHERE option_name = '$name'");
	return true;
}

/***** Tags functions *****/
function get_post_tags(){
	
	global $gcdb;
	$cuurent_postID = the_ID(false);
	$cuurent_postID = $gcdb->escape($cuurent_postID);
	
	$request = "SELECT tag_name,tag_nicename FROM $gcdb->tags LEFT JOIN $gcdb->post2tag ON $gcdb->tags.tag_id = $gcdb->post2tag.tag_id WHERE $gcdb->post2tag.post_id = $cuurent_postID";
	
	$tags = $gcdb->get_results($request);
	$numbers = count($tags);
	
	for($i=0;$i<$numbers;$i++)
	{
		$tag = $tags[$i];
		$tag_name = $tag->tag_name;
		$tag_nicename = $tag->tag_nicename;
		echo " <A class='grey' HREF='?tag=$tag_name' title='$tag_nicename'>$tag_name</A>";
	}
}

// tp get tag links
function get_tags(){
	global $gcdb;
	$request = "SELECT a.tag_name,a.tag_nicename, count(b.post_id) AS title FROM $gcdb->tags a, $gcdb->post2tag b,$gcdb->posts c WHERE a.tag_id=b.tag_id AND b.post_id=c.ID AND c.post_status='publish' GROUP BY b.tag_id ORDER BY title DESC";
	
	$tags = $gcdb->get_results($request);
	$numbers = count($tags);
	
	for($i=0;$i<$numbers;$i++)
	{
		$tag = $tags[$i];
		$tag_name = $tag->tag_name;
		$tag_nicename = $tag->tag_nicename;
		$title = $tag->title;
		
		echo " <a href='?tag=$tag_name' title='$title'>$tag_name</a> <a href='./?feed=$tag_name' class='grey' target='_blank'><img src='./gc-themes/o_rss.gif' border='0' height='16' width='16' align='absbottom'/></a> <br><br> ";
	}
}

// tp get links for 1 tag
function get_tag(){
	global $gcdb,$db_query;
	
	$tag_name = $gcdb->escape($db_query->query_vars['tag']);
	$request = "SELECT a.ID,a.post_date,a.post_title,b.tag_ID FROM $gcdb->posts a, $gcdb->tags b, $gcdb->post2tag c WHERE b.tag_id=c.tag_id AND b.tag_name = '$tag_name' AND c.post_id=a.ID AND a.post_status='publish'";
	$request .= " ORDER BY a.post_date DESC";
	
	$posts = $gcdb->get_results($request);
	$numbers = count($posts);
	
	for($i=0;$i<$numbers;$i++)
	{
		$post = $posts[$i];
		$post_ID = $post->ID;
		$post_date = $post->post_date;
		$post_date = mysql2date("l, F d, Y", $post_date);
		$post_title = $post->post_title;
		$post_tagid = $post->tag_ID;
		$result = "<div class='date'><img src='./".TPPATH."/pic/sq.gif' width='7' height='7'> $post_date</div><div class='archivepage'><a href='?q=$post_ID&tagid=$post_tagid' title='permanent link'>$post_title</a></div>\n";
		echo $result;
	}
}

/***** Archive functions *****/
function get_archives(){
	
	global $gcdb;
	$request = "SELECT DISTINCT DATE_FORMAT(post_date, '%M %Y') AS date_dis,DATE_FORMAT(post_date, '%Y%m') AS date_url FROM $gcdb->posts WHERE post_status='publish' AND show_in_home='yes' ORDER BY date_url DESC";
	
	$months = $gcdb->get_results($request);
	$numbers = count($months);
	
	for($i=0;$i<$numbers;$i++)
	{
		$month = $months[$i];
		$date_dis = $month->date_dis;
		$date_url = $month->date_url;
		echo " <a href='?archive&month=$date_url'>$date_dis</a><br><br> ";
	}
}

/***** Links functions *****/
function get_links(){
	global $gcdb;
	$request = "SELECT link_name,link_url FROM $gcdb->links ORDER BY link_rating";
	
	$links = $gcdb->get_results($request);
	$numbers = count($links);
	
	for($i=0;$i<$numbers;$i++)
	{
		$link = $links[$i];
		$link_name = $link->link_name;
		$link_url = $link->link_url;
		echo "<a href='$link_url' target='_blank'>$link_name</a><br><br>\n";
	}
}

/***** About page functions *****/
function get_about() {
	$about_text = get_option('about_text');
	echo $about_text;
}

/***** Search functions *****/
function processSearchForm($aFormValues)
{
	global $gcdb;
	
	$keyword = trim($aFormValues['keyword']);
	$keyword = iconv( "UTF-8", "gb2312" , $keyword);
	$keyword = $gcdb->escape($keyword);
	$keywords = explode(" ", $keyword);
	$keyword_count = count($keywords);


	$request = "SELECT ID,post_title from $gcdb->posts WHERE (";
	
	for($i=0;$i < $keyword_count;$i++)
	{
		$kw = $keywords[$i];
		if($i!=0)
			$request .= " OR ";
		$request .= "post_title LIKE '%$kw%' OR post_content LIKE '%$kw%'";
	}
	$request .= ") AND post_status='publish'";
	
	$search_results = $gcdb->get_results($request);
	$numbers = count($search_results);
	
	$text = '';
	
	for($i=0;$i<$numbers;$i++)
	{
		$search_result = $search_results[$i];
		$post_ID = $search_result->ID;
		$post_title = $search_result->post_title;
		$text.= "<a href='?q=$post_ID'>$post_title</a><br><br>\n";
	}
	
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("div1","innerHTML",$text);
	$objResponse->addAssign("submit","value","search");
	$objResponse->addAssign("submit","disabled",false);
	
	return $objResponse;
}

/**** Feed functions ****/
function createFeed(){
	global $gcdb,$PHP_SELF,$db_query;
	
	$tag_name = $gcdb->escape($db_query->query_vars['feed']);

	$rss = new UniversalFeedCreator();

	if($tag_name == 'comment'){
		$rss->title = get_option('blog_title')." new comments rss"; 
		$rss->description = "blog comments"; 
		$rss->link = get_option('base_url'); 
		$rss->syndicationURL = get_option('base_url').$PHP_SELF; 
		$rss->cssStyleSheet = "http://www.w3.org/2000/08/w3c-synd/style.css";
		
		$request = "SELECT a.comment_post_id, a.comment_author, a.comment_content, UNIX_TIMESTAMP(a.comment_date) AS comment_date FROM $gcdb->comments a";
		$request .= " WHERE a.comment_approved = '1'";
		$request .= " ORDER BY a.comment_date DESC LIMIT 20";
		$posts = $gcdb->get_results($request);
		$post_num = count($posts);
		
		for ($i=0;$i<$post_num;$i++) { 
			$item = new FeedItem();
			$post = $posts[$i];
			$item->title = 'comment by '.$post->comment_author;
			$item->link = get_option('base_url')."/?q=".$post->comment_post_id;
			$item->description = $post->comment_content;
			$item->date = (int)$post->comment_date;
			$item->author = $post->comment_author;
			$item->authorEmail = get_option('admin_email');
			 
			$rss->addItem($item); 
		}
	}
	else{
		if($tag_name == ''){
			$tag_name = '%';
			$rss->title = get_option('blog_title');
		}
		else
			$rss->title = get_option('blog_title').": ".$tag_name; 
		$rss->description = get_option('blog_title'); 
		$rss->link = get_option('base_url'); 
		$rss->syndicationURL = get_option('base_url').$PHP_SELF; 
		$rss->cssStyleSheet = "http://www.w3.org/2000/08/w3c-synd/style.css";
		
		//$image = new FeedImage(); 
		//$image->title = get_option('blog_title'); 
		//$image->url = ""; 
		//$image->link = get_option('base_url'); 
		//$image->description = "...";
		//$rss->image = $image;
		
		$request = "SELECT a.ID,a.post_title,a.post_content,UNIX_TIMESTAMP(a.post_date) AS post_date FROM $gcdb->posts a, $gcdb->tags b, $gcdb->post2tag c ";
		$request .= "WHERE b.tag_id=c.tag_id AND b.tag_name LIKE '$tag_name' AND c.post_id=a.ID AND a.post_status='publish' ";
		$request .= "GROUP BY a.ID ";
		$request .= "ORDER BY a.post_date DESC LIMIT 10";
		$posts = $gcdb->get_results($request);
		$post_num = count($posts);
		
		for ($i=0;$i<$post_num;$i++) { 
			$item = new FeedItem(); 
			$post = $posts[$i];
			$item->title = $post->post_title;
			$item->link = get_option('base_url')."/?q=".$post->ID; 
			$item->description = $post->post_content;
			$item->date = (int)$post->post_date;
			$item->source = get_option('base_url'); 
			$item->author = get_option('admin_email');
			$item->comments = get_option('base_url')."/?q=".$post->ID."&comment#comment";
			 
			$rss->addItem($item); 
		}
	}
	
	$rss->saveFeed("RSS2.0");
}

/***** Security functions *****/

// Look for serverconfiguration "get_magic_quotes_gpc"
function proofAddSlashes($in_string)
{
	if (get_magic_quotes_gpc()==1) {
		return $in_string;
	} else {
		return AddSlashes($in_string);
	}
}
?>