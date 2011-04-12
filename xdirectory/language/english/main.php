<?php
// $Id: main.php,v 1.7 2003/03/27 12:11:08 w4z004 Exp $
//%%%%%%		Module Name 'xDirectory'		%%%%%

define("_MD_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");
define("_MD_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");
define("_MD_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");

define("_MD_SEARCHFOR","Search for");
define("_MD_ANY","ANY");
define("_MD_SEARCH","Search");

define("_MD_MAIN","Main");
define("_MD_SUBMITLINK","Submit Listing");
define("_MD_SUBMITLINKHEAD","Submit New Business Listing");
define("_MD_POPULAR","Popular");
define("_MD_TOPRATED","Top Rated");

define("_MD_NEWTHISWEEK","New this week");
define("_MD_UPTHISWEEK","Updated this week");
define("_MD_ATTENTION","<strong>Attention Business Owners!</strong> Getting your business listed is easy and FREE! <br />Simply <a href=\"".XOOPS_URL."/register.php\"><u>register with {X_SITENAME}</u></a> Once registration is completed <br />just logon and <a href=\"".XOOPS_URL."/modules/xdirectory/submit.php\"><u>submit your business</u></a>.");

define("_MD_POPULARITYLTOM","Popularity (Least to Most Hits)");
define("_MD_POPULARITYMTOL","Popularity (Most to Least Hits)");
define("_MD_TITLEATOZ","Business Name (A to Z)");
define("_MD_TITLEZTOA","Business Name (Z to A)");
define("_MD_DATEOLD","Date (Old Listings First)");
define("_MD_DATENEW","Date (New Listings First)");
define("_MD_RATINGLTOH","Rating (Lowest Score to Highest Score)");
define("_MD_RATINGHTOL","Rating (Highest Score to Lowest Score)");

define("_MD_NOSHOTS","No Screenshots Available");
define("_MD_EDITTHISLINK","Edit This Listing");

define("_MD_DESCRIPTIONC","Description: ");
define("_MD_DESCRIPTHELP","Please use this area to enter your hours of operation along with a brief description of your business");
define("_MD_EMAILC","Email: ");
define("_MD_CATEGORYC","Category: ");
define("_MD_LASTUPDATEC","Last Update: ");
define("_MD_HITSC","Hits: ");
define("_MD_RATINGC","Rating: ");
define("_MD_ONEVOTE","1 vote");
define("_MD_NUMVOTES","%s votes");
define("_MD_RATETHISSITE","Rate this Business");
define("_MD_MODIFY","Modify this Listing");
define("_MD_REPORTBROKEN","Report Incorrect Info");
define("_MD_TELLAFRIEND","Tell a Friend");

define("_MD_THEREARE","There are <b>%s</b> businesses in our Database");
define("_MD_LATESTLIST","Latest Business Listings");

define("_MD_REQUESTMOD","Request Business Listing Modification");
define("_MD_LINKID","Listing ID: ");
define("_MD_SITETITLE","Business Name: ");
define("_MD_BUSADDRESS","Address: ");
define("_MD_BUSADDRESS2","Address2: ");
define("_MD_BUSCITY","City: ");
define("_MD_BUSSTATE","State: ");
define("_MD_BUSZIP","Zip: ");
define("_MD_BUSCOUNTRY","Country: ");
define("_MD_BUSPHONE","Phone: ");
define("_MD_BUSFAX","Fax: ");
define("_MD_BUSEMAIL","e-Mail: ");
define("_MD_SITEURL","Website: ");
define("_MD_OPTIONS","Options: ");
define("_MD_PREMIUM","Premium Listing: ");
define("_MD_NOTIFYAPPROVE", "Notify me when this Listing is approved");
define("_MD_SHOTIMAGE","Screenshot Img: ");
define("_MD_SENDREQUEST","Send Request");

define("_MD_VOTEAPPRE","Your vote is appreciated.");
define("_MD_THANKURATE","Thank you for taking the time to rate a site here at %s.");
define("_MD_VOTEFROMYOU","Input from users such as yourself will help other visitors better decide which businesses to choose.");
define("_MD_VOTEONCE","Please do not vote for the same resource more than once.");
define("_MD_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_MD_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_MD_DONOTVOTE","Do not vote for your own resource.");
define("_MD_RATEIT","Rate It!");

define("_MD_INTRESTLINK","Interesting Business Listing at %s");  // %s is your site name
define("_MD_INTLINKFOUND","Here is an interesting Business I have found at %s");  // %s is your site name
define("_CLA_MESSEND","Your message has been sent");  // %s is your site name

define("_MD_RECEIVED","We've received your business information. Thank you!");
define("_MD_WHENAPPROVED","You'll receive an e-mail when your business listing has been approved.");
define("_MD_SUBMITONCE","Submit your business listing only once.");
define("_MD_ALLPENDING","All business listing information is posted pending verification.");
define("_MD_DONTABUSE","Username and IP are recorded.");
define("_MD_TAKESHOT","It may take up to several days for your business listing to be added to our live database.");

define("_MD_RANK","Rank");
define("_MD_CATEGORY","Category");
define("_MD_HITS","Hits");
define("_MD_RATING","Rating");
define("_MD_VOTE","Vote");
define("_MD_TOP10","%s Top 10"); // %s is a link category title

define("_MD_SEARCHRESULTS","Search results for <b>%s</b>:"); // %s is search keywords
define("_MD_SORTBY","Sort by:");
define("_MD_TITLE","Business Name");
define("_MD_DATE","Date");
define("_MD_POPULARITY","Popularity");
define("_MD_CURSORTEDBY","Businesses currently sorted by: %s");
define("_MD_PREVIOUS","Previous");
define("_MD_NEXT","Next");
define("_MD_NOMATCH","No matches found to your query");

define("_MD_SUBMIT","Submit");
define("_MD_CANCEL","Cancel");

define("_MD_ALREADYREPORTED","You have already submitted a broken report for this resource.");
define("_MD_MUSTREGFIRST","Sorry, you don't have the permission to perform this action.<br />Please register or login first!");
define("_MD_NORATING","No rating selected.");
define("_MD_CANTVOTEOWN","You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_MD_VOTEONCE2","Vote for the selected resource only once.<br />All votes are logged and reviewed.");

//%%%%%%	Module Name 'xDirectory' (Admin)	  %%%%%

define("_MD_WEBLINKSCONF","Directory Listing Configuration");
define("_MD_GENERALSET","xDirectory General Settings");
define("_MD_ADDMODDELETE","Add, Modify, and Delete Categories/Listings");
define("_MD_LINKSWAITING","Listings Waiting for Validation");
define("_MD_BROKENREPORTS","Broken Link Reports");
define("_MD_MODREQUESTS","Listing Modification Requests");
define("_MD_SUBMITTER","Submitter: ");
define("_MD_VISIT","Visit");
define("_MD_SHOTMUST","Screenshot image must be a valid image file under %s directory (ex. shot.gif). Leave it blank if no image file.");
define("_MD_APPROVE","Approve");
define("_MD_DELETE","Delete");
define("_MD_NOSUBMITTED","No New Submitted Listings.");
define("_MD_ADDMAIN","Add a MAIN Category");
define("_MD_TITLEC","Category Name: ");
define("_MD_IMGURL","Image URL (OPTIONAL Image height will be resized to 50): ");
define("_MD_ADD","Add");
define("_MD_ADDSUB","Add a SUB-Category");
define("_MD_IN","in");
define("_MD_ADDNEWLINK","Add a New Business Listing");
define("_MD_MODCAT","Modify Category");
define("_MD_MODLINK","Modify Listing");
define("_MD_TOTALVOTES","Business Listing Votes (total votes: %s)");
define("_MD_USERTOTALVOTES","Registered User Votes (total votes: %s)");
define("_MD_ANONTOTALVOTES","Anonymous User Votes (total votes: %s)");
define("_MD_USER","User");
define("_MD_IP","IP Address");
define("_MD_USERAVG","User AVG Rating");
define("_MD_TOTALRATE","Total Ratings");
define("_MD_NOREGVOTES","No Registered User Votes");
define("_MD_NOUNREGVOTES","No Unregistered User Votes");
define("_MD_VOTEDELETED","Vote data deleted.");
define("_MD_NOBROKEN","No reported broken links.");
define("_MD_IGNOREDESC","Ignore (Ignores the report and only deletes the <b>broken link report</b>)");
define("_MD_DELETEDESC","Delete (Deletes <b>the reported business data</b> and <b>broken link reports</b> for the link.)");
define("_MD_REPORTER","Report Sender");
define("_MD_LINKSUBMITTER","Listing Submitter");
define("_MD_IGNORE","Ignore");
define("_MD_LINKDELETED","Listing Deleted.");
define("_MD_BROKENDELETED","Broken link report deleted.");
define("_MD_USERMODREQ","User Listing Modification Requests");
define("_MD_ORIGINAL","Original");
define("_MD_PROPOSED","Proposed");
define("_MD_OWNER","Owner: ");
define("_MD_NOMODREQ","No Listing Modification Request.");
define("_MD_DBUPDATED","Database Updated Successfully!");
define("_MD_MODREQDELETED","Modification Request Deleted.");
define("_MD_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
define("_MD_PARENT","Parent Category:");
define("_MD_SAVE","Save Changes");
define("_MD_CATDELETED","Category Deleted.");
define("_MD_WARNING","WARNING: Are you sure you want to delete this Category and ALL of its Listings and Comments?");
define("_MD_YES","Yes");
define("_MD_NO","No");
define("_MD_NEWCATADDED","New Category Added Successfully!");
define("_MD_ERROREXIST","ERROR: The Business you provided is already in the database!");
define("_MD_ERRORTITLE","ERROR: You need to enter Business Name!");
define("_MD_ERRORDESC","ERROR: You need to enter DESCRIPTION!");
define("_MD_NEWLINKADDED","New Listing added to the Database.");
define("_MD_YOURLINK","Your Website Link at %s");
define("_MD_YOUCANBROWSE","You can browse our directory listing at %s");
define("_MD_HELLO","Hello %s");
define("_MD_WEAPPROVED","We've approved your business listing submission to our business directory database.");
define("_MD_THANKSSUBMIT","Thank you for your submission!");
define("_MD_ISAPPROVED","We approved your business listing submission");
?>
