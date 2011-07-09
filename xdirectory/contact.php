<?php
// 
// ------------------------------------------------------------------------- //
//               E-Xoops: content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //
//lid prob added line below
$submit = intval($_GET['submit']);

if ($submit) {

//lid Prob 
$id = intval($_GET['id']);
$te = $_GET['tele'];
$na = $_GET['namep'];
$me = $_GET['messtext'];
$po = $_GET['post'];
//lid prob

include("header.php");
global $xoopsConfig, $xoopsDB, $myts, $meta;

 $result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$id'");
while(list($title, $email) = $xoopsDB->fetchRow($result)) {

if ($te) {
$teles = "Phone: $te";
}  else {
$teles = "";
}


$message = "Message from $na\ne-Mail: $po ".$meta['title']."\n$teles\n\n";
$message = "$na wrote:\n";
$message = "$me\n\n\n";
$message = "This message was sent by $na using the e-Mail form on {X_SITENAME} Business Directory.  \n\n\n";

	$subject = "Email Submission from {X_SITENAME}";
	$mail =& xoops_getMailer();
	$mail->useMail();
	$mail->setFromEmail($po);
	$mail->setToEmails($email);
	$mail->setSubject($subject);
	$mail->setBody($message);
	$mail->send();
	echo $mail->getErrors();
}
redirect_header("index.php",3,_CLA_MESSEND);
exit();

} else {


//lid prob
$lid = intval($_GET['lid']);
$id = $lid;
// lid prob
include("header.php");

	global $xoopsConfig, $xoopsDB, $xoopsUser;

include(XOOPS_ROOT_PATH."/header.php");

echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 2px solid #102bc9;'><tr class='bg4'><td valign='top'>\n";

 $result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$id'");
while(list($title, $email) = $xoopsDB->fetchRow($result)) {

echo "<script>
          function verify() {
                var msg = \"Errors were found during the validation of this form!\\n__________________________________________________\\n\\n\";
                var errors = \"FALSE\";

			
				if (document.cont.namep.value == \"\") {
                        errors = \"true\";
                        msg += \"The Name field is a required field.\\n\";
                }
				
				if (document.cont.post.value == \"\") {
                        errors = \"true\";
                        msg += \"The e-Mail field is a required field.\\n\";
                }
				
				if (document.cont.messtext.value == \"\") {
                        errors = \"true\";
                        msg += \"The Message field is a required field.\\n\";
                }
				
  
                if (errors == \"true\") {
                        msg += \"__________________________________________________\\n\\nPlease correct the errors listed above before submitting this form.\\n\";
                        alert(msg);
                        return false;
                }
          }
          </script>";


echo "<b></b><br /><br />";
echo "Send a message to:<br><font size='4'>$title</font><br />";
echo "<form onSubmit=\"return verify();\" method=\"get\" action=\"contact.php\" name=\"cont\">";
echo "<input type=\"hidden\" name=\"id\" value=\"$lid\">";
echo "<input type=\"hidden\" name=\"submit\" value=\"1\">";
    if($xoopsUser) {
	$idd =$xoopsUser->getVar("name", "E");
	$idde =$xoopsUser->getVar("email", "E");
	}

echo "<table width='100%' border='0' cellspacing='1'>
    <tr>
      <td>Your Name: </td>
      <td><input type=\"text\" name=\"namep\" size=\"42\" value=\"$idd\"></td>
    </tr>
    <tr>
      <td>Your e-Mail: </td>
      <td><input type=\"text\" name=\"post\" size=\"42\" value=\"$idde\"></font></td>
    </tr>
    <tr>
      <td>Your Phone: </td>
      <td><input type=\"text\" name=\"tele\" size=\"42\"></font></td>
    </tr>
    <tr>
      <td>Message: </td>
      <td><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></td>
    </tr>
</table>
      <p><input type=\"submit\" value=\"Send\">
</form>";

echo "</td></tr></table>";

}
include(XOOPS_ROOT_PATH."/footer.php");

}
?>