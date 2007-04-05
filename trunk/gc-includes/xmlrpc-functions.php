<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/xmlrpc-functions.php
*  Usage: XML-RPC Functions
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

/**** XML RPC functions ****/

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

function post_permalink($post_id = 0, $mode = '') {
	return get_settings('base_url').'/?q='.$post_id;
}

function get_category_link($tag_name) { 
	return get_settings('base_url').'/?tag='.$tag_name;
}

function get_category_rss_link($tag_name) { 
	return get_settings('base_url').'/?feed='.$tag_name;
}

?>