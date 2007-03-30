<?php

/***** Page Check functions (check which kind of page is) *****/

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

function is_tech () {
    global $db_query;
    return $db_query->is_tech;
}

function is_tripreader () {
    global $db_query;
    return $db_query->is_tripreader;
}

function is_feed () {
    global $db_query;
    return $db_query->is_feed;
}

function is_gallery () {
    global $db_query;
    return $db_query->is_gallery;
}

function is_lab () {
    global $db_query;
    return $db_query->is_lab;
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
		$blog_title = "_blank: ".$post_title;
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


/***** Date/Time tags *****/

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

/* Options functions */

function get_settings($setting) {
	global $gcdb;

	$option = $gcdb->get_var("SELECT option_value FROM $gcdb->options WHERE option_name = '$setting'");

	if (!$option) :
		return false;
	endif;

	@ $kellogs = unserialize($option);
	if ($kellogs !== FALSE)
		return $kellogs;
	else return $option;

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

/***** Template Using functions *****/

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
		$request_prv .= " ORDER BY a.post_date DESC";
		$request_prv .= " LIMIT 1";$prv_post = $gcdb->get_row($request_prv);
		
		if($prv_post == "")
		{
			return "";
		}
		
		else{
			if(is_home()||is_page()) {
				echo("&laquo;&laquo;&laquo; <a href='?q=$prv_post->ID&tagid=$tag_id' title='click to view previous blog'>$prv_post->post_title</a><BR>");
			}
		}
	}
	
	else {
		$request_prv = "SELECT ID, post_title FROM $gcdb->posts";
		$request_prv .= " WHERE post_date < '".$cuurent_post_date;
		$request_prv .= "' AND post_status = 'publish'";
		$request_prv .= " AND show_in_home = 'yes'";
		$request_prv .= " ORDER BY post_date DESC";
		$request_prv .= " LIMIT 1";
	
		$prv_post = $gcdb->get_row($request_prv);
		
		if($prv_post == "")
		{
			return "";
		}
		
		else{
			if(is_home()||is_page()) {
				echo("&laquo;&laquo;&laquo; <a href='?q=$prv_post->ID' title='click to view previous blog'>$prv_post->post_title</a><BR>");
			}
		}
	}
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
		$result = "<div class='date'><img src='./".TPPATH."/pic/sq.gif' width='7' height='7'> $post_date</div><span class='archivepage'><a href='?q=$post_ID&tagid=$post_tagid' title='permanent link'>$post_title</a></span>\n";
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

/***** xajax functions *****/
function processForm($aFormValues)
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

/***** Tech page functions *****/
function get_post_nav(){
	global $gcdb,$db_query;
	$url_param = "";
	
	if(isset($db_query->query_vars['p']))
		$page_num = $gcdb->escape($db_query->query_vars['p']);
	else
		$page_num = 1;
		
	$text = "";
	
	if(is_tech()){
		$url_param = "tech&";
		$pagepost_num = get_option('techpage_post_num');
		$request = "SELECT count(a.ID) FROM $gcdb->posts a, $gcdb->post2tag b, $gcdb->tags c";
		$request .= " WHERE a.post_status = 'publish'";
		$request .= " AND a.ID = b.post_id";
		$request .= " AND b.tag_id = c.tag_id";
		$request .= " AND c.tag_name = 'tech'";
	}
	
	if(is_tripreader()){
		$url_param = "tripreader&";
		$pagepost_num = get_option('techpage_post_num');
		$request = "SELECT count(a.ID) FROM $gcdb->posts a, $gcdb->post2tag b, $gcdb->tags c";
		$request .= " WHERE a.post_status = 'publish'";
		$request .= " AND a.ID = b.post_id";
		$request .= " AND b.tag_id = c.tag_id";
		$request .= " AND c.tag_name = 'tripreader'";
	}
	
	$total_num = $gcdb->get_var($request);

	if($total_num == 0)
		echo "";
	else{
		$total_page = $total_num/$pagepost_num;
		$mo = $total_num%$pagepost_num;
		if($mo != 0)
			$total_page = (int)$total_page+1;
		
		$current_page = $page_num;
		$pre_page_num = $current_page-1;
		$nxt_page_num = $current_page+1;
		
		// if not first page, add previous link
		if($current_page!=1)
			$text.= "&laquo;&laquo;&laquo; <a href='?".$url_param."p=$pre_page_num'>Prev</a>";

		if($current_page==6&&$total_page>10)
			$text.= " <a href='?".$url_param."p=1'>1</a>";
			
		// if total page > 10 and current page >= 7 add '...'
		if($total_page > 10&&$current_page>6){
			$text.= " <a href='?".$url_param."p=1'>1</a>";
			$text.= " <a href='?".$url_param."p=2'>2</a>";
			$text.= " ...";
		}
				
		for($i=1;$i<=$total_page;$i++){
			// if not current page, add number and link
			if(($i!=$current_page)&&
				((($i>=$current_page-4)&&($i<=$current_page+4)) || 
				($current_page<6&&$i<11) || 
				($current_page>($total_page-5)&&$i>($total_page-10)))
				)
			{
				$text.= " <a href='?".$url_param."p=$i'>$i</a>";
			}
			// if is current page, only add number
			elseif($i==$current_page)
				$text.= " $i";
		}
		
		// if total page - current page > 7 add '...'
		if(($total_page - $current_page > 5)&&$total_page>10){
			$before_total_page = $total_page -1;
			$text.= " ...";
			$text.= " <a href='?".$url_param."p=$before_total_page'>$before_total_page</a>";
			$text.= " <a href='?".$url_param."p=$total_page'>$total_page</a>";
		}
		
		if(($total_page - $current_page == 5)&&$total_page>10)
			$text.= " <a href='?".$url_param."p=$total_page' >$total_page</a>";

		// if not last page, add next link
		if($current_page!=($total_page))
			$text.= " <a href='?".$url_param."p=$nxt_page_num'>Next</a> &raquo;&raquo;&raquo;";
		
		echo $text;
	}
}

/**** Feed Function ****/
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

/**** XML RPC Function ****/

function user_pass_ok($user_login,$user_pass) {
	global $cache_userdata;
	if ( empty($cache_userdata[$user_login]) ) {
		$userdata = get_userdatabylogin($user_login);
	} else {
		$userdata = $cache_userdata[$user_login];
	}
	return ($user_pass == $userdata->user_pass);
}

function user_pass_ok2($username,$password) {
	global $gcdb;
		$request = "SELECT user_pass FROM gc_users WHERE user_login='$username'"; 
		$member_password = $gcdb->get_var($request);
		if($member_password)
		{
			if($member_password!=$password)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
}

function get_userdatabylogin($user_login) {
	global $cache_userdata, $gcdb;
	if ( !empty($user_login) && empty($cache_userdata[$user_login]) ) {
		$user = $gcdb->get_row("SELECT * FROM $gcdb->users WHERE user_login = '$user_login'"); /* todo: get rid of this intermediate var */
		$cache_userdata[$user->ID] = $user;
		$cache_userdata[$user_login] =& $cache_userdata[$user->ID];
	} else {
		$user = $cache_userdata[$user_login];
	}
	return $user;
}

function get_userdata($userid) {
	global $gcdb, $cache_userdata;
	$userid = (int) $userid;
	if ( empty($cache_userdata[$userid]) && $userid != 0) {
		$cache_userdata[$userid] = $gcdb->get_row("SELECT * FROM $gcdb->users WHERE ID = $userid");
		$cache_userdata[$cache_userdata[$userid]->user_login] =& $cache_userdata[$userid];
	} 

	return $cache_userdata[$userid];
}

function gc_get_single_post($postid = 0, $mode = OBJECT) {
	global $gcdb;

	$sql = "SELECT * FROM $gcdb->posts WHERE ID=$postid";
	$result = $gcdb->get_row($sql, $mode);
	
	// Set categories
	if($mode == OBJECT) {
		$result->post_category = gc_get_post_cats('',$postid);
	} 
	else {
		$result['post_category'] = gc_get_post_cats('',$postid);
	}

	return $result;
}

function gc_get_post_cats($blogid = '1', $post_ID = 0) {
	global $gcdb;
	
	$sql = "SELECT tag_id 
		FROM $gcdb->post2tag 
		WHERE post_id = $post_ID 
		ORDER BY tag_id";

	$result = $gcdb->get_col($sql);

	if ( !$result )
		$result = array();

	return array_unique($result);
}

function gc_get_recent_posts($num = 10) {
	global $gcdb;

	//$sql = "SELECT * FROM $gcdb->posts WHERE post_status IN ('publish', 'draft', 'private') ORDER BY post_date DESC $limit";
	$sql = "SELECT * FROM $gcdb->posts WHERE post_status IN ('publish', 'private') ORDER BY post_date DESC LIMIT 3";
	$result = $gcdb->get_results($sql,ARRAY_A);

	return $result?$result:array();
}

function xmlrpc_getposttitle($content) {
	global $post_default_title;
	if (preg_match('/<title>(.+?)<\/title>/is', $content, $matchtitle)) {
		$post_title = $matchtitle[0];
		$post_title = preg_replace('/<title>/si', '', $post_title);
		$post_title = preg_replace('/<\/title>/si', '', $post_title);
	} else {
		$post_title = $post_default_title;
	}
	return $post_title;
}
	
function xmlrpc_getpostcategory($content) {
	global $post_default_category;
	if (preg_match('/<category>(.+?)<\/category>/is', $content, $matchcat)) {
		$post_category = trim($matchcat[1], ',');
		$post_category = explode(',', $post_category);
	} else {
		$post_category = $post_default_category;
	}
	return $post_category;
}

function xmlrpc_removepostdata($content) {
	$content = preg_replace('/<title>(.+?)<\/title>/si', '', $content);
	$content = preg_replace('/<category>(.+?)<\/category>/si', '', $content);
	$content = trim($content);
	return $content;
}

function current_time($type, $gmt = 0) {
	switch ($type) {
		case 'mysql':
			if ($gmt) $d = gmdate('Y-m-d H:i:s');
			else $d = gmdate('Y-m-d H:i:s', (time() + (get_settings('gmt_offset') * 3600)));
			return $d;
			break;
		case 'timestamp':
			if ($gmt) $d = time();
			else $d = time() + (get_settings('gmt_offset') * 3600);
			return $d;
			break;
	}
}

// give it a date, it will give you the same date as GMT
function get_gmt_from_date($string) {
  // note: this only substracts $time_difference from the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_gmt = gmdate('Y-m-d H:i:s', $string_time - get_settings('gmt_offset') * 3600);
  return $string_gmt;
}

function gc_insert_post($postarr = array()) {
	global $gcdb, $allowedtags;
	
	// export array as variables
	extract($postarr);
	
	$post_name = $post_title;
	$post_author = (int) $post_author;

	// Make sure we set a valid category
	if (0 == count($post_category) || !is_array($post_category)) {
		$post_category = array('1');
	}

	$post_cat = $post_category[0];
	
	if (empty($post_date))
		$post_date = current_time('mysql');
	// Make sure we have a good gmt date:
	if (empty($post_date_gmt)) 
		$post_date_gmt = get_gmt_from_date($post_date);
	if ( empty($post_parent) )
		$post_parent = 0;

	$post_ID = getNextPostId();

	/*
	if ('publish' == $post_status) {
		$post_name_check = $gcdb->get_var("SELECT post_name FROM $gcdb->posts WHERE post_name = '$post_name' AND post_status = 'publish' AND ID != '$post_ID' LIMIT 1");
		if ($post_name_check) {
			$suffix = 2;
			while ($post_name_check) {
				$alt_post_name = $post_name . "-$suffix";
				$post_name_check = $gcdb->get_var("SELECT post_name FROM $gcdb->posts WHERE post_name = '$alt_post_name' AND post_status = 'publish' AND ID != '$post_ID' LIMIT 1");
				$suffix++;
			}
			$post_name = $alt_post_name;
		}
	}
	*/

	$sql = "INSERT INTO $gcdb->posts 
		(ID, post_date, post_modified, post_content, post_title, post_status) 
		VALUES ('$post_ID', '$post_date', '$post_date', '$post_content', '$post_title', '$post_status')";
	
	$result = $gcdb->query($sql);

	idPlusOne();

	// Set GUID
	//$gcdb->query("UPDATE $gcdb->posts SET guid = '" . get_permalink($post_ID) . "' WHERE ID = '$post_ID'");
	
	//gc_set_post_cats('', $post_ID, $post_category);

	// Return insert_id if we got a good result, otherwise return zero.
	return $result ? $post_ID : 0;
}

// Get a new id for next post in the gc_id table
function getNextPostId(){
	global $gcdb;
	$request = "SELECT ID FROM $gcdb->id LIMIT 1";
	$id = $gcdb->get_var($request);
	return $id;
}

function idPlusOne(){
	global $gcdb;
	$request = "UPDATE $gcdb->id SET ID = ID +1";
	$gcdb->query($request);
}

function gc_set_post_cats($blogid = '1', $post_ID = 0, $post_categories = array()) {
	global $gcdb;
	// If $post_categories isn't already an array, make it one:
	if (!is_array($post_categories) || 0 == count($post_categories))
		$post_categories = array('1');

	$post_categories = array_unique($post_categories);

	// First the old categories
	$old_categories = $gcdb->get_col("
		SELECT tag_id 
		FROM $gcdb->post2tag 
		WHERE post_id = $post_ID");
	
	if (!$old_categories) {
		$old_categories = array();
	} else {
		$old_categories = array_unique($old_categories);
	}


	$oldies = printr($old_categories,1);
	$newbies = printr($post_categories,1);

	// Delete any?
	$delete_cats = array_diff($old_categories,$post_categories);

	if ($delete_cats) {
		foreach ($delete_cats as $del) {
			$gcdb->query("
				DELETE FROM $gcdb->post2tag 
				WHERE tag_id = $del 
					AND post_id = $post_ID 
				");
		}
	}

	// Add any?
	$add_cats = array_diff($post_categories, $old_categories);

	if ($add_cats) {
		foreach ($add_cats as $new_cat) {
			$gcdb->query("
				INSERT INTO $gcdb->post2tag (post_id, tag_id) 
				VALUES ($post_ID, $new_cat)");
		}
	}
}

function printr($var, $do_not_echo = false) {
	// from php.net/print_r user contributed notes 
	ob_start();
	print_r($var);
	$code =  htmlentities(ob_get_contents());
	ob_clean();
	if (!$do_not_echo) {
	  echo "<pre>$code</pre>";
	}
	return $code;
}

function add_magic_quotes($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) {
			$array[$k] = add_magic_quotes($v);
		} else {
			$array[$k] = addslashes($v);
		}
	}
	return $array;
}

function gc_update_post($postarr = array()) {
	global $gcdb;

	// First get all of the original fields
	$post = gc_get_single_post($postarr['ID'], ARRAY_A);

	// Escape data pulled from DB.
	$post = add_magic_quotes($post);
	extract($post);

	// Now overwrite any changed values being passed in. These are 
	// already escaped.
	extract($postarr);

	// If no categories were passed along, use the current cats.
	if ( 0 == count($post_category) || !is_array($post_category) )
		$post_category = $post['post_category'];

	$post_modified = current_time('mysql');
	$post_modified_gmt = current_time('mysql', 1);

	$sql = "UPDATE $gcdb->posts 
		SET post_content = '$post_content',
		post_title = '$post_title',
		post_status = '$post_status',
		post_modified = '$post_modified'
		WHERE ID = $ID";
		
	$result = $gcdb->query($sql);
	$rows_affected = $gcdb->rows_affected;

	gc_set_post_cats('', $ID, $post_category);

	return $rows_affected;
}

function gc_delete_post($postid = 0) {
	global $gcdb;
	$postid = (int) $postid;

	if ( !$post = $gcdb->get_row("SELECT * FROM $gcdb->posts WHERE ID = $postid") )
		return $post;

	$gcdb->query("DELETE FROM $gcdb->posts WHERE ID = $postid");
	
	$gcdb->query("DELETE FROM $gcdb->comments WHERE comment_post_ID = $postid");

	$gcdb->query("DELETE FROM $gcdb->post2tag WHERE post_id = $postid");
	
	return $post;
}

// computes an offset in seconds from an iso8601 timezone
function iso8601_timezone_to_offset($timezone) {
  // $timezone is either 'Z' or '[+|-]hhmm'
  if ($timezone == 'Z') {
    $offset = 0;
  } else {
    $sign    = (substr($timezone, 0, 1) == '+') ? 1 : -1;
    $hours   = intval(substr($timezone, 1, 2));
    $minutes = intval(substr($timezone, 3, 4)) / 60;
    $offset  = $sign * 3600 * ($hours + $minutes);
  }
  return $offset;
}

// converts an iso8601 date to MySQL DateTime format used by post_date[_gmt]
function iso8601_to_datetime($date_string, $timezone = USER) {
  if ($timezone == GMT) {
    preg_match('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', $date_string, $date_bits);
    if (!empty($date_bits[7])) { // we have a timezone, so let's compute an offset
      $offset = iso8601_timezone_to_offset($date_bits[7]);
    } else { // we don't have a timezone, so we assume user local timezone (not server's!)
      $offset = 3600 * get_settings('gmt_offset');
    }
    $timestamp = gmmktime($date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1]);
    $timestamp -= $offset;
    return gmdate('Y-m-d H:i:s', $timestamp);
  } elseif ($timezone == USER) {
    return preg_replace('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', '$1-$2-$3 $4:$5:$6', $date_string);
  }
}

// Get the ID of a category from its name
function get_cat_ID($cat_name='General') {
	global $gcdb;
	
	$cid = $gcdb->get_var("SELECT tag_ID FROM $gcdb->tags WHERE tag_name='$cat_name'");

	return $cid?$cid:1;	// default to cat 1
}

// Get the name of a category from its ID
function get_cat_name($cat_id) {
	global $gcdb;
	
	$cat_id -= 0; 	// force numeric
	$name = $gcdb->get_var("SELECT tag_name FROM $gcdb->tags WHERE tag_ID=$cat_id");
	
	return $name;
}


// get extended entry info (<!--more-->)
function get_extended($post) {
	//list($main,$extended) = explode('<!--more-->', $post, 2);

	// Strip leading and trailing whitespace
	//$main = preg_replace('/^[\s]*(.*)[\s]*$/','\\1',$main);
	//$extended = preg_replace('/^[\s]*(.*)[\s]*$/','\\1',$extended);
	$main = transcode($post);
	$extended = "";

	return array('main' => $main, 'extended' => $extended);
}

function transcode($string) {
$string = iconv( "GB2312", "UTF-8//IGNORE" , $string);
return $string;
}

function transcode_bak($string) {
$string = iconv( "UTF-8", "GB2312//IGNORE" , $string);
return $string;
}

function post_permalink($post_id = 0, $mode = '') { // $mode legacy
	return get_settings('base_url').'/?q='.$post_id;
}

function get_category_link($tag_name) { 
	return get_settings('base_url').'/?tag='.$tag_name;
}

function get_category_rss_link($tag_name) { 
	return get_settings('base_url').'/?feed='.$tag_name;
}

?>