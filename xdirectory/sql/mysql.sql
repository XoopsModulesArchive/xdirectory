
#
# Table structure for table `xdir_broken`
#

CREATE TABLE `xdir_broken` (
  `reportid` int(5) NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `sender` int(11) unsigned NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`reportid`),
  KEY `lid` (`lid`),
  KEY `sender` (`sender`),
  KEY `ip` (`ip`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

# --------------------------------------------------------

#
# Table structure for table `xdir_cat`
#

CREATE TABLE `xdir_cat` (
  `cid` int(5) unsigned NOT NULL auto_increment,
  `pid` int(5) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `imgurl` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`cid`),
  KEY `pid` (`pid`)
) TYPE=MyISAM AUTO_INCREMENT=154 ;

# --------------------------------------------------------

#
# Table structure for table `xdir_links`
#

CREATE TABLE `xdir_links` (
  `lid` int(11) unsigned NOT NULL auto_increment,
  `cid` int(5) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `address` varchar(200) NOT NULL default '',
  `address2` varchar(100) NOT NULL default '',
  `city` varchar(80) NOT NULL default '',
  `state` char(2) NOT NULL default '',
  `zip` varchar(15) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `phone` varchar(35) NOT NULL default '(916)',
  `fax` varchar(35) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `url` varchar(250) NOT NULL default '',
  `logourl` varchar(60) NOT NULL default '',
  `submitter` int(11) unsigned NOT NULL default '0',
  `status` tinyint(2) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  `rating` double(6,4) NOT NULL default '0.0000',
  `votes` int(11) unsigned NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  `premium` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`lid`),
  KEY `cid` (`cid`),
  KEY `status` (`status`),
  KEY `title` (`title`(40))
) TYPE=MyISAM AUTO_INCREMENT=1336 ;

# --------------------------------------------------------

#
# Table structure for table `xdir_mod`
#

CREATE TABLE `xdir_mod` (
  `requestid` int(11) unsigned NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `cid` int(5) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `address2` varchar(100) NOT NULL default '',
  `city` varchar(40) NOT NULL default '',
  `state` char(2) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `phone` varchar(35) NOT NULL default '',
  `fax` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `url` varchar(250) NOT NULL default '',
  `logourl` varchar(150) NOT NULL default '',
  `premium` char(2) NOT NULL default '0',	
  `description` text NOT NULL,
  `modifysubmitter` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`requestid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


# --------------------------------------------------------

#
# Table structure for table `xdir_text`
#

CREATE TABLE `xdir_text` (
  `lid` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  KEY `lid` (`lid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Table structure for table `xdir_votedata`
#

CREATE TABLE `xdir_votedata` (
  `ratingid` int(11) unsigned NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `ratinguser` int(11) unsigned NOT NULL default '0',
  `rating` tinyint(3) unsigned NOT NULL default '0',
  `ratinghostname` varchar(60) NOT NULL default '',
  `ratingtimestamp` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ratingid`),
  KEY `ratinguser` (`ratinguser`),
  KEY `ratinghostname` (`ratinghostname`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
    