<? 
if(isset($_REQUEST['info']))
{
	// include class file
	require("../gc-includes/inputfilter.class.php");
	// create customised filter object (more info on constructor in readme.)
	$myFilter = new InputFilter(array(), array(), 1, 1);

	//$before = '<script>bad blah blah</script> <good attr="ok">';
	$info = $_REQUEST['info'];
	$info = $myFilter->process($info);

	$info="<font color=red>".$info."</font>";
}
else
	$info="";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Login</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<LINK href="files/cm.css" rel=stylesheet>
</HEAD>
<BODY>
<form name="entry" method="post" action="checklogin.php">
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
                <TD></TD></TR></TBODY></TABLE></TD>
</TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
            <DIV id=tr_grid-view>
            <TABLE class=tr_sort-bar>
              <THEAD>
              <TR id=tr_new-page-2-list-view>
                      <TD>
						<p align="center">&nbsp;</p>
						<p align="center">&nbsp;</p>
						<p align="center">&nbsp;</p>
						<p align="center">Username: &nbsp;<input type="text" name="username" tabindex="1" size="20" maxlength="20">
						</p>						
						<p align="center">Password: &nbsp;<input type="password" name="password" tabindex="2" size="20" maxlength="30" >
						</p>
						<p align="center">
                      	&nbsp;&nbsp;&nbsp;<input class=tr_submit type=submit name="login" value="  login  ">
						&nbsp;&nbsp;<input class=tr_submit type=reset value="  reset  " name="reset"><br/><br/><?=$info?> 
                      	<p align="center">
                      &nbsp;<p align="center">
                      &nbsp;<p align="center">
                      &nbsp;</TD></TR></THEAD>
              <TBODY></TBODY></TABLE>
            </DIV>
            </TD>
</TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px">&copy;</span></span><SPAN class=tr_footer-text style="FONT-SIZE: 10px"> 2006 ericfish.com</SPAN></DIV>
</FORM>
 </BODY></HTML>
