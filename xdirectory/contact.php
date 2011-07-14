<?
// 
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

if ($submit) {
include("header.php");
global $xoopsConfig, $xoopsDB, $myts, $meta;

 $result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$id'");
while(list($title, $email) = $xoopsDB->fetchRow($result)) {


if ($tele) {
$teles = "Phone: $tele";
}  else {
$teles = "";
}

$message .= "Message from $namep\ne-Mail: $post ".$meta['title']."\n$teles\n\n";
$message .= "$namep wrote:\n";
$message .= "$messtext\n\n\n";
$message .= "This message was sent by $namep using the e-Mail form on {X_SITENAME}.  \n\n\n";

	$subject = "Email Submission from {X_SITENAME}";
	$mail =& getMailer();
	$mail->useMail();
	$mail->setFromEmail($post);
	$mail->setToEmails($email);
	$mail->setSubject($subject);
	$mail->setBody($message);
	$mail->send();
	echo $mail->getErrors();

	$from = "From: info@changeme.com"; 
	$to = $email; 
	$subject = $subject; 
	$body = $message; 

if(mail($to,$subject,$body,$from)) echo ""; 
else echo ""; 


}
redirect_header("index.php",1,_CLA_MESSEND);
exit();

} else {

include("header.php");
include(XOOPS_ROOT_PATH."/header.php");
OpenTable();

 $result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$lid'");
while(list($title, $email) = $xoopsDB->fetchRow($result)) {

echo "<script>
          function verify() {
                var msg = \"Errors were found during the validation of this form!\\n__________________________________________________\\n\\n\";
                var errors = \"FALSE\";

			
				if (document.Cont.namep.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"The Name field is a required field.\\n\";
                }
				
				if (document.Cont.post.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"The e-Mail field is a required field.\\n\";
                }
				
				if (document.Cont.messtext.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"The Message field is a required field.\\n\";
                }
				
  
                if (errors == \"TRUE\") {
                        msg += \"__________________________________________________\\n\\nPlease correct the errors listed above before submitting this form.\\n\";
                        alert(msg);
                        return false;
                }
          }
          </script>";


echo "<B></B><BR><BR>";
echo "Send a message to:<br><font size=4>$title</font><BR>";
echo "<form onSubmit=\"return verify();\" method=\"post\" action=\"contact.php\" NAME=\"Cont\">";
echo "<INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"$lid\">";
echo "<INPUT TYPE=\"hidden\" NAME=\"submit\" VALUE=\"1\">";

    if($xoopsUser) {
	$idd =$xoopsUser->getVar("name", "E");
	$idde =$xoopsUser->getVar("email", "E");
	}

echo "<TABLE WIDTH=100% BORDER=0 CELLSPACING=1>
    <TR>
      <TD>Your Name: </TD>
      <TD><input type=\"text\" name=\"namep\" size=\"42\" value=\"$idd\"></TD>
    </TR>
    <TR>
      <TD>Your e-Mail: </TD>
      <TD><input type=\"text\" name=\"post\" size=\"42\" value=\"$idde\"></font></TD>
    </TR>
    <TR>
      <TD>Your Phone: </TD>
      <TD><input type=\"text\" name=\"tele\" size=\"42\"></font></TD>
    </TR>
    <TR>
      <TD>Message: </TD>
      <TD><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></TD>
    </TR>
</TABLE>
      <p><INPUT TYPE=\"submit\" VALUE=\"Send\">
</form>";

CloseTable();
include(XOOPS_ROOT_PATH."/footer.php");
}
}
?>