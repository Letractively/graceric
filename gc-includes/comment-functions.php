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
*  $LastChangedDate$
*  $LastChangedRevision$
*  $LastChangedBy$
*  $URL$
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
	
	$text = "<a href='?q=$current_postID&comment#comment'>Comment ($comments_number)</a>";
	return $text;
}

// Show comments detail on page, call by the_comment
function show_comments() {
	global $db_query;
	$comments = $db_query->get_comment();
	$comment_ID="";
	$comment_author = "";
	$comment_author_email = "";
	$comment_author_url = "";
	$comment_date = "";
	
	for($i=0; $i<count($comments); $i++)
	{
	    $comment_ID = $comments[$i]->comment_ID;
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
		echo("<strong>$comment_author</strong>");
		// only admin can see comment's email
		if(user_is_auth())
		{
		  echo($comment_author_email);
		}
		echo("$comment_author_url, $comment_date");
		echo(" <a href='?q=".the_ID(false)."&comment#".bin2hex($comment_date)."'>link</a>");
		if(user_is_auth())
		{
		    echo(" <span onclick=\"javascript:xajax_saveSpamComment($comment_ID);\"><a href=\"javascript://\">spam?</a></span>");
		}
		echo("</div></div>");
	}
}

// call by ajax, mark spam comment
function saveSpamComment($comment_ID){
    
	global $gcdb;
	$objResponse = new xajaxResponse();

	$option_value = trim($comment_ID);
	//$option_value = apply_filters($option_value);

	$request1 = "UPDATE $gcdb->comments SET comment_approved='spam' WHERE comment_ID=$comment_ID";
	$gcdb->query($request1);

	$objResponse->addAlert("Spam comment marked!");

	return $objResponse;
}

/**** check comment validations ****/
function is_comment_valid($comm_name,$comm_e_mail,$comm_website,$comm_content){
    global $error_message;
	$is_comment_valid = true;
		
	//require name
	if ($comm_name == '') {
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Please enter your name.</b></div>";
	}
		
	//require content
	if ($comm_content == '') {
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Please enter some content.</b></div>";
	}
	
	//check name allowed
	$garbage = "localhost";	// garbage information
	$pos_found = strpos($comm_name,$garbage);
	if ($pos_found !== false)
	{
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this name is not allow to post comment.</b></div>";
	}

	//check email allowed
	
	$pos_found = strpos($comm_e_mail,$garbage);
	if ($pos_found !== false)
	{
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this email is not allow to post comment.</b></div>";
	}
	
	//check user ip allowed
	
	//check comment content for garbage information
	if (is_garbage_comment($comm_content)) {
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>No 'http://' is allow in comment content, <br/>please remove 'http://' and post again, 3x</b></div>";
	}
	
	return $is_comment_valid;
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

// filter comment content input
function filter_comment($comment_content) {
	
    $comment_content= htmlspecialchars($comment_content, ENT_QUOTES);

    $comment_content= str_replace ("\n"," ", $comment_content);
    $comment_content= str_replace ("\r","<br/>", $comment_content);
    
    $comment_content= proofAddSlashes($comment_content);
    
    return $comment_content;
}

// get user info in cookies
// if no cookies, set the default values
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

// check if this post allow comment
// if allow return true
// if not return false
function allow_comment(){
    global $post;
	$comment_status = $post->comment_status;
	if ($comment_status == 'open')
    	return true;
	else
		return false;
}

// get the commentor's ip address
function get_visitor_ip() {
	
    //For 512j apache server
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
	
	//For other servers, you can just use the following sentence to replace all above
	//return $_SERVER['REMOTE_ADDR'];
}

?>