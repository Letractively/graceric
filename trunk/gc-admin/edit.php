<?php
require_once('../gc-config.php');
include("../gc-includes/functions.php");
include("../gc-includes/admin-functions.php");
include("fckeditor.php");

checkLogin();

// edit page init
if(isset($_REQUEST['q'])) {
	$post_id=$_REQUEST['q'];
	global $gcdb;
	$request = "SELECT post_title,post_content,show_in_home FROM $gcdb->posts WHERE ID = $post_id";
	$post = $gcdb->get_row($request);
	$post_title = $post->post_title;
	$post_content = $post->post_content;
	$post_tags = getTagsText($post_id);
	if($post->show_in_home == 'yes')
		$is_show = true;
	else
		$is_show = false;
}
// create new page init
else {
	$postArray = &$HTTP_POST_VARS;
	if(isset($postArray['new-page-title']))
		$post_title = $postArray['new-page-title'];
	else
		$post_title = "";
	$post_content = "";
	$post_id = getNextId();
	$post_tags = "";
	$is_show = true;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Create New Post</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<LINK href="files/cm.css" rel=stylesheet>

</HEAD>
<BODY>
<form id="postForm" action="save.php" method="post">
<TABLE width="100%">
  <TBODY>
  <TR>
    <TD style="VERTICAL-ALIGN: top">
      <TABLE cellSpacing=0 cellPadding=0 width="100%">
        <TBODY>
        <TR>
          <TD class="tr_enclosure tr_tl"></TD>
          <TD class=tr_enclosure>
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD><A href="index.php">Home</A> | 
                <A href="#">Site settings</A> | 
                <A href="#">My Account</A> | 
                <A href="#">Links</A> | 
                <A href="logout.php">Sign out</A>&nbsp;&nbsp; </TD></TR></TBODY></TABLE></TD>
          <TD class="tr_enclosure tr_tr"></TD></TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
            <DIV id=tr_grid-view>
            <TABLE class=tr_sort-bar>
              <THEAD>
              <TR id=tr_new-page-2-list-view>
                  <TD>
                  
                  Title: <INPUT id="post_title" name="post_title" size="40" value="<?=$post_title?>">
                 <input type="hidden" id="post_id" name="post_id" value='<?=$post_id?>'>
                  </TD></TR></THEAD>
              <TBODY></TBODY></TABLE>
            </DIV>
            <DIV id=tr_list-view>
            
            <TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width="100%">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      
                      Display on Homepage: Yes <input type="radio" value="yes" <? if($is_show)echo('checked')?> name="show_in_home"> No <input type="radio" name="show_in_home" value="no" <? if(!$is_show)echo('checked')?>>
                      
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD> &nbsp;&nbsp;&nbsp;Tags:
                <INPUT id="post_tags" name="post_tags" size="30" value="<?=$post_tags?>">
                </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>

<?php
$oFCKeditor = new FCKeditor ;
$oFCKeditor->ToolbarSet = 'Accessibility' ;
$oFCKeditor->Value = $post_content;
$oFCKeditor->CanUpload = false ;	// Overrides fck_config.js default configuration
$oFCKeditor->CanBrowse = false ;	// Overrides fck_config.js default configuration
$oFCKeditor->CreateFCKeditor( 'EditorAccessibility', '100%', '330' ) ;
?>

              </TBODY></TABLE></DIV>
            </TD>
          <TD class=tr_enclosure></TD></TR>
        <TR>
          <TD class="tr_enclosure tr_bl">.</TD>
          <TD class=tr_enclosure>
          <DIV id=tr_select-view>
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>
                      &nbsp;<INPUT class=tr_submit name="btnSubmit" type=submit value="Publish">
                      <input type="checkbox" name="D1" value="draft">
                      &nbsp;save as draft&nbsp;</TD>
                <TD style="PADDING-RIGHT: 0px" align="right">
                <INPUT class=tr_submit name="btnBack" onclick="history.back()" type=button value="Discard"></TD></TR></TBODY></TABLE></DIV></TD>
          <TD class="tr_enclosure tr_br">.</TD></TR></TBODY></TABLE></TD>
    	<TD style="VERTICAL-ALIGN: top" width="1%">
      </TD></TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px">&copy;</span></span><SPAN class=tr_footer-text style="FONT-SIZE: 10px"> 2006 ericfish.com</SPAN></DIV>
</form></BODY></HTML>
