<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/comment.php
*  Usage: Default Comment Template
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
?>

<span class="lastpost">[<a class="blogkommlink" href="?q=<? the_ID(); ?>">Hide comment</a>]</span>
<form method="post" name="comm" action="?q=<? the_ID(); ?>&comment#comment">

	<a name="comment"></a>
	<?php show_comments(); ?>
	
	<?php if(allow_comment()): ?>
	<a name="addcomment"></a>

		<div class="blogkbox">
			<div class="blogkrow">
			<strong>Add comment here</strong><br/>
  			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_name" value="<? get_cookie_name(); ?>" size="32" maxlength="30"/> (name)</span>
		</div>
		<div class="blogkrow">
 			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_e_mail" value="<?get_cookie_mail(); ?>" size="32" maxlength="30"/> (e-mail)</span>
		</div>
    	<div class="blogkrow">
  			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_website"  size="32" value="<? get_cookie_www();?>" /> (website)</span>
		</div>
		<div class="blogkrow">
  			<span class="blogkRightClmn">
  				<textarea class="textarea" name="comm_content" rows="12" cols="40" tabindex="4" ></textarea>

				<br/>
        		<input style="font-size:9px; font-weight: bold;" type="submit" name="button" value="I'm finish, post it!"/><br/>

			</span>
		</div>
		<div class="blogkrow">
 			<input type="checkbox" name="rem" value="1" checked="checked"/>remember me<br/>
		</div>
 	</div>
 	<?php endif; ?>
</form>

