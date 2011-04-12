<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //

include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
include '../include/functions.php';
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$mytree = new XoopsTree($xoopsDB->prefix("xdir_cat"),"cid","pid");

function xdir()
{
    global $xoopsDB, $xoopsModule;
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
		echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
		."<tr class=\"odd\"><td>";
	// Temporarily 'homeless' links (to be revised in admin.php breakup)
		$result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_broken")."");
		list($totalbrokenlinks) = $xoopsDB->fetchRow($result);
	if($totalbrokenlinks>0){
		$totalbrokenlinks = "<span style='color: #ff0000; font-weight: bold'>$totalbrokenlinks</span>";
	}
		$result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_mod")."");
		list($totalmodrequests) = $xoopsDB->fetchRow($result2);
	if($totalmodrequests>0){
		$totalmodrequests = "<span style='color: #ff0000; font-weight: bold'>$totalmodrequests</span>";
	}
	$result3 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status=0");
    	list($totalnewlinks) = $xoopsDB->fetchRow($result3);
	if($totalnewlinks>0){
		$totalnewlinks = "<span style='color: #ff0000; font-weight: bold'>$totalnewlinks</span>";
	}
	echo " - <a href='".XOOPS_URL."/modules/system/admin.php?fct=preferences&op=showmod&mod=".$xoopsModule->getVar('mid')."'>"._MD_GENERALSET."</a>";
	echo "<br /><br />";
	echo " - <a href=index.php?op=linksConfigMenu>"._MD_ADDMODDELETE."</a>";
	echo "<br /><br />";
	echo " - <a href=index.php?op=listNewLinks>"._MD_LINKSWAITING." ($totalnewlinks)</a>";
	echo "<br /><br />";
	echo " - <a href=index.php?op=listBrokenLinks>"._MD_BROKENREPORTS." ($totalbrokenlinks)</a>";
	echo "<br /><br />";
	echo " - <a href=index.php?op=listModReq>"._MD_MODREQUESTS." ($totalmodrequests)</a>";
	$result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status>0");
    list($numrows) = $xoopsDB->fetchRow($result);
	echo "<br /><br /><div>";
	printf(_MD_THEREARE,$numrows);	echo "</div>";
   	echo"</td></tr></table>";
	xoops_cp_footer();
}

function listNewLinks()
{
	global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;
	// List links waiting for validation
	$linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/xdirectory/images/shots/");
    $result = $xoopsDB->query("select lid, cid, title, address, address2, city, state, zip, country, phone, fax, email, url, logourl, submitter, premium from ".$xoopsDB->prefix("xdir_links")." where status=0 order by date DESC");
    $numrows = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
        echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
           ."<tr class=\"odd\"><td>";
    echo "<h4>"._MD_LINKSWAITING." ($numrows)</h4><br />";
    if ( $numrows > 0 ) {
		while(list($lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $submitterid, $premium) = $xoopsDB->fetchRow($result)) {
			$result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
			list($description) = $xoopsDB->fetchRow($result2);
			$title = $myts->makeTboxData4Edit($title);
			$address = $myts->makeTboxData4Edit($address);
			$address2 = $myts->makeTboxData4Edit($address2);
			$city = $myts->makeTboxData4Edit($city);
			$state = $myts->makeTboxData4Edit($state);
			$zip = $myts->makeTboxData4Edit($zip);
			$country = $myts->makeTboxData4Edit($country);
			$phone = $myts->makeTboxData4Edit($phone);
			$fax = $myts->makeTboxData4Edit($fax);
			$email = $myts->makeTboxData4Edit($email);
			$url = $myts->makeTboxData4Edit($url);
			//		$url = urldecode($url);
			$logourl = $myts->makeTboxData4Edit($logourl);
			//		$logourl = urldecode($logourl);
			$description = $myts->makeTareaData4Edit($description);
			$submitter = XoopsUser::getUnameFromId($submitterid);
			$premium = $myts->makeTboxData4Edit($premium);
			echo "<form action=\"index.php\" method=post>\n";
			echo "<table width=\"80%\">";
			echo "<tr><td align=\"right\" nowrap>"._MD_SUBMITTER."</td><td>\n";
			echo "<a href=\"".XOOPS_URL."/userinfo.php?uid=".$submitterid."\">$submitter</a>";
			echo "</td></tr>\n";
			echo "<tr><td align=\"right\" nowrap>"._MD_SITETITLE."</td><td>";
			echo "<input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" value=\"$title\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSADDRESS."</td><td>";
			echo "<input type=\"text\" name=\"address\" size=\"50\" maxlength=\"250\" value=\"$address\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSADDRESS2."</td><td>";
			echo "<input type=\"text\" name=\"address2\" size=\"50\" maxlength=\"250\" value=\"$address2\">";			
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSCITY."</td><td>";
			echo "<input type=\"text\" name=\"city\" size=\"50\" maxlength=\"250\" value=\"$city\">";			
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSSTATE."</td><td>";
			echo "<input type=\"text\" name=\"state\" size=\"50\" maxlength=\"250\" value=\"$state\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSZIP."</td><td>";
			echo "<input type=\"text\" name=\"zip\" size=\"50\" maxlength=\"250\" value=\"$zip\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSCOUNTRY."</td><td>";
			echo "<input type=\"text\" name=\"country\" size=\"50\" maxlength=\"250\" value=\"$country\">";			
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSPHONE."</td><td>";
			echo "<input type=\"text\" name=\"phone\" size=\"50\" maxlength=\"250\" value=\"$phone\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSFAX."</td><td>";
			echo "<input type=\"text\" name=\"fax\" size=\"50\" maxlength=\"250\" value=\"$fax\">";
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_BUSEMAIL."</td><td>";
			echo "<input type=\"text\" name=\"email\" size=\"50\" maxlength=\"250\" value=\"$email\">";						
			echo "</td></tr><tr><td align=\"right\" nowrap>"._MD_SITEURL."</td><td>";
			echo "<input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"$url\">";
			echo " [ <a href=\"$url\" target=\"_blank\">"._MD_VISIT."</a> ]";
			echo "</td></tr>";
			echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";
			$mytree->makeMySelBox("title", "title", $cid);
			echo "</td></tr>\n";
			echo "<tr><td align=\"right\" valign=\"top\" nowrap>"._MD_DESCRIPTIONC."</td><td>\n";
			echo "<textarea name=description cols=\"60\" rows=\"5\">$description</textarea>\n";
			echo "</td></tr>\n";
			echo "<tr><td align=\"right\" nowrap>"._MD_SHOTIMAGE."</td><td>\n";
			echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\">\n";
			echo "<select size='1' name='logourl'>";
			echo "<option value=' '>------</option>";
			foreach($linkimg_array as $image){
				echo "<option value='".$image."'>".$image."</option>";
			}
			echo "</select>";
			echo "</td></tr>\n";
			echo "<tr><td></td><td>";
			$shotdir = "<b>".XOOPS_URL."/modules/xdirectory/images/shots/</b>";
			printf(_MD_SHOTMUST,$shotdir);
			echo "</td></tr>";
			echo "<tr><td align=\"right\" nowrap>"._MD_PREMIUM."</td><td>\n";
			echo "<select size='1' name='premium'>";
			echo "<option value='".$premium."'>".$premium."</option>";
			echo "<option value='0'>------</option>";
			echo "<option value=\"1\">1 WebLink</option>";
			echo "<option value=\"2\">2 Highlight</option>";
			echo "<option value=\"3\">3 Highlight Plus WebLink</option>";
			echo "<option value=\"4\">4 Page Sponsor</option>";
			echo "<option value=\"5\">5 Page Sponsor Plus WebLink</option>";
			echo "</select>";
			echo "</td></tr>";
			echo "</table>\n";
			echo "<br /><input type=\"hidden\" name=\"op\" value=\"approve\"></input>";
			echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\"></input>";
			echo "<input type=\"submit\" value=\""._MD_APPROVE."\"></form>\n";
			echo myTextForm("index.php?op=delNewLink&lid=$lid",_MD_DELETE);
			echo "<br /><br />";
		}
	} else {
		echo ""._MD_NOSUBMITTED."";
	}
	echo"</td></tr></table>";
	xoops_cp_footer();
}

function linksConfigMenu()
{
	global $xoopsDB,$xoopsConfig, $myts, $eh, $mytree;
	// Add a New Main Category
	xoops_cp_header();
	$linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/xdirectory/images/shots/");
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
	echo "<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
   	echo "<form method=post action=index.php>\n";
    	echo "<h4>"._MD_ADDMAIN."</h4><br />"._MD_TITLEC."<input type=text name=title size=30 maxlength=50><br />";
	echo ""._MD_IMGURL."<br /><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\" value=\"http://\"><br /><br />";
	echo "<input type=hidden name=cid value=0>\n";
	echo "<input type=hidden name=op value=addCat>";
	echo "<input type=submit value="._MD_ADD."><br /></form>";
    echo"</td></tr></table>";
    echo "<br />";
	// Add a New Sub-Category
    $result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_cat")."");
	list($numrows)=$xoopsDB->fetchRow($result);
    if ( $numrows > 0 ) {
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    	echo "<form method=post action=index.php>";
    	echo "<h4>"._MD_ADDSUB."</h4><br />"._MD_TITLEC."<input type=text name=title size=30 maxlength=50> "._MD_IN." ";
		$mytree->makeMySelBox("title", "title");
		#		echo "<br />"._MD_IMGURL."<br /><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";
    	echo "<input type=hidden name=op value=addCat><br /><br />";
		echo "<input type=submit value="._MD_ADD."><br /></form>";
		echo"</td></tr></table>";
		echo "<br />";
		// If there is a category, add a New Link

	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    	echo "<form method=post action=index.php>\n";
    	echo "<h4>"._MD_ADDNEWLINK."</h4><br />\n";
    	echo "<table width=\"80%\"><tr>\n";
		echo "<td align=\"right\">"._MD_SITETITLE."</td><td>";
    	echo "<input type=text name=title size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSADDRESS."</td><td>";
    	echo "<input type=text name=address size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSADDRESS2."</td><td>";
    	echo "<input type=text name=address2 size=50 maxlength=100>";
        echo "</td></tr>\n";		
		echo "<tr><td align=\"right\">"._MD_BUSCITY."</td><td>";
    	echo "<input type=text name=city size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSSTATE."</td><td>";
    	echo "<input type=text name=state size=2 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSZIP."</td><td>";
    	echo "<input type=text name=zip size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSCOUNTRY."</td><td>";
    	echo "<input type=text name=country size=50 maxlength=100>";
        echo "</td></tr>\n";		
		echo "<tr><td align=\"right\">"._MD_BUSPHONE."</td><td>";
    	echo "<input type=text name=phone size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSFAX."</td><td>";
    	echo "<input type=text name=fax size=50 maxlength=100>";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\">"._MD_BUSEMAIL."</td><td>";
    	echo "<input type=text name=email size=50 maxlength=100>";
        echo "</td></tr>\n";				
    	echo "<tr><td align=\"right\" nowrap>"._MD_SITEURL."</td><td>";
        echo "<input type=text name=url size=50 maxlength=250 value=\"http://\">";
        echo "</td></tr>";
        echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";
        $mytree->makeMySelBox("title", "title");
        echo "<tr><td align=\"right\" valign=\"top\" nowrap>"._MD_DESCRIPTIONC."</td><td>\n";
        xoopsCodeTarea("description",60,8);
		xoopsSmilies("description");
        //echo "<textarea name=description cols=60 rows=5></textarea>\n";
        echo "</td></tr>\n";
		echo "<tr><td align=\"right\"nowrap>"._MD_SHOTIMAGE."</td><td>\n";
		//echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\">";
		echo "<select size='1' name='logourl'>";
		echo "<option value=' '>------</option>";
		foreach($linkimg_array as $image){
			echo "<option value='".$image."'>".$image."</option>";
		}
		echo "</select>";
		echo "</td></tr>\n";
		$shotdir = "<b>".XOOPS_URL."/modules/xdirectory/images/shots/</b>";
		echo "<tr><td></td><td>";
		printf(_MD_SHOTMUST,$shotdir);
		echo "</td></tr>\n";
		echo "<tr><td align=\"right\" nowrap>"._MD_PREMIUM."</td><td>\n";
		echo "<select name=\"premium\">";
		echo "<option value=\"0\">------</option>";
		echo "<option value=\"1\">1 WebLink</option>";
		echo "<option value=\"2\">2 Highlight</option>";
		echo "<option value=\"3\">3 Highlight Plus WebLink</option>";
		echo "<option value=\"4\">4 Page Sponsor</option>";
		echo "<option value=\"5\">5 Page Sponsor Plus WebLink</option>";
		echo "</select>";
		echo "</td></tr>\n";
        echo "</table>\n<br />";
		echo  "<input type=\"hidden\" name=\"op\" value=\"addLink\"></input>";
		echo "<input type=\"submit\" class=\"button\" value=\""._MD_ADD."\"></input>\n";
    	echo "</form>";
		echo"</td></tr></table>";
		echo "<br />";

		// Modify Category
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    	echo "
    	</center><form method=post action=index.php>
    	<h4>"._MD_MODCAT."</h4><br />";
    	echo _MD_CATEGORYC;
    	$mytree->makeMySelBox("title", "title");
    	echo "<br /><br />\n";
    	echo "<input type=hidden name=op value=modCat>\n";
    	echo "<input type=submit value="._MD_MODIFY.">\n";
		echo "</form>";
		echo"</td></tr></table>";
		echo "<br />";
    }
	// Modify Link
    $result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")."");
    list($numrows2) = $xoopsDB->fetchRow($result2);
    if ( $numrows2 > 0 ) {
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    	echo "<form method=get action=\"index.php\">\n";
    	echo "<h4>"._MD_MODLINK."</h4><br />\n";
    	echo _MD_LINKID."<input type=text name=lid size=12 maxlength=11>\n";
		echo "<input type=hidden name=fct value=xdir>\n";
   		echo "<input type=hidden name=op value=modLink><br /><br />\n";
		echo "<input type=submit value="._MD_MODIFY."></form>\n";
		echo"</td></tr></table>";
   	}
	xoops_cp_footer();
}

function modLink()
{
   	global $xoopsDB, $_GET, $myts, $eh, $mytree, $xoopsConfig;
   	$linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/xdirectory/images/shots/");
   	$lid = $_GET['lid'];
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
   	$result = $xoopsDB->query("select cid, title, address, address2, city, state, zip, country, phone, fax, email, url, logourl, premium from ".$xoopsDB->prefix("xdir_links")." where lid=$lid") or $eh->show("0013");
   	echo "<h4>"._MD_MODLINK."</h4><br />";
   	list($cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $premium) = $xoopsDB->fetchRow($result);
   	$title = $myts->makeTboxData4Edit($title);
   	$address = $myts->makeTboxData4Edit($address);
	$address2 = $myts->makeTboxData4Edit($address2);
   	$city = $myts->makeTboxData4Edit($city);
   	$state = $myts->makeTboxData4Edit($state);
   	$zip = $myts->makeTboxData4Edit($zip);
	$country = $myts->makeTboxData4Edit($country);
   	$phone = $myts->makeTboxData4Edit($phone);
	$fax = $myts->makeTboxData4Edit($fax);
	$email = $myts->makeTboxData4Edit($email);
   	$url = $myts->makeTboxData4Edit($url);
	//   	$url = urldecode($url);
    $logourl = $myts->makeTboxData4Edit($logourl);
	$premium = $myts->makeTboxData4Edit($premium);
	//  	$logourl = urldecode($logourl);
    $result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
    list($description)=$xoopsDB->fetchRow($result2);
    $GLOBALS['description'] = $myts->makeTareaData4Edit($description);
	echo "<table>";
    echo "<form method=post action=index.php>";
    echo "<tr><td>"._MD_LINKID."</td><td><b>$lid</b></td></tr>";
    echo "<tr><td>"._MD_SITETITLE."</td><td><input type=text name=title value=\"$title\" size=50 maxlength=100></input></td></tr>\n";
	echo "<tr><td>"._MD_BUSADDRESS."</td><td><input type=text name=address value=\"$address\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSADDRESS2."</td><td><input type=text name=address2 value=\"$address2\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSCITY."</td><td><input type=text name=city value=\"$city\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSSTATE."</td><td><input type=text name=state value=\"$state\" size=2 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSZIP."</td><td><input type=text name=zip value=\"$zip\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSCOUNTRY."</td><td><input type=text name=country value=\"$country\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSPHONE."</td><td><input type=text name=phone value=\"$phone\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSFAX."</td><td><input type=text name=fax value=\"$fax\" size=50 maxlength=100></td></tr>\n";
	echo "<tr><td>"._MD_BUSEMAIL."</td><td><input type=text name=email value=\"$email\" size=50 maxlength=100></td></tr>\n";
    echo "<tr><td>"._MD_SITEURL."</td><td><input type=text name=url value=\"$url\" size=50 maxlength=250></input></td></tr>\n";
    echo "<tr><td valign=\"top\">"._MD_DESCRIPTIONC."</td><td>";
    xoopsCodeTarea("description",60,8);
	xoopsSmilies("description");
    //echo "<textarea name=description cols=60 rows=5>$description</textarea>";
    echo "</td></tr>";
    echo "<tr><td>"._MD_CATEGORYC."</td><td>";
    $mytree->makeMySelBox("title", "title", $cid);
	echo "</td></tr>\n";
	echo "<tr><td>"._MD_SHOTIMAGE."</td><td>";
	//echo "<input type=text name=logourl value=\"$logourl\" size=\"50\" maxlength=\"60\"></input>
	echo "<select size='1' name='logourl'>";
	echo "<option value=' '>------</option>";
	foreach($linkimg_array as $image){
		if ( $image == $logourl ) {
			$opt_selected = "selected='selected'";
		}else{
			$opt_selected = "";
		}
		echo "<option value='".$image."' $opt_selected>".$image."</option>";
	}
	echo "</select>";
	echo "</td></tr>\n";
	echo "<tr><td></td><td>";
	$shotdir = "<b>".XOOPS_URL."/modules/xdirectory/images/shots/</b>";
	printf(_MD_SHOTMUST,$shotdir);
	echo "</td></tr>";
	echo "<tr><td align=\"right\" nowrap>"._MD_PREMIUM."</td><td>\n";
	echo "<select size=\"1\" name=\"premium\">";
		if ( $premium == 0 ) {
		echo "<option value=\"0\">0 none</option>";
		}elseif ( $premium == 1 ){
		echo "<option value=\"1\">1 WebLink</option>";
		}elseif ( $premium == 2 ){
		echo "<option value=\"2\">2 Highlight</option>";
		}elseif ( $premium == 3 ){
		echo "<option value=\"3\">3 Highlight Plus WebLink</option>";
		}elseif ( $premium == 4 ){
		echo "<option value=\"4\">4 Page Sponsor</option>";
		}elseif ( $premium == 5 ){
		echo "<option value=\"5\">5 Page Sponsor Plus WebLink</option>";
	}	
	echo "<option value=\"0\">------</option>";
	echo "<option value=\"1\">1 WebLink</option>";
	echo "<option value=\"2\">2 Highlight</option>";
	echo "<option value=\"3\">3 Highlight Plus WebLink</option>";
	echo "<option value=\"4\">4 Page Sponsor</option>";
	echo "<option value=\"5\">5 Page Sponsor Plus WebLink</option>";
	echo "</select>";
	echo "</td></tr>\n";
	echo "</table>";
    echo "<br /><br /><input type=hidden name=lid value=$lid></input>\n";
    echo "<input type=hidden name=op value=modLinkS><input type=submit value="._MD_MODIFY.">";
	// echo " <input type=button value="._MD_DELETE." onclick=\"javascript:location='index.php?op=delLink&lid=".$lid."'\">";
	//echo " <input type=button value="._MD_CANCEL." onclick=\"javascript:history.go(-1)\">";
	echo "</form>\n";

	echo "<table><tr><td>\n";
	echo myTextForm("index.php?op=delLink&lid=".$lid , _MD_DELETE);
	echo "</td><td>\n";
	echo myTextForm("index.php?op=linksConfigMenu", _MD_CANCEL);
	echo "</td></tr></table>\n";
    echo "<hr>";

    $result5=$xoopsDB->query("SELECT count(*) FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid");
    list($totalvotes) = $xoopsDB->fetchRow($result5);
    echo "<table width=100%>\n";
    echo "<tr><td colspan=7><b>";
	printf(_MD_TOTALVOTES,$totalvotes);
	echo "</b><br /><br /></td></tr>\n";
    // Show Registered Users Votes
    $result5=$xoopsDB->query("SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");
    $votes = $xoopsDB->getRowsNum($result5);
    echo "<tr><td colspan=7><br /><br /><b>";
	printf(_MD_USERTOTALVOTES,$votes);
	echo "</b><br /><br /></td></tr>\n";
    echo "<tr><td><b>" ._MD_USER."  </b></td><td><b>" ._MD_IP."  </b></td><td><b>" ._MD_RATING."  </b></td><td><b>" ._MD_USERAVG."  </b></td><td><b>" ._MD_TOTALRATE."  </b></td><td><b>" ._MD_DATE."  </b></td><td align=\"center\"><b>" ._MD_DELETE."</b></td></tr>\n";
    if ($votes == 0){
		echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_NOREGVOTES."<br /></td></tr>\n";
    }
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
		//	$ratingtimestamp = formatTimestamp($ratingtimestamp);
    	//Individual user information
    	$result2=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE ratinguser = '$ratinguser'");
        $uservotes = $xoopsDB->getRowsNum($result2);
        $useravgrating = 0;
        while ( list($rating2) = $xoopsDB->fetchRow($result2) ) {
			$useravgrating = $useravgrating + $rating2;
		}
        $useravgrating = $useravgrating / $uservotes;
        $useravgrating = number_format($useravgrating, 1);
		$ratingusername = XoopsUser::getUnameFromId($ratinguser);
        echo "<tr><td bgcolor=\"".$colorswitch."\">".$ratingusername."</td><td bgcolor=\"$colorswitch\">".$ratinghostname."</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">".$useravgrating."</td><td bgcolor=\"$colorswitch\">".$uservotes."</td><td bgcolor=\"$colorswitch\">".$ratingtimestamp."</td><td bgcolor=\"$colorswitch\" align=\"center\"><b>".myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid", "X")."</b></td></tr>\n";
    	$x++;
    	if ( $colorswitch == "dddddd" ) {
			$colorswitch="ffffff";
    	} else {
			$colorswitch="dddddd";
		}
    }
	// Show Unregistered Users Votes
    $result5=$xoopsDB->query("SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");
    $votes = $xoopsDB->getRowsNum($result5);
    echo "<tr><td colspan=7><b><br /><br />";
	printf(_MD_ANONTOTALVOTES,$votes);
	echo "</b><br /><br /></td></tr>\n";
    echo "<tr><td colspan=2><b>" ._MD_IP."  </b></td><td colspan=3><b>" ._MD_RATING."  </b></td><td><b>" ._MD_DATE."  </b></b></td><td align=\"center\"><b>" ._MD_DELETE."</b></td><br /></tr>";
    if ( $votes == 0 ) {
		echo "<tr><td colspan=\"7\" align=\"center\">" ._MD_NOUNREGVOTES."<br /></td></tr>";
    }
    $x=0;
    $colorswitch="dddddd";
    while ( list($ratingid, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5) ) {
		$formatted_date = formatTimestamp($ratingtimestamp);
        echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" aling=\"center\"><b>".myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid", "X")."</b></td></tr>";
    	$x++;
    	if ( $colorswitch == "dddddd" ) {
			$colorswitch="ffffff";
    	} else {
			$colorswitch="dddddd";
		}
    }
    echo "<tr><td colspan=\"6\"> <br /></td></tr>\n";
    echo "</table>\n";
    echo"</td></tr></table>";
    xoops_cp_footer();
}

function delVote()
{
	global $xoopsDB, $_GET, $eh;
    $rid = $_GET['rid'];
    $lid = $_GET['lid'];
	$sql = sprintf("DELETE FROM %s WHERE ratingid = %u", $xoopsDB->prefix("xdir_votedata"), $rid);
    $xoopsDB->query($sql) or $eh->show("0013");
    updaterating($lid);
    redirect_header("index.php",1,_MD_VOTEDELETED);
    exit();
}

function listBrokenLinks()
{
   	global $xoopsDB, $eh;
   	$result = $xoopsDB->query("select * from ".$xoopsDB->prefix("xdir_broken")." group by lid order by reportid DESC");
   	$totalbrokenlinks = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
	echo "<h4>"._MD_BROKENREPORTS." ($totalbrokenlinks)</h4><br />";

   	if ( $totalbrokenlinks == 0 ) {
		echo _MD_NOBROKEN;
    } else {
		echo "<center>
		"._MD_IGNOREDESC."<br />
		"._MD_DELETEDESC."</center><br /><br /><br />";
        $colorswitch="dddddd";
		echo "<table align=\"center\" width=\"90%\">";
        echo "
        <tr>
        <td><b>Link Name</b></td>
        <td><b>" ._MD_REPORTER."</b></td>
        <td><b>" ._MD_LINKSUBMITTER."</b></td>
        <td><b>" ._MD_IGNORE."</b></td>
        <td><b>" ._EDIT."</b></td>
        <td><b>" ._MD_DELETE."</b></td>
        </tr>";
        while ( list($reportid, $lid, $sender, $ip)=$xoopsDB->fetchRow($result) ) {
			$result2 = $xoopsDB->query("select title, url, submitter from ".$xoopsDB->prefix("xdir_links")." where lid=$lid");
			if ( $sender != 0 ) {
				$result3 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid=$sender");
				list($uname, $email)=$xoopsDB->fetchRow($result3);
			}
    		list($title, $url, $ownerid)=$xoopsDB->fetchRow($result2);
			//			$url=urldecode($url);
    		$result4 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
    		list($owner, $owneremail)=$xoopsDB->fetchRow($result4);
    		echo "<tr><td bgcolor=$colorswitch><a href=$url target='_blank'>$title</a></td>";
    		if ( $email=='' ) {
				echo "<td bgcolor=\"".$colorswitch."\">".$sender." (".$ip.")";
			} else {
				echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$email."\">".$uname."</a> (".$ip.")";
			}
   			echo "</td>";
   			if ( $owneremail == '' ) {
				echo "<td bgcolor=\"".$colorswitch."\">".$owner."";
			} else {
				echo "<td bgcolor=\"".$colorswitch."\"><a href=\"mailto:".$owneremail."\">".$owner."</a>";
			}

			echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=ignoreBrokenLinks&lid=$lid" , "X");
			echo "</td><td bgcolor='$colorswitch' align='center'>\n";
			echo myTextForm("index.php?op=modLink&lid=$lid" , "X");
			echo "</td><td align='center' bgcolor='$colorswitch'>\n";
			echo myTextForm("index.php?op=delBrokenLinks&lid=$lid" , "X");
			echo "</td></tr>\n";

    		if ( $colorswitch == "#dddddd" ) {
				$colorswitch="#ffffff";
			} else {
				$colorswitch="#dddddd";
			}
    	}
		echo "</table>";
    }

	echo"</td></tr></table>";
	xoops_cp_footer();
}

function delBrokenLinks()
{
	global $xoopsDB, $_GET, $eh;
    $lid = $_GET['lid'];
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_broken"), $lid);
    $xoopsDB->query($sql) or $eh->show("0013");
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
    $xoopsDB->query($sql) or $eh->show("0013");
    redirect_header("index.php",1,_MD_LINKDELETED);
	exit();
}

function ignoreBrokenLinks()
{
	global $xoopsDB, $_GET, $eh;
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_broken"), $_GET['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    redirect_header("index.php",1,_MD_BROKENDELETED);
	exit();
}


function listModReq()
{
	global $xoopsDB, $myts, $eh, $mytree, $xoopsModuleConfig;
    $result = $xoopsDB->query("select * from ".$xoopsDB->prefix("xdir_mod")." order by requestid");
    $totalmodrequests = $xoopsDB->getRowsNum($result);
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    echo "<h4>"._MD_USERMODREQ." ($totalmodrequests)</h4><br />";
	if ( $totalmodrequests > 0 ) {
		echo "<table width=95%><tr><td>";
		$lookup_lid = array();
		while ( list($requestid, $lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $premium, $description, $modifysubmitter)=$xoopsDB->fetchRow($result) ) {
			$lookup_lid[$requestid] = $lid;
			$result2 = $xoopsDB->query("select cid, title, address, address2, city, state, zip, country, phone, fax, email, url, logourl, submitter from ".$xoopsDB->prefix("xdir_links")." where lid=$lid");
			list($origcid, $origtitle, $origaddress, $origaddress2, $origcity, $origstate, $origzip, $origcountry, $origphone, $origfax, $origemail, $origurl, $origlogourl, $ownerid)=$xoopsDB->fetchRow($result2);
			$result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
			list($origdescription) = $xoopsDB->fetchRow($result2);
			$result7 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$modifysubmitter'");
			$result8 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
			$cidtitle=$mytree->getPathFromId($cid, "title");
			$origcidtitle=$mytree->getPathFromId($origcid, "title");
			list($submitter, $submitteremail)=$xoopsDB->fetchRow($result7);
			list($owner, $owneremail)=$xoopsDB->fetchRow($result8);
			$title = $myts->htmlSpecialChars($title);
			$address = $myts->htmlSpecialChars($address);
			$address2 = $myts->htmlSpecialChars($address2);
			$city= $myts->htmlSpecialChars($city);
			$state = $myts->htmlSpecialChars($state);
			$zip = $myts->htmlSpecialChars($zip);
			$country = $myts->htmlSpecialChars($country);
			$phone = $myts->htmlSpecialChars($phone);
			$fax = $myts->htmlSpecialChars($fax);
			$email = $myts->htmlSpecialChars($email);
    		$url = $myts->htmlSpecialChars($url);
			//			$url = urldecode($url);
			// use original image file to prevent users from changing screen shots file
			$origlogourl = $myts->htmlSpecialChars($origlogourl);
    		$logourl = $origlogourl;
			//			$logourl = urldecode($logourl);
    		$description = $myts->makeTareaData4Show($description);
    		$origurl = $myts->htmlSpecialChars($origurl);
			//			$origurl = urldecode($origurl);
			//			$origlogourl = urldecode($origlogourl);
    		$origdescription = $myts->makeTareaData4Show($origdescription);
    		if ( $owner == "" ) {
				$owner="administration";
			}
    		echo "<table border=1 bordercolor=black cellpadding=5 cellspacing=0 align=center width=450><tr><td>
    	   	<table width=100% bgcolor=dddddd>
    	    <tr>
    	    <td valign=top width=45%><b>"._MD_ORIGINAL."</b></td>
	       	<td rowspan=16 valign=top align=left><small><br />"._MD_DESCRIPTIONC."<br />$origdescription</small></td>
    	    </tr>
    	    <tr><td valign=top width=45%><small>"._MD_SITETITLE."<br>$origtitle</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSADDRESS."<br>$origaddress</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSADDRESS2."<br>$origaddress2</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSCITY."<br>$origcity</small></td></tr>			
			<tr><td valign=top width=45%><small>"._MD_BUSSTATE."<br>$origstate</small></td></tr>		
			<tr><td valign=top width=45%><small>"._MD_BUSZIP."<br>$origzip</small></td></tr>		
			<tr><td valign=top width=45%><small>"._MD_BUSCOUNTRY."<br>$origcountry</small></td></tr>			
			<tr><td valign=top width=45%><small>"._MD_BUSPHONE."<br>$origphone</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSFAX."<br>$origfax</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSEMAIL."<br>$origemail</small></td></tr>
    	    <tr><td valign=top width=45%><small>"._MD_SITEURL."<br>".$origurl."</small></td></tr>
	     	<tr><td valign=top width=45%><small>"._MD_CATEGORYC."<br>$origcidtitle</small></td></tr>
	     	<tr><td valign=top width=45%><small>"._MD_SHOTIMAGE."</small>";
			if ( $xoopsModuleConfig['useshots'] && !empty($origlogourl) ) {
				echo "<img src=\"".XOOPS_URL."/modules/xdirectory/images/shots/".$origlogourl."\" width=\"".$xoopsModuleConfig['shotwidth']."\" />";
			} else {
				echo " ";
			}
			echo "</td></tr>
    	   	</table></td></tr><tr><td>
    	   	<table width=100%>
    	    <tr>
    	    <td valign=top width=45%><b>"._MD_PROPOSED."</b></td>
    	    <td rowspan=14 valign=top align=left><small><br />"._MD_DESCRIPTIONC."<br />$description</small></td>
    	    </tr>
    	    <tr><td valign=top width=45%><small>"._MD_SITETITLE."$title</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSADDRESS."<br>$address</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSADDRESS2."<br>$address2</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSCITY."<br>$city</small></td></tr>	
			<tr><td valign=top width=45%><small>"._MD_BUSSTATE."<br>$state</small></td></tr>	
			<tr><td valign=top width=45%><small>"._MD_BUSZIP."<br>$zip</small></td></tr>	
			<tr><td valign=top width=45%><small>"._MD_BUSCOUNTRY."<br>$country</small></td></tr>	
			<tr><td valign=top width=45%><small>"._MD_BUSPHONE."<br>$phone</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSFAX."<br>$fax</small></td></tr>
			<tr><td valign=top width=45%><small>"._MD_BUSEMAIL."<br>$email</small></td></tr>
    	    <tr><td valign=top width=45%><small>"._MD_SITEURL."<br>".$url."</small></td></tr>
	     	<tr><td valign=top width=45%><small>"._MD_CATEGORYC."$cidtitle</small></td></tr>
	     	<tr><td valign=top width=45%><small>"._MD_SHOTIMAGE."</small>";
			if ( $xoopsModuleConfig['useshots'] == 1 && !empty($logourl) ) {
				echo "<img src=\"".XOOPS_URL."/modules/xdirectory/images/shots/".$logourl."\" width=\"".$xoopsModuleConfig['shotwidth']."\" alt=\"/\" />";
			} else {
				echo " ";
			}
			echo "</td></tr>
    	   	</table></td></tr></table>
    		<table align=center width=450>
    	  	<tr>";
    		if ( $submitteremail == "" ) {
				echo "<td align=left><small>"._MD_SUBMITTER."$submitter</small></td>";
			} else {
				echo "<td align=left><small>"._MD_SUBMITTER."<a href=mailto:".$submitteremail.">".$submitter."</a></small></td>";
			}
    		if ( $owneremail == "" ) {
				echo "<td align=center><small>"._MD_OWNER."".$owner."</small></td>";
			} else {
				echo "<td align=center><small>"._MD_OWNER."<a href=mailto:".$owneremail.">".$owner."</a></small></td>";
			}
			echo "<td align=right><small>\n";
			echo "<table><tr><td>\n";
			echo myTextForm("index.php?op=changeModReq&requestid=$requestid" , _MD_APPROVE);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=modLink&lid=$lookup_lid[$requestid]", _EDIT);
			echo "</td><td>\n";
			echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _MD_IGNORE);
			echo "</td></tr></table>\n";
			echo "</small></td></tr>\n";
    		echo "</table><br /><br />";
    	}
    	echo "</td></tr></table>";
	} else {
		echo _MD_NOMODREQ;
	}
	echo"</td></tr></table>";
	xoops_cp_footer();
}

function changeModReq()
{
	global $xoopsDB, $_GET, $eh, $myts;
    $requestid = $_GET['requestid'];
    $query = "select requestid, lid, cid, title, address, address2, city, state, zip, country, phone, fax, email, url, logourl, premium, description from ".$xoopsDB->prefix("xdir_mod")." where requestid=".$requestid."";
    $result = $xoopsDB->query($query);
    while ( list($requestid, $lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $premium, $description)=$xoopsDB->fetchRow($result) ) {
		if ( get_magic_quotes_runtime() ) {
			$title = stripslashes($title);
    		$address = stripslashes($address);
			$address2 = stripslashes($address2);
    		$city = stripslashes($city);
    		$state = stripslashes($state);
    		$zip = stripslashes($zip);
			$country = stripslashes($country);
    		$phone = stripslashes($phone);
			$fax = stripslashes($fax);
			$email = stripslashes($email);
    		$url = stripslashes($url);
    		$logourl = stripslashes($logourl);
    		$description = stripslashes($description);
		}
		$title = addslashes($title);
    	$address = addslashes($address);
		$address2 = addslashes($address2);
    	$city = addslashes($city);
    	$state = addslashes($state);
    	$zip = addslashes($zip);
		$country = addslashes($country);
    	$phone = addslashes($phone);		
		$fax = addslashes($fax);
		$email = addslashes($email);
    	$url = addslashes($url);
    	$logourl = addslashes($logourl);
    	$description = addslashes($description);
		$sql = sprintf("UPDATE %s SET cid = %u, title = '%s', address = '%s', address2 = '%s', city = '%s', state = '%s', zip = '%s', country = '%s', phone = '%s', fax = '%s', email = '%s', url = '%s', logourl = '%s', status = %u, date = %u, premium = %u WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, 2, time(), $premium, $lid);
    	$xoopsDB->query($sql) or $eh->show("0013");
		$sql = sprintf("UPDATE %s SET description = '%s' WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $description, $lid);
		$xoopsDB->query($sql) or $eh->show("0013");
		$sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("xdir_mod"), $requestid);
		$xoopsDB->query($sql) or $eh->show("0013");
    }
    redirect_header("index.php",1,_MD_DBUPDATED);
	exit();
}

function ignoreModReq()
{
	global $xoopsDB, $_GET, $eh;
	$sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("xdir_mod"), $_GET['requestid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    redirect_header("index.php",1,_MD_MODREQDELETED);
	exit();
}

function modLinkS()
{
   	global $xoopsDB, $_POST, $myts, $eh;
   	$cid = $_POST["cid"];
   	if ( ($_POST["title"]) || ($_POST["title"]!="") ) {
		//		$url = $myts->formatURL($_POST["url"]);
		//		$url = urlencode($url);
		$title = $myts->makeTboxData4Save($_POST["title"]);
	}
    $address = $myts->makeTboxData4Save($_POST["address"]);
	$address2 = $myts->makeTboxData4Save($_POST["address2"]);
    $city = $myts->makeTboxData4Save($_POST["city"]);
    $state = $myts->makeTboxData4Save($_POST["state"]);
    $zip = $myts->makeTboxData4Save($_POST["zip"]);
	$country = $myts->makeTboxData4Save($_POST["country"]);
    $phone = $myts->makeTboxData4Save($_POST["phone"]);
	$fax = $myts->makeTboxData4Save($_POST["fax"]);
	$email = $myts->makeTboxData4Save($_POST["email"]);
    $url = $myts->makeTboxData4Save($_POST["url"]);
	$logourl = $myts->makeTboxData4Save($_POST["logourl"]);
	$premium = $myts->makeTboxData4Save($_POST["premium"]);
    $description = $myts->makeTareaData4Save($_POST["description"]);
    $xoopsDB->query("update ".$xoopsDB->prefix("xdir_links")." set cid='$cid', title='$title', address='$address', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', fax='$fax', email='$email', url='$url', logourl='$logourl', status=2, date=".time().", premium='$premium' where lid=".$_POST['lid']."")  or $eh->show("0013");
    $xoopsDB->query("update ".$xoopsDB->prefix("xdir_text")." set description='$description' where lid=".$_POST['lid']."")  or $eh->show("0013");
    redirect_header("index.php",1,_MD_DBUPDATED);
	exit();
}

function delLink()
{
	global $xoopsDB, $_GET, $eh, $xoopsModule;
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $_GET['lid']);
   	$xoopsDB->query($sql) or $eh->show("0013");
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $_GET['lid']);
	$xoopsDB->query($sql) or $eh->show("0013");
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $_GET['lid']);
	$xoopsDB->query($sql) or $eh->show("0013");
	// delete comments
	xoops_comment_delete($xoopsModule->getVar('mid'), $_GET['lid']);
	// delete notifications
	xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'link', $_GET['lid']);

    redirect_header("index.php",1,_MD_LINKDELETED);
	exit();
}

function modCat()
{
	global $xoopsDB, $_POST, $myts, $eh, $mytree;
    $cid = $_POST["cid"];
	xoops_cp_header();
	echo "<h4>"._MD_WEBLINKSCONF."</h4>";
	echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
	."<tr class=\"odd\"><td>";
    echo "<h4>"._MD_MODCAT."</h4><br />";
	$result=$xoopsDB->query("select pid, title, imgurl from ".$xoopsDB->prefix("xdir_cat")." where cid=$cid");
	list($pid,$title,$imgurl) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Edit($title);
	$imgurl = $myts->makeTboxData4Edit($imgurl);
	echo "<form action=index.php method=post>"._MD_TITLEC."<input type=text name=title value=\"$title\" size=51 maxlength=50><br /><br />"._MD_IMGURLMAIN."<br /><input type=text name=imgurl value=\"$imgurl\" size=100 maxlength=150><br /><br />";
	echo _MD_PARENT." ";
	$mytree->makeMySelBox("title", "title", $pid, 1, "pid");
	//	<input type=hidden name=pid value=\"$pid\">
	echo "<br /><input type=\"hidden\" name=\"cid\" value=\"".$cid."\">
	<input type=\"hidden\" name=\"op\" value=\"modCatS\"><br />
	<input type=\"submit\" value=\""._MD_SAVE."\">
	<input type=\"button\" value=\""._MD_DELETE."\" onClick=\"location='index.php?pid=$pid&cid=$cid&op=delCat'\">";
	echo " <input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\ /\">";
	echo "</form>";
	echo"</td></tr></table>";
	xoops_cp_footer();
}

function modCatS()
{
   	global $xoopsDB, $_POST, $myts, $eh;
   	$cid =  $_POST['cid'];
   	$pid =  $_POST['pid'];
   	$title =  $myts->makeTboxData4Save($_POST['title']);
	if (empty($title)) {
		redirect_header("index.php", 2, _MD_ERRORTITLE);
	}
	if ( ($_POST["imgurl"]) || ($_POST["imgurl"]!="") ) {
		$imgurl = $myts->makeTboxData4Save($_POST["imgurl"]);
	}
	$xoopsDB->query("update ".$xoopsDB->prefix("xdir_cat")." set pid=$pid, title='$title', imgurl='$imgurl' where cid=$cid") or $eh->show("0013");
    redirect_header("index.php",1,_MD_DBUPDATED);
}

function delCat()
{
   	global $xoopsDB, $_GET, $_POST, $eh, $mytree, $xoopsModule;
   	$cid =  isset($_POST['cid']) ? intval($_POST['cid']) : intval($_GET['cid']);
	$ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    if ( $ok == 1 ) {
		//get all subcategories under the specified category
		$arr=$mytree->getAllChildId($cid);
		$dcount=count($arr);
		for ( $i=0;$i<$dcount;$i++ ) {
			//get all links in each subcategory
			$result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("xdir_links")." where cid=".$arr[$i]."") or $eh->show("0013");
			//now for each link, delete the text data and vote ata associated with the link
			while ( list($lid)=$xoopsDB->fetchRow($result) ) {
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
				$xoopsDB->query($sql) or $eh->show("0013");
				xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
				xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
			}
			xoops_notification_deltebyitem($xoopsModule->getVar('mid'), 'category', $arr[$i]);

			//all links for each subcategory is deleted, now delete the subcategory data
			$sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("xdir_cat"), $arr[$i]);
			$xoopsDB->query($sql) or $eh->show("0013");
		}
		//all subcategory and associated data are deleted, now delete category data and its associated data
		$result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("xdir_links")." where cid=".$cid."") or $eh->show("0013");
		while ( list($lid)=$xoopsDB->fetchRow($result) ) {
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $lid);
			$xoopsDB->query($sql) or $eh->show("0013");
			// delete comments
			xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
			// delete notifications
			xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
		}
		$sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("xdir_cat"), $cid);
	    $xoopsDB->query($sql) or $eh->show("0013");
		xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $cid);
        redirect_header("index.php",1,_MD_CATDELETED);
		exit();
    } else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delCat', 'cid' => $cid, 'ok' => 1), 'index.php', _MD_WARNING);
		xoops_cp_footer();
    }
}

function delNewLink()
{
	global $xoopsDB, $_GET, $eh, $xoopsModule;
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $_GET['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
	$sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $_GET['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
	// delete comments
	xoops_comment_delete($xoopsModule->getVar('mid'), $_GET['lid']);
	// delete notifications
	xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $_GET['lid']);
	redirect_header("index.php",1,_MD_LINKDELETED);
}

function addCat()
{
	global $xoopsDB, $_POST, $myts, $eh;
    $pid = $_POST["cid"];
    $title = $myts->makeTboxData4Save($_POST["title"]);
	if (empty($title)) {
		redirect_header("index.php",2,_MD_ERRORTITLE);
		exit();
	}
    if ( ($_POST["imgurl"]) || ($_POST["imgurl"]!="") ) {
		//		$imgurl = $myts->formatURL($_POST["imgurl"]);
		//		$imgurl = urlencode($imgurl);
		$imgurl = $myts->makeTboxData4Save($_POST["imgurl"]);
	}
	$newid = $xoopsDB->genId($xoopsDB->prefix("xdir_cat")."_cid_seq");
	$sql = sprintf("INSERT INTO %s (cid, pid, title, imgurl) VALUES (%u, %u, '%s', '%s')", $xoopsDB->prefix("xdir_cat"), $newid, $pid, $title, $imgurl);
	$xoopsDB->query($sql) or $eh->show("0013");
	if ($newid == 0) {
		$newid = $xoopsDB->getInsertId();
	}
	global $xoopsModule;
	$tags = array();
	$tags['CATEGORY_NAME'] = $title;
	$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newid;
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('global', 0, 'new_category', $tags);
	redirect_header("index.php",1,_MD_NEWCATADDED);
}

function addLink()
{
	global $xoopsConfig, $xoopsDB, $myts, $xoopsUser, $xoopsModule, $eh, $_POST;
	if ( ($_POST["title"]) || ($_POST["title"]!="") ) {
		//	$url=$myts->formatURL($_POST["url"]);
		//		$url = urlencode($url);
		$title = $myts->makeTboxData4Save($_POST["title"]);
	}
    $address = $myts->makeTboxData4Save($_POST["address"]);
	$address2 = $myts->makeTboxData4Save($_POST["address2"]);
    $city = $myts->makeTboxData4Save($_POST["city"]);
    $state = $myts->makeTboxData4Save($_POST["state"]);
    $zip = $myts->makeTboxData4Save($_POST["zip"]);
	$country = $myts->makeTboxData4Save($_POST["country"]);
    $phone = $myts->makeTboxData4Save($_POST["phone"]);
	$fax = $myts->makeTboxData4Save($_POST["fax"]);
	$email = $myts->makeTboxData4Save($_POST["email"]);
    $url = $myts->makeTboxData4Save($_POST["url"]);
	$logourl = $myts->makeTboxData4Save($_POST["logourl"]);
	$premium = $myts->makeTboxData4Save($_POST["premium"]);
    $description = $myts->makeTareaData4Save($_POST["description"]);
    $submitter = $xoopsUser->uid();
    $result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where title='$title'");
    list($numrows) = $xoopsDB->fetchRow($result);
	$errormsg = "";
	$error = 0;
    if ( $numrows > 0 ) {
		$errormsg .= "<h4 style='color: #ff0000'>";
		$errormsg .= _MD_ERROREXIST."</h4>";
		$error = 1;
    }
	// Check if Title exist
    if ( $title == "" ) {
		$errormsg .= "<h4 style='color: #ff0000'>";
		$errormsg .= _MD_ERRORTITLE."</h4>";
    	$error =1;
    }

	// Check if Description exist
    //if ( $description == "" ) {
	//	$errormsg .= "<h4 style='color: #ff0000'>";
	//	$errormsg .= _MD_ERRORDESC."</h4>";
    //	$error =1;
    //}
    if ( $error == 1 ) {
		xoops_cp_header();
		echo $errormsg;
		xoops_cp_footer();
		exit();
    }
    if ( !empty($_POST['cid']) ) {
		$cid = $_POST['cid'];
	} else {
		$cid = 0;
	}
	$newid = $xoopsDB->genId($xoopsDB->prefix("xdir_links")."_lid_seq");
	$sql = sprintf("INSERT INTO %s (lid, cid, title, address, address2, city, state, zip, country, phone, fax, email, url, logourl, submitter, status, date, hits, rating, votes, comments, premium) VALUES (%u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix("xdir_links"), $newid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $submitter, 1, time(), 0, 0, 0, 0, $premium);
	$xoopsDB->query($sql) or $eh->show("0013");
	if ( $newid == 0 ) {
		$newid = $xoopsDB->getInsertId();
	}
	$sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix("xdir_text"), $newid, $description);
	$xoopsDB->query($sql) or $eh->show("0013");
	$tags = array();
    $tags['LINK_NAME'] = $title;
    $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&lid=' . $newid;
    $sql = "SELECT title FROM " . $xoopsDB->prefix("xdir_cat") . " WHERE cid=" . $cid;
    $result = $xoopsDB->query($sql);
    $row = $xoopsDB->fetchArray($result);
    $tags['CATEGORY_NAME'] = $row['title'];
    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('global', 0, 'new_link', $tags);
	$notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
    redirect_header("index.php?op=linksConfigMenu",1,_MD_NEWLINKADDED);
}

function approve()
{
	global $xoopsConfig, $xoopsDB, $_POST, $myts, $eh;
	$lid = $_POST['lid'];
	$cid = $_POST['cid'];
	$title = $_POST['title'];
	$address = $_POST['address'];
	$address2 = $_POST['address2'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$country = $_POST['country'];
	$phone = $_POST['phone'];
	$fax = $_POST['fax'];
	$email = $_POST['email'];
	$premium = $_POST['premium'];
	if ( empty($cid) ) {
		$cid = 0;
	}
	$description = $_POST['description'];
	if (($_POST["url"]) || ($_POST["url"]!="")) {
		//		$url=$myts->formatURL($_POST["url"]);
		//		$url = urlencode($url);
		$url = $myts->makeTboxData4Save($_POST["url"]);
	}
	$logourl = $myts->makeTboxData4Save($_POST["logourl"]);
	$title = $myts->makeTboxData4Save($title);
	$address = $myts->makeTboxData4Save($address);
	$address2 = $myts->makeTboxData4Save($address2);
	$city = $myts->makeTboxData4Save($city);
	$state = $myts->makeTboxData4Save($state);
	$zip = $myts->makeTboxData4Save($zip);
	$country = $myts->makeTboxData4Save($country);
	$phone = $myts->makeTboxData4Save($phone);
	$fax = $myts->makeTboxData4Save($fax);
	$email = $myts->makeTboxData4Save($email);
	$premium = $myts->makeTboxData4Save($premium);
	$description = $myts->makeTareaData4Save($description);
	$query = "update ".$xoopsDB->prefix("xdir_links")." set cid='$cid', title='$title', address='$address', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', fax='$fax', email='$email', url='$url', logourl='$logourl', premium='$premium', status=1, date=".time()." where lid=".$lid."";
	$xoopsDB->query($query) or $eh->show("0013");
	$query = "update ".$xoopsDB->prefix("xdir_text")." set description='$description' where lid=".$lid."";
	$xoopsDB->query($query) or $eh->show("0013");
	global $xoopsModule;
	$tags=array();
    $tags['LINK_NAME'] = $title;
    $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&lid=' . $lid;
	$sql = "SELECT title FROM " . $xoopsDB->prefix("xdir_cat") . " WHERE cid=" . $cid;
    $result = $xoopsDB->query($sql);
    $row = $xoopsDB->fetchArray($result);
    $tags['CATEGORY_NAME'] = $row['title'];
    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('global', 0, 'new_link', $tags);
	$notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
	$notification_handler->triggerEvent('link', $lid, 'approve', $tags);
    redirect_header("index.php",1,_MD_NEWLINKADDED);
}
if(!isset($_POST['op'])) {
	$op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
	$op = $_POST['op'];
}
switch ($op) {
case "delNewLink":
	delNewLink();
	break;
case "approve":
	approve();
	break;
case "addCat":
	addCat();
	break;
case "addLink":
	addLink();
	break;
case "listBrokenLinks":
	listBrokenLinks();
	break;
case "delBrokenLinks":
	delBrokenLinks();
	break;
case "ignoreBrokenLinks":
	ignoreBrokenLinks();
	break;
case "listModReq":
	listModReq();
	break;
case "changeModReq":
	changeModReq();
	break;
case "ignoreModReq":
	ignoreModReq();
	break;
case "delCat":
	delCat();
	break;
case "modCat":
	modCat();
	break;
case "modCatS":
	modCatS();
	break;
case "modLink":
	modLink();
	break;
case "modLinkS":
	modLinkS();
	break;
case "delLink":
	delLink();
	break;
case "delVote":
	delVote();
	break;
case "linksConfigMenu":
	linksConfigMenu();
	break;
case "listNewLinks":
	listNewLinks();
	break;
case 'main':
default:
	xdir();
	break;
}
?>