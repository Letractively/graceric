<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/comment-functions.php
*  Usage: Comment Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id$
*  $LastChangedDate: 2007-03-30 15:09:23 +0800 (Fri, 30 Mar 2007) $
*  $LastChangedRevision: 10 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/gc-includes/comment-functions.php $
*/

// Show the comment link on page
// Click to show comments part
// Called by the_comment()
function show_comment_link() {
	global $db_query,$gcdb;
	
	$current_postID = the_ID(false);
	$comments_number = $db_query->get_comments_number();
	
	$request = "SELECT tag_id FROM $gcdb->tags WHERE tag_name = 'tech'";
	$tag_id = $gcdb->get_var($request);
	
	if(is_tech())
		$text = "<a href='?q=$current_postID&tagid=$tag_id&comment#comment'>Comment ($comments_number)</a>";
	else
		$text = "<a href='?q=$current_postID&comment#comment'>Comment ($comments_number)</a>";
	return $text;
}

// Show comments detail on page, call by the_comment
function show_comments() {
	global $db_query;
	$comments = $db_query->get_comment();
	$comment_author = "";
	$comment_author_email = "";
	$comment_author_url = "";
	$comment_date = "";
	
	for($i=0; $i<count($comments); $i++)
	{
		$comment_author = $comments[$i]->comment_author;
		$comment_date = mysql2date('d.m.Y, g:iA', $comments[$i]->comment_date);
		$comment_author_email = $comments[$i]->comment_author_email;
		$comment_author_url = $comments[$i]->comment_author_url;
		if ($comment_author_email != "")
			$comment_author_email = "[<a href='mailto:$comment_author_email'>@</a>]";
		if ($comment_author_url != "http://")
			$comment_author_url = "[<a href='$comment_author_url'>H</a>]";
		else
			$comment_author_url = "";
		echo("<div class='blogkcomments'>");
		echo("<div class='blogkrow'>");
		echo("<a id='".bin2hex($comment_date)."' name='".bin2hex($comment_date)."'></a>");
		echo($comments[$i]->comment_content);
		echo("</div><div class='blogkrow'>");
		echo("<strong>$comment_author</strong>$comment_author_email$comment_author_url, $comment_date");
		echo(" <a href='?q=".the_ID(false)."&comment#".bin2hex($comment_date)."'>link</a>");
		echo("</div></div>");
	}
}

// find garbage information in comment content, if it is garbage comment return true
function is_garbage_comment($comment_content) {
	// filter garbage comment
	$garbage = "http://";	// garbage information
	$pos_found = strpos($comment_content,$garbage);
	if ($pos_found !== false)
		return true;
	else
		return false;
		
}

function filter_comment($comment_content) {
	
    $comment_content= htmlspecialchars($comment_content, ENT_QUOTES);

    $comment_content= str_replace ("\n"," ", $comment_content);
    $comment_content= str_replace ("\r","<br/>", $comment_content);
    
    $comment_content= proofAddSlashes($comment_content);
    
    return $comment_content;
}

function get_cookie_name(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_name"]))
		echo $HTTP_COOKIE_VARS["blogKo_name"];
	else
		echo "";
}

function get_cookie_mail(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_mail"]))
		echo $HTTP_COOKIE_VARS["blogKo_mail"];
	else
		echo "";
}

function get_cookie_www(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_www"]))
		echo $HTTP_COOKIE_VARS["blogKo_www"];
	else
		echo "http://";
}

function allow_comment(){
    global $post;
	$comment_status = $post->comment_status;
	if ($comment_status == 'open')
    	return true;
	else
		return false;
}

function get_visitor_ip() {
	
	global $HTTP_X_FORWARDED_FOR;
	
	if($HTTP_X_FORWARDED_FOR!="")
		$REMOTE_ADDR=$HTTP_X_FORWARDED_FOR;

	if(isset($REMOTE_ADDR))
	{
		$tmp_ip=explode(",",$REMOTE_ADDR);

		$REMOTE_ADDR=$tmp_ip[0];
	}
	else
		$REMOTE_ADDR="";
	
	return $REMOTE_ADDR;
	
	//return $_SERVER['REMOTE_ADDR'];
}

?>