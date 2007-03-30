<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/dbquery.class.php
*  Usage: Admin Pages Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $LastChangedDate: 3/30/2007 $
*  $LastChangedRevision: 1 $
*  $LastChangedBy: ericfish $
*  $URL: http://graceric.googlecode.com/svn/trunk/gc-includes/dbquery.class.php $
*/

class DB_Query {
	var $query;
	var $query_vars;
	var $queried_object;
	var $queried_object_id;

	var $posts;
	var $post_count = 0;
	var $current_post = -1;
	var $post;

	var $is_page = false;
	var $is_archive = false;
	var $is_date = false;
	var $is_year = false;
	var $is_month = false;
	var $is_day = false;
	var $is_home = false;
	var $is_comment = false;
	var $is_feed = false;
	
	var $is_tags = false;
	var $is_search = false;
	var $is_about = false;
	var $is_links = false;
	var $is_tag = false;
	var $is_tech = false;
	var $is_gallery = false;
	var $is_lab = false;
	var $is_tripreader = false;

	function DB_Query ($query = '' ) {
		global $posts, $post;
		
		// filter xss
		$myFilter = new InputFilter(array(), array(), 0, 0);
		$query = $myFilter->process($query);
		
		$this->query($query);
		
		if ($this->is_home || $this->is_page || $this->is_month || $this->is_tech || $this->is_tripreader)
		{
			$posts = $this->get_post();
		}
	}
	
	function init () {
		$this->is_page = false;
		$this->is_archive = false;
		$this->is_date = false;
		$this->is_year = false;
		$this->is_month = false;
		$this->is_day = false;
		$this->is_about = false;
		$this->is_search = false;
		$this->is_home = false;
		$this->is_comment = false;
		$this->is_tags = false;
		$this->is_links = false;
		$this->is_tag = false;
		$this->is_tech = false;
		$this->is_feed = false;
		$this->is_gallery = false;
		$this->is_lab = false;
		$this->is_tripreader = false;

		unset($this->posts);
		unset($this->query);
		unset($this->query_vars);
		unset($this->queried_object);
		unset($this->queried_object_id);
		$this->post_count = 0;
		$this->current_post = -1;
	}

	function &query($query) {
		$this->parse_query($query);
	}

	// Reparse the query vars.
	function parse_query_vars() {
		$this->parse_query('');
	}

	// Parse a query string and set query type booleans.
	function parse_query ($query) {
		global $HTTP_COOKIE_VARS,$comm_name,$comm_e_mail,$comm_website;
		
		if ( !empty($query) || !isset($this->query) ) {
			$this->init();
			parse_str($query, $qv);
			$this->query = $query;
			$this->query_vars = $qv;
		}

		if (isset($qv['q'])) {
			$this->is_page = true;
			
			if (isset($qv['comment'])) {
				$this->is_comment = true;
				if (isset($_REQUEST['rem'])) {
					setcookie ("blogKo_name", $_POST['comm_name'],time()+1209600);
					setcookie ("blogKo_mail", $_POST['comm_e_mail'],time()+1209600);
					setcookie ("blogKo_www", $_POST['comm_website'],time()+1209600);
				}
			}
		}
		elseif (isset($qv['archive'])) {
			$this->is_archive = true;
			
			if (isset($qv['month'])) {
				$this->is_month = true;
			}
		}
		elseif (isset($qv['tags'])) {
			
			$this->is_tags = true;
		}
		elseif (isset($qv['about'])) {
			
			$this->is_about = true;
		}
		elseif (isset($qv['links'])) {
			
			$this->is_links = true;
		}
		elseif (isset($qv['search'])) {
			
			$this->is_search = true;
		}
		elseif (isset($qv['tag'])) {
			
			$this->is_tag = true;
		}
		elseif (isset($qv['tech'])) {
			
			$this->is_tech = true;
		}
		elseif (isset($qv['tripreader'])) {
			
			$this->is_tripreader = true;
		}
		elseif (isset($qv['feed'])) {
			
			$this->is_feed = true;
		}
		elseif (isset($qv['gallery'])) {
			
			$this->is_gallery = true;
		}
		elseif (defined('THIS_IS_LAB')) {
			
			$this->is_lab = true;
		}
		if (  !($this->is_archive || $this->is_page || $this->is_search || $this->is_tags || $this->is_about || $this->is_tag || $this->is_links || $this->is_tech || $this->is_feed || $this->is_gallery || $this->is_lab || $this->is_tripreader)) {
			$this->is_home = true;
		}
	}
	
	function next_post() {
        
		$this->current_post++;

		$this->post = $this->posts[$this->current_post];
		return $this->post;
	}

	function the_post() {
		global $post;
		$post = $this->next_post();
	}

	function rewind_posts() {
		$this->current_post = -1;
		if ($this->post_count > 0) {
			$this->post = $this->posts[0];
		}
	}

	function have_posts() {
		if ($this->current_post + 1 < $this->post_count) {
			return true;
		}

		return false;
	}

	// Get the Data in db for the posts
	function &get_post() {

		global $gcdb;
		
		// Get db request for is_page
		if ( $this->is_page)
		{
			$q = $gcdb->escape($this->query_vars['q']);
			$request = "SELECT ID, post_date, post_title, post_content,comment_status  FROM $gcdb->posts";
			$request .= " WHERE ID=".$q;
			$request .= " AND post_status = 'publish'";
			$request .= " LIMIT 1";
		}
		
		// Get db request for is_home
		if ($this->is_home)
		{
			$request = "SELECT ID, post_date, post_title, post_content FROM $gcdb->posts";
			$request .= " WHERE post_status = 'publish'";
			$request .= " AND show_in_home = 'yes'";
			$request .= " ORDER BY post_date DESC";
			$request .= " LIMIT ".get_option('home_post_number');
		}
		
		// Get db request for is_month, show all post for the month
		if ($this->is_month)
		{
			$the_month = $this->query_vars['month'];
			$the_month = $gcdb->escape($the_month);
			$request = "SELECT ID, post_date, post_title, post_content FROM $gcdb->posts";
			$request .= " WHERE post_status = 'publish'";
			$request .= " AND DATE_FORMAT(post_date, '%Y%m') = '$the_month'";
			$request .= " AND show_in_home = 'yes'";
			$request .= " ORDER BY post_date DESC";
		}

		// Get db request for tag echo tech
		
		if ($this->is_tech)
		{
			if(isset($this->query_vars['p']))
				$p = $this->query_vars['p'] - 1;
			else
				$p=0;
			
			$p = $gcdb->escape($p);
			$page_post_num = get_option('techpage_post_num');
			$begin_num = $p*$page_post_num;
			$request = "SELECT a.ID, a.post_date, a.post_title, a.post_content FROM $gcdb->posts a, $gcdb->post2tag b, $gcdb->tags c";
			$request .= " WHERE a.post_status = 'publish'";
			$request .= " AND a.ID = b.post_id";
			$request .= " AND b.tag_id = c.tag_id";
			$request .= " AND c.tag_name = 'tech'";
			$request .= " ORDER BY post_date DESC";
			$request .= " LIMIT $begin_num,$page_post_num";
		}

		// Get db request for tag echo tripreader
		
		if ($this->is_tripreader)
		{
			if(isset($this->query_vars['p']))
				$p = $this->query_vars['p'] - 1;
			else
				$p=0;
			
			$p = $gcdb->escape($p);
			$page_post_num = get_option('techpage_post_num');
			$begin_num = $p*$page_post_num;
			$request = "SELECT a.ID, a.post_date, a.post_title, a.post_content FROM $gcdb->posts a, $gcdb->post2tag b, $gcdb->tags c";
			$request .= " WHERE a.post_status = 'publish'";
			$request .= " AND a.ID = b.post_id";
			$request .= " AND b.tag_id = c.tag_id";
			$request .= " AND c.tag_name = 'tripreader'";
			$request .= " ORDER BY post_date DESC";
			$request .= " LIMIT $begin_num,$page_post_num";
		}
		
		$this->posts = $gcdb->get_results($request);
		$this->post_count = count($this->posts);
		
		return $this->posts;
	}
	
	// Get the number of comments for the post
	function get_comments_number() {
		global $gcdb;
		
		// Get db request for is_page
		if ( $this->is_page)
		{
			$q = $gcdb->escape($this->query_vars['q']);
			$request = "SELECT COUNT(*) FROM $gcdb->comments";
			$request .= " WHERE comment_post_ID=".$q;
		}
		
		// Get db request for is_home
		if ($this->is_home || $this->is_page || $this->is_month || $this->is_tech || $this->is_tripreader)
		{
			$current_postID = $this->posts[$this->current_post]->ID;
			$current_postID = $gcdb->escape($current_postID);
			$request = "SELECT COUNT(*) FROM $gcdb->comments WHERE comment_post_ID = $current_postID";
		}

		$result_number = $gcdb->get_var($request);
		return $result_number;
	}
	
	// Get comments of the post
	function get_comment() {
		global $gcdb;
		
		$current_postID = $this->posts[0]->ID;
		$current_postID = $gcdb->escape($current_postID);
		$request = "SELECT comment_author,comment_author_email,comment_author_url,comment_content,comment_date FROM $gcdb->comments WHERE comment_post_ID = $current_postID";
		$request .= " ORDER BY comment_date";
		
		return $gcdb->get_results($request);
	}
	
	//Save comment into db
	function save_comment() {
		global $gcdb, $error_message;
		$is_comment_valid = true;
		
		//$comm_name,$comm_e_mail,$comm_website,$comm_content,$notify_comment
		$comm_name = $_REQUEST['comm_name'];
		$comm_name = $gcdb->escape($comm_name);
		$comm_e_mail = $_REQUEST['comm_e_mail'];
		$comm_e_mail = $gcdb->escape($comm_e_mail);
		$comm_website = $_REQUEST['comm_website'];
		$comm_website = $gcdb->escape($comm_website);
		$comm_content = $_REQUEST['comm_content'];
		$comm_content = $gcdb->escape($comm_content);
		
		if ($comm_name == '') {
			$is_comment_valid = false;
			$error_message = "<div class='blogkrow' style='color: red'><b>Please enter your name.</b></div>";
		}
		
		$garbage = "localhost";	// garbage information
		$pos_found = strpos($comm_name,$garbage);
		if ($pos_found !== false)
		{
			$is_comment_valid = false;
			$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this name is not allow to post comment.</b></div>";
		}

		$pos_found = strpos($comm_e_mail,$garbage);
		if ($pos_found !== false)
		{
			$is_comment_valid = false;
			$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this email is not allow to post comment.</b></div>";
		}
		
		if (is_garbage_comment($comm_content)) {
			$is_comment_valid = false;
			$error_message = "<div class='blogkrow' style='color: red'><b>No 'http://' is allow in comment content, <br/>please remove 'http://' and post again, 3x</b></div>";
		}
		
		if($is_comment_valid)
		{
			$comm_content = filter_comment($comm_content);
			$comm_date = Date("Y-m-d H:i:s",Time());
			$comm_author_ip = get_visitor_ip();
			
			$current_postID = $this->posts[0]->ID;
			
			$request = "INSERT INTO $gcdb->comments (comment_author, comment_author_email, comment_author_url, comment_content, comment_date, comment_post_ID, comment_author_IP) VALUES ('$comm_name','$comm_e_mail','$comm_website','$comm_content','$comm_date',$current_postID, '$comm_author_ip')";
			
			$gcdb->query($request);
			
			// send email to admin
			/*
			$admin_mail = get_settings('admin_email');
			$post_url = get_settings('base_url')."/?q=".$current_postID;
			
			$mail_content=stripslashes($comm_content); 
		   	$subject = "a new blogKomment by $comm_name";
		   	$headers .= "From: noreply@ericfish.com\nReply-To: $comm_e_mail";			  
			$text = "$comm_name wrote: $mail_content\n\nCheck out the post at\n$post_url&comment#".bin2hex($comm_date)."\n\n$comm_date, IP:[$comm_author_ip]";
			mail($admin_mail, $subject, $text , $headers);
			*/
			
		}
	}
}

// Make a global instance.
if (! isset($db_query)) {
    $db_query = new DB_Query($_SERVER['QUERY_STRING']);
}