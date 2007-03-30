<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/admin-functions.php
*  Usage: Admin Pages Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $LastChangedDate: 3/30/2007 $
*  $LastChangedRevision: 1 $
*  $LastChangedBy: ericfish $
*  $URL: http://graceric.googlecode.com/svn/trunk/gc-includes/admin-functions.php $
*/

/***** index.php *****/

// load posts grid when the index page is first load
function initPage() {
	global $gcdb,$begin_id,$end_id;
	
	$begin_id = 0;
	
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY post_date DESC LIMIT $begin_id, $end_id";
	
	$base_url = get_option('base_url');
	
	global $gcdb;
	
	$p1_posts = $gcdb->get_results($request);
		
	for($i=0;$i<10;$i++)
	{
		if(isset($p1_posts[$i]))
		{
			$p1_post = $p1_posts[$i];
			$post_ID = $p1_post->ID;
			$post_title = $p1_post->post_title;
			$post_status = $p1_post->post_status;
			$post_date = $p1_post->post_date_fmt;
			
			echo("<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">");
			echo("<A href=\"edit.php?q=$post_ID\">$post_title</A>");
			echo("</span><span class=\"tr_revision-state tr_revision-state-3\">");
			echo(" $post_status");
			echo("</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"$base_url/?q=$post_ID\">");
			echo("$base_url/?q=$post_ID");
			echo("</a></td><td>");
			echo("$post_date");
			echo("</td></tr>");
		}
	}
	echo("<input type=\"hidden\" name=\"begin_post_id\" value='$begin_id'>");
}

// get the search result grid
function processSearch($keyword){
	
	global $gcdb;
	
	$keyword = trim($keyword);
	$keyword = iconv( "UTF-8", "gb2312" , $keyword);
	$keyword = $gcdb->escape($keyword);
	$keywords = explode(" ", $keyword);
	$keyword_count = count($keywords);


	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_status from $gcdb->posts WHERE ";
	
	for($i=0;$i < $keyword_count;$i++)
	{
		$kw = $keywords[$i];
		if($i!=0)
			$request .= " OR ";
		$request .= "post_title LIKE '%$kw%' OR post_content LIKE '%$kw%'";
	}
	
	$search_results = $gcdb->get_results($request);
	$numbers = count($search_results);
	
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"xajax_processSearch(document.getElementById('tr_search_keyword').value);\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
	
	for($i=0;$i<$numbers;$i++)
	{		
		$s_post = $search_results[$i];
		$post_ID = $s_post->ID;
		$post_title = $s_post->post_title;
		$post_status = $s_post->post_status;
		$post_date = $s_post->post_date_fmt;
		
		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
		$text.=" $post_status";
		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".BASEURL."/?q=$post_ID\">";
		$text.=BASEURL."/?q=$post_ID";
		$text.="</a></td><td>";
		$text.="$post_date";
		$text.="</td></tr>";
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" value='-10'>";
	$text .= "</TBODY></TABLE>";
	
	$objResponse = new xajaxResponse();
	//$objResponse->addAssign("admin_search","value","please wait...");
	//$objResponse->addAssign("admin_search","disabled",true);
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	//$objResponse->addAssign("admin_search","value","Search");
	//$objResponse->addAssign("admin_search","disabled",false);
	
	return $objResponse;
}

// get the next page grid
function nextPage($begin_post_id){
	global $gcdb,$end_id;
	
	$begin_post_id += 10;
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY post_date DESC LIMIT $begin_post_id, $end_id";
	
	$p1_posts = $gcdb->get_results($request);
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"xajax_processSearch(document.getElementById('tr_search_keyword').value);\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<10;$i++)
	{
		$p1_post = $p1_posts[$i];
		$post_ID = $p1_post->ID;
		$post_title = $p1_post->post_title;
		$post_status = $p1_post->post_status;
		$post_date = $p1_post->post_date_fmt;
		
		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
		$text.=" $post_status";
		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".BASEURL."/?q=$post_ID\">";
		$text.=BASEURL."/?q=$post_ID";
		$text.="</a></td><td>";
		$text.="$post_date";
		$text.="</td></tr>";
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" value='$begin_post_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	
	return $objResponse;
}

// get the prevpage grid
function prevPage($begin_post_id){
	global $gcdb,$end_id;
	
	$begin_post_id -= 10;
	if($begin_post_id<0)
		$begin_post_id = 0;
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY post_date DESC LIMIT $begin_post_id, $end_id";
	
	$p1_posts = $gcdb->get_results($request);
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"xajax_processSearch(document.getElementById('tr_search_keyword').value);\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<10;$i++)
	{
		$p1_post = $p1_posts[$i];
		$post_ID = $p1_post->ID;
		$post_title = $p1_post->post_title;
		$post_status = $p1_post->post_status;
		$post_date = $p1_post->post_date_fmt;
		
		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
		$text.=" $post_status";
		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".BASEURL."/?q=$post_ID\">";
		$text.=BASEURL."/?q=$post_ID";
		$text.="</a></td><td>";
		$text.="$post_date";
		$text.="</td></tr>";
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" value='$begin_post_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	
	return $objResponse;
}

// do draft action when click draft option
function updateDraft($post_id) {
	global $gcdb;
	
	$request = "Update $gcdb->posts SET post_status = 'draft' WHERE ID = $post_id";
	$gcdb->query($request);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// do publish action when click publish option
function updatePublish($post_id) {
	global $gcdb;
	
	$request = "Update $gcdb->posts SET post_status = 'publish' WHERE ID = $post_id";
	$gcdb->query($request);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// do delete action when click delete option
function updateDelete($post_id) {
	global $gcdb;
	
	$request = "Delete FROM $gcdb->posts WHERE ID = $post_id";
	$gcdb->query($request);
	
	delTagRelated($post_id);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// refresh the posts on the page to the newest update status
function refreshPage($begin_id) {
	global $gcdb,$end_id;
		
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY post_date DESC LIMIT $begin_id, $end_id";
	
	$base_url = get_option('base_url');
	
	global $gcdb;
	
	$p1_posts = $gcdb->get_results($request);
		
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"xajax_processSearch(document.getElementById('tr_search_keyword').value);\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<10;$i++)
	{
		$p1_post = $p1_posts[$i];
		$post_ID = $p1_post->ID;
		$post_title = $p1_post->post_title;
		$post_status = $p1_post->post_status;
		$post_date = $p1_post->post_date;
		
		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
		$text.=" $post_status";
		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".BASEURL."/?q=$post_ID\">";
		$text.=BASEURL."/?q=$post_ID";
		$text.="</a></td><td>";
		$text.="$post_date";
		$text.="</td></tr>";
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" value='$begin_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	
	return $objResponse;
}

// after click draft or publish or delete option in the selection, refresh the select to the original one
function resetSelect() {
	$text = "<TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>&nbsp;
                <SELECT id=tr_bulk-action-dropdown name=\"tr_bulk-action-dropdown\" onchange=updateCheck('C1');>
                <OPTION value=\"\" selected>More Actions ...</OPTION>
                <OPTION value=publish>&nbsp; &nbsp; Publish</OPTION>
                <OPTION value=draft>&nbsp; &nbsp; Draft</OPTION>
                <OPTION value=delete>&nbsp; &nbsp; Delete</OPTION>
                </SELECT>
                <SPAN class=tr_selectors>Select: 
                  <SPAN class=tr_pseudo-link onclick=\"checkAll('C1');\">All</SPAN>,&nbsp;
                  <SPAN class=tr_pseudo-link onclick=\"checkNone('C1');\">None</SPAN></SPAN>
                </TD>
                <TD style=\"PADDING-RIGHT: 0px\" align=\"right\">
                &lt; <SPAN class=tr_pseudo-link onclick=\"xajax_prevPage(document.getElementById('begin_post_id').value);\">PREV</SPAN>
                 | 
                 <SPAN class=tr_pseudo-link onclick=\"xajax_nextPage(document.getElementById('begin_post_id').value);\";>NEXT</SPAN> &gt;
                </TD></TR></TBODY></TABLE>";
	
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_select-view","innerHTML",$text);
	
	return $objResponse;
}

/***** edit.php *****/

// get tag name for 1 post based on post id, the result seperated by space
function getTagsText($post_id){
	global $gcdb;
	
	$prerequest = "SELECT count(*) tot FROM $gcdb->post2tag WHERE post_id = $post_id";
	$tot = $gcdb->get_var($prerequest);
	
	if($tot != 0)
	{
		$request = "SELECT a.tag_name FROM $gcdb->tags a, $gcdb->post2tag b, $gcdb->posts c WHERE a.tag_id=b.tag_id AND b.post_id = c.ID AND c.ID = $post_id";
		$tags = $gcdb->get_col($request);
	
		$number = count($tags);
	}
	else
		$number=0;
	
	$tag_text = "";
	for($i=0;$i < $number;$i++)
	{
		$tag_text .= $tags[$i]." ";
	}
	return $tag_text;
}

// Get a new id for next post in the gc_id table
function getNextId(){
	global $gcdb;
	$request = "SELECT ID FROM $gcdb->id LIMIT 1";
	$id = $gcdb->get_var($request);
	return $id;
}

// save post - new and edit
function savePost($post_id,$post_title,$post_content,$post_tags,$show_in_home,$post_status) {
	global $gcdb;
		
	// check is id exist
	$querycheckid = "SELECT COUNT(*) FROM $gcdb->posts WHERE ID = $post_id";
	$exist_no = $gcdb->get_var($querycheckid);
	$post_date = date('Y-m-d H:i:s');
	
	// id not exist - create new - insert
	if($exist_no == 0) {
		// insert post
		$requestnewpost = "INSERT INTO $gcdb->posts (ID,post_title,post_content,post_date,show_in_home,post_status) VALUES ($post_id,'$post_title','$post_content','$post_date','$show_in_home','$post_status')";
		$gcdb->query($requestnewpost);
		
		// gc_db id + 1
		idAddOne();
		
		// insert tag related
		updateTags($post_id,$post_tags);
		
	}
	
	// id exist - edit - update
	else {
		// update post
		$requesteditpost = "UPDATE $gcdb->posts SET post_title='$post_title',post_content='$post_content',post_modified='$post_date',show_in_home='$show_in_home',post_status='$post_status' WHERE ID=$post_id";
		$gcdb->query($requesteditpost);
	
		// delete already tags related
		delTagRelated($post_id);
	
		// insert new related tags
		updateTags($post_id,$post_tags);
	}
	
}

function idAddOne(){
	global $gcdb;
	$request = "UPDATE $gcdb->id SET ID = ID +1";
	$gcdb->query($request);
}

function updateTags($post_id,$post_tags){
	
	global $gcdb;
	// get tags 
	$tags = explode(" ", $post_tags);
	$tag_count = count($tags);
	
	// do while to circle all the tags
	for($i=0;$i < $tag_count;$i++)
	{
		// check is tag exist
		$tag = $tags[$i];
		$query1 = "SELECT COUNT(*) FROM $gcdb->tags WHERE tag_name = '$tag'";
		$exist_tag = $gcdb->get_var($query1);
		
		// if tag not exist, insert tag into gc_tags
		if($exist_tag == '0') {
			$query_insert_tag = "INSERT INTO $gcdb->tags (tag_name) VALUES ('$tag')";
			$gcdb->query($query_insert_tag);
		}
		
		// select tag_ID
		$query2 = "SELECT tag_ID FROM $gcdb->tags WHERE tag_name = '$tag'";
		$tagid = $gcdb->get_var($query2);
		
		// insert record to relate post to tag
		$insert_post2tag = "INSERT INTO $gcdb->post2tag (tag_id, post_id) VALUES ($tagid, $post_id)";
		$gcdb->query($insert_post2tag);
		
	}
}

function delTagRelated($post_id) {
	global $gcdb;
	$request = "DELETE FROM $gcdb->post2tag WHERE post_id = $post_id";
	$gcdb->query($request);
	
}

function checkLogin(){
	session_start();
	$s_username=$_SESSION['s_username'];
	$time_last_load=$_SESSION['time_last_load'];
	
	if(!isset($s_username))
	{
	    $info="Not Logon, please logon first";
	    header("location:login.php?info=$info");
	    exit;
	}
	
	else
	{
	    $time_now=time();
	    $time_interval=$time_now-$time_last_load;
	    if($time_interval>6000)
	    {
	        $info="Idle time is too long, please relogin.";
	        session_destroy;
	        header("location:login.php?info=$info");
	        exit;
	    }
		$_SESSION['time_last_load']=time();
	}
}

function logout() {
	session_start();
	session_destroy();
	header("location:login.php");
}

function addSession($username){

	session_start();
	$_SESSION['s_username']=$username;
	$_SESSION['time_last_load']=time();				
}

// CREATE ATOM.XML
function createAtom(){
	global $gcdb;
	
	$rss = new UniversalFeedCreator(); 
	$rss->useCached(); 
	$rss->title = ""; 
	$rss->description = ""; 
	$rss->link = ""; 
	$rss->syndicationURL = "".$PHP_SELF; 
	
	$image = new FeedImage(); 
	$image->title = ""; 
	$image->url = ""; 
	$image->link = ""; 
	$image->description = "";
	$rss->image = $image;
	
	$request = "SELECT ID,post_title,post_content,UNIX_TIMESTAMP(post_date) AS post_date FROM $gcdb->posts WHERE post_status='publish' and show_in_home='yes' ORDER BY post_date DESC LIMIT 10";
	$posts = $gcdb->get_results($request);
	$post_num = count($posts);
	
	for ($i=0;$i<$post_num;$i++) { 
		$item = new FeedItem(); 
		$post = $posts[$i];
		$item->title = $post->post_title;
		$item->link = "?q=".$post->ID; 
		$item->description = $post->post_content;
		$item->date = (int)$post->post_date;
		$item->source = ""; 
		$item->author = ""; 
		 
		$rss->addItem($item); 
	} 
	
	$rss->saveFeed("ATOM", "../atom.xml", false);
}

?>