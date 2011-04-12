<?php
// $Id: index.php,v 1.12 2003/03/27 12:11:06 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //
include "header.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
$xoopsOption['template_main'] = 'xdir_index.html';
include XOOPS_ROOT_PATH."/header.php";
$result=$xoopsDB->query("SELECT cid, title, imgurl FROM ".$xoopsDB->prefix("xdir_cat")." WHERE pid = 0 ORDER BY title") or exit("Error");

$count = 1;
while($myrow = $xoopsDB->fetchArray($result)) {
	$imgurl = '';
	if ($myrow['imgurl'] && $myrow['imgurl'] != "http://"){
		$imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
	}
	$totallink = getTotalItems($myrow['cid'], 1);
	// get child category objects
	$arr = array();
	$arr = $mytree->getFirstChild($myrow['cid'], "title");
	$space = 0;
	$chcount = 0;
	$subcategories = '';
	foreach($arr as $ele){
		$chtitle = $myts->htmlSpecialChars($ele['title']);
		if ($chcount > 5) {
			$subcategories .= "...";
			break;
		}
		if ($space>0) {
			$subcategories .= ", ";
		}
		$subcategories .= "<a href=\"".XOOPS_URL."/modules/xdirectory/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
		$space++;
		$chcount++;
	}
	$xoopsTpl->append('categories', array('image' => $imgurl, 'id' => $myrow['cid'], 'title' => $myts->htmlSpecialChars($myrow['title']), 'subcategories' => $subcategories, 'totallink' => $totallink, 'count' => $count));
	$count++;
}
list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status>0"));
$xoopsTpl->assign('lang_thereare', sprintf(_MD_THEREARE,$numrows));

if ($xoopsModuleConfig['useshots'] == 1) {
	$xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
	$xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
	$xoopsTpl->assign('show_screenshot', true);
	$xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
}

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}

$xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
$xoopsTpl->assign('lang_lastupdate', _MD_LASTUPDATEC);
$xoopsTpl->assign('lang_hits', _MD_HITSC);
$xoopsTpl->assign('lang_rating', _MD_RATINGC);
$xoopsTpl->assign('lang_ratethissite', _MD_RATETHISSITE);
$xoopsTpl->assign('lang_reportbroken', _MD_REPORTBROKEN);
$xoopsTpl->assign('lang_tellafriend', _MD_TELLAFRIEND);
$xoopsTpl->assign('lang_modify', _MD_MODIFY);
$xoopsTpl->assign('lang_latestlistings' , _MD_LATESTLIST);
$xoopsTpl->assign('lang_category' , _MD_CATEGORYC);
$xoopsTpl->assign('lang_visit' , _MD_VISIT);
$xoopsTpl->assign('lang_comments' , _COMMENTS);
$xoopsTpl->assign('lang_attention', _MD_ATTENTION);
$xoopsTpl->assign('lang_phone', _MD_BUSPHONE);
$xoopsTpl->assign('lang_fax', _MD_BUSFAX);
$xoopsTpl->assign('lang_email', _MD_BUSEMAIL);
$xoopsTpl->assign('lang_url', _MD_SITEURL);

$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.phone, l.fax, l.email, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description FROM ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t where l.status>0 and l.lid=t.lid ORDER BY date DESC", $xoopsModuleConfig['newlinks'], 0);
while(list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $premium, $description) = $xoopsDB->fetchRow($result)) {
	if ($isadmin) {
		$adminlink = '<a href="'.XOOPS_URL.'/modules/xdirectory/admin/?op=modLink&lid='.$lid.'"><img src="'.XOOPS_URL.'/modules/xdirectory/images/editicon.gif" border="0" alt="'._MD_EDITTHISLINK.'" /></a>';
	} else {
		$adminlink = '';
	}
	if ($votes == 1) {
		$votestring = _MD_ONEVOTE;
	} else {
		$votestring = sprintf(_MD_NUMVOTES,$votes);
	}
	$path = $mytree->getPathFromId($cid, "title");
	$path = substr($path, 1);
	$path = str_replace("/"," - ",$path);
	$new = newlinkgraphic($time, $status);
	$pop = popgraphic($hits);
	$xoopsTpl->append('links', array('id' => $lid, 'cid' => $cid, 'url' => $url, 'rating' => number_format($rating, 2), 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'address' => $myts->htmlSpecialChars($address), 'address2' => $myts->htmlSpecialChars($address2), 'city' => $myts->htmlSpecialChars($city), 'state' => $myts->htmlSpecialChars($state), 'zip' => $myts->htmlSpecialChars($zip), 'country' => $myts->htmlSpecialChars($country), 'phone' => $myts->htmlSpecialChars($phone), 'fax' => $myts->htmlSpecialChars($fax), 'email' => $myts->htmlSpecialChars($email), 'category' => $path, 'logourl' => $myts->htmlSpecialChars($logourl), 'updated' => formatTimestamp($time,"m"), 'description' => $myts->makeTareaData4Show($description,0), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring, 'comments' => $comments, 'premium' => $premium, 'mail_subject' => rawurlencode(sprintf(_MD_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/xdirectory/singlelink.php?cid='.$cid.'&lid='.$lid)));
}
include XOOPS_ROOT_PATH.'/footer.php';
?>
