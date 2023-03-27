=========================================================
#  PHP Navigator 4.12.12
#  Modified: 5:59 PM; December 03, 2008
#            Paul Wratt,
#            Homeless, Melbourne, Austarlia.
#  Created:  5:00 PM; July 25, 2007
#  http://www.isource.net.nz/download.php?phpnav412.zip
#   PHP Navigator Plugins » http://phpnav.isource.net.nz/
#    FileWalker Suite » http://filewalker.isource.net.nz/
#     Original » http://navphp.sourceforge.net/
=========================================================

LATEST: v4.12 (rev 12) now comes in two flavours, FULL and LITE. This reflects the fact that 
        there are now four 'explorers', three editors (four including the work done on 'Atari'), 
        and five 'skin sets', totaling almost 2Mb, making it considerably larger than the original 
        83Kb, and even thats when they are compressed. Part of the reason I started working with
        phpNavigator was because of its small size. Some of the features of the new explorer
        'skins' may be added into the mainstream v4.32, and I will endeavor to at least get 
        the main functions patched to allow 'single page' inserts from this v4.12.

PREVIOUS: v4.12 (rev 8) will now continue life as the prototype bed for v5 development which 
        builds on the v4.2 vision of multi-user and includes advanced skinning and
        supports modules and virtual drives amongst other things. It is also updated to
        be more Linux friendly. There is currently no longer a v4.5 in development.
        v4.15 will become available for historical reasons, once v5 is complete.

UPDATE: This will be the last version of 4.12 (rev 7), with all additions added to 
        PHP Navigator 4.2, a code re-write is required to maintain both a single user
        and multi-user version, within the same codebase, and to allow easier development 
        of further code branches (PHP Media Navigator), keep and eye out for v4.15..
        (v4.2 will also move to v4.5 in the same re-write, then to v5.0 new skinsets)

UPDATE: PHP Navigator 4.2 has been release, and although it includes some of the
        changes I have made, and now includes a multi-user option, I will continue
        to maintain this official v4.1 (previously unofficial 4.2 beta). Also anyone 
        woundering, I wrote a file browser like this in ASP in 1998, and was looking 
        to pick up some PHP skills. Check out my PHP Navigator "tribute" site to 
        see how far I have "gone" from the original 3.21 source code, and my
        FileWalker site for where I am going..

 » User friendly source editors.
 » multiple interface types, including the retro 'Atari'.
 » multiple icon & skin sets supported.
 » single user only.
 » this version a v5 proto-type and hence is unsupported

EDITORS: are "\" fixed. The new proto-type editor 'DevEdit' is a 'code insertion' editor 
      and a work in progress (unsupported). Its intention is to be an Intergrated Development 
      Environment or IDE, with variable 'plain text' fonts, a color coded editor supporting 
      multiple languages, a WYSIWYG editor for point an click design, but mainly a flexible 
      point and click _code_ editor with libraries, eventually supporting at least 9 languages 
      and multiple language API and code libraries. Contributor are welcome, have a look at 
      the source, its pretty straight forward at the moment, 'libraries.???' will eventually use 
      separate includes, and also support non-english interface text.

NOTE: FileWalker is a heavily modified version of PHP Navigator with the read only
      version being code safe against hacking by PHP Navigator users, as well as 
      providing hidden file and folder parsing, although apart from that it is 
      completely PHP Navigator functional, which makes it perfect for either 
      safe public demos of PHP Navigator, or for web hosting services that DO NOT 
      allow file managers.

      It is however designed to sit around (inside the same folder as) PHP Navigator, 
      as are the other versions of FileWalker. PHP Navigator 4.12 is modified with 
      this in mind and will throw you to roFileWalker if you fail a login (if it is 
      installed). It will also show the following modules if they are installed:
        roFileWalker
        Administrator
        Control Panel
        Database
        Archiver
        Imageview
        Mediaman
        & Console

      These, along with SKINS and a few "other" features, is what FileWalker is 
      all about. But I feel it is also necessary to cater for PHP Navigator, since 
      I started playing with 3.21 by adding the dynamic source editor. I hope to 
      see roFileWalker (or a PHP Navigator version) available on the PHP Navigator 
      website, as I know it will be at least as in demand as PHP Navigator 4.12 is.

      PHP Navigator 4.12 will be the only version maintained by me, however I hope to 
      supply Cyril and Timothy with updates that will reflect the quality changes that 
      come about in FileWalker development, especially skins, left panel modules, 
      control panel modules, installer, as well as other plug-in modules along with my 
      other hard core non-Ajax coding (to make it function the same as the Ajax version)
      as I think it is better to compliment than to replace, which is what tends to happen.

      Thanks to Cyril for the chance to do this..

1ST UPDATE: I (Paul Wratt) am now current (Christmas 2007) lead developer for PHP Navigator.
      After releasing a v4.2 update with fixes and "simple skinning", v4.5 was dropped in
      favour of a v5 complete re-write. I presumed that would be the end of v4.12, however
      due to the nature of the changes and the constant need to prototype functionality as
      well as maintain a wide as possible Web Browser compatibility base, v4.12 continues
      to be the only main "fully working" and non-beta version of PHP Navigator available.
      Once v5 is complete, it will trasition to v4.15 to maintain a historical version,
      which can also be compared on a functional as well as code level with future v5
      builds of PHP Navigator. It is also more usable and compatible than any other
      version available, including the Japanese version which contains FTP access.

==================================
#  PHP Navigator 4.0
#  Author: Cyril Sebastian,
#          Kerala, India.
#  Lead QA: Timothy Alexander
#           Indiana, USA.
#  9:38 PM; May 07, 2007
#  http://navphp.sourceforge.net
==================================

 Web based open source file management system in PHP.
 With Windows XP style & icons. You will feel you are on Windows!

*-* Warning: Provided as is! ABSOLUTELY NO WARRANTY.*-*
*-* The use of this software is at the risk of the user.*-*

For the ORIGINAL version, log on to
'www.sourceforge.net/projects/navphp' or 'navphp.sourceforge.net'

Features
--------
 » User friendly Explorer style navigation and view. Easy to install and configure.
 » Advanced AJAX & DOM to avoid reloading of the full page.
 » gzip & deflate encoding gives you rocket speed data transfer!
 » It inherits the colour scheme of your OS. See the magic of css2!
 » Thumbnail view for wbmp,jpg,png,gif + many others.
 » Right click context menu (may need enabling).
 » Built in HTML editor
 » Keyboard shortcuts
 » Download folder as zip.
 » Extract zip archive to current folder.
 » And the BEST OF ALL, it's FREE!! 
 
Why PHP Navigator?
------------------
  Why yet another web file manager? Because the technology changed, browsers changed,
and file managers too! When the web flies on Web 2.0, AJAX and DOM, why use the same old 
sluggish file manager? Here are some features of old fm's: (i) Written without Javascript
for cross-browser support! (ii) Works on PHP 3.0!! (iii) No cookies!!!
Funny! Isn't it?

Known Issues
------------
 » HTML Editor is browser/OS dependent
 » Code Editor is browser/OS dependent
 » Right Click is browser/OS dependent
 » saved file can be 0 bytes in Chrome (on Linux) when file changes 
   charset type. only happens when copy & paste entire line. 
   Its a bug in Chrome, use history arrow to get edited file, Copy all 
   text, Exit, open the file again Paste, Save.

Requirements
------------
PHP 4.3, gd2 library for thumbnail view.
The web browser should support AJAX for the best functioning. (IE6, Mozilla Firefox etc)
It works best with Firefox 2.x. MSIE does not support some DOM features. It should work
on all other browsers that support standard DOM, else works in normal mode. (No AJAX)

Installing
----------

If you want to install in a remote server, you can use the 'navphp-zip-installer' to
save time & energy. Installer is available from the project website.

---[OR]----

1.Unzip and upload the files into a folder in your site.

2.Edit the 'config.php' to customize to your needs. Set the username and password in
  'config.php' and enable/disable login. (consider using httaccess for password protection,
  if security is an issue. Just look at the documentation for Apache on how to do that.)

3.Edit the 'config_patch.php' to enable 'View in Browser'. Set the FULL PATH to the ROOT 
  of your web server and the corresponding browser path (the one that will get you to the 
  server path). You may need to use PHP Navigator to determine the correct server path. 
  The examples given in 'config_patch.php' are for a local apache server on linux, set to 
  use 'user home' as well as a normal/standard 'wwwroot/htdocs' root.

4.Then access 'index.php' in your browser. If you directly access 'windows.php', proper
  browser detection may NOT OCCUR (choose "Configure/Default/Save" to reset).

Theme: The file named windows.css will be used for theming. Two theme files are provided,
XP & 98. XP is the default; so if you prefer to use 98, rename 'windows.css' and
'windows98.css' accordingly. See "SKINNING.TXT" for more information.

Skins: PHP Navigator v4.12 supports intermediate skinning, ie. "./skins/_name_/skin.css" and a folder
".skins/_name_/*.gif". If no CSS is found, the default one is used, if an icon or image is 
not found, again the default one is used. Choose a default skin in 'config.php', or change it 
through 'Configure PHP Navigator'. See "SKINNING.TXT" for more information.

How to use:
-----------
 Select File	:- 	
			You can select a file by clicking on the icon. [Or Use Arrow keys]
			Details of the selected file will be shown in the left pane.

 Open Folder	:- 	[Enter]
			Double Click a folder. 

 Edit File	:-	[Enter]
			Double click a file for editing. 

 Delete 	:-	[Shift+Ctrl+X]
			Select the file and click delete button.
			You will be prompted for a confirmation

 Rename 	:-	 [F2]
			Select the file/folder and click rename button.
			You will be prompted for the new name.

 Chmode 	:-	Change permissions of files. eg: Php scripts requires 755.

 Copy   	:-	[Shift+Ctrl+C]
			Click on a file icon and click the copy button. 
			You will be prompted for the destination folder.

 Search 	:- 	[Shift+Ctrl+F]
			Click on the 'Search' link on the left, follow the prompts.

 Thumbnail	:- 	[Shift+Ctrl+T]
			Select the file and click the thumbnail button or
			Enable in 'config.php' for all images. Available for jpg, gif, png
			and Windows bitmap.

 Download	:- 	Click on the file name below its icon.

 Extract	:-	[Shift+Ctrl+E]
			Select the zip file and click the extract button. 

 Edit HTML	:- 	[Shift+Ctrl+H]
			Select the HTML file and click the link on the left pane/menu. 

 Edit Code	:- 	[Shift+Ctrl+S]
			Select the source code file and click the link on the left pane/menu. 
			Color coded source code currently supports: html, javascript, php, 
			css, sql, java, text & generic.

 DevEdit	:- 	[Shift+Ctrl+I]
			Select any file and click the link on the pane/menu. 

Trouble-Shooting
----------------
 My picture Thumbnails won't work.
	   Load the gd2 library for PHP. It allows use of thumbnails.
           As of PHP v5, GD2 is installed by default

 How do I password protect PHP Navigator?
	   Read the note at the end of this file.
 
 HTML (WYSIWYG) Editor isn't working in Opera
	   Use IE or Firefox.
 
Permissions on Linux!
	   You need at least 660 permission on working folder for writing. If it was 
	   NOT created or uploaded via PHP, the file or folder may require 770 or 777. 
           You need to "chmod" the them, either manually or using ftp.
 
 My browser doesn't display anything or I just see some erroneous characters.
	   Edit config.php and set '$compress' to false.
           Better still, upgrade your web browser now supports:
              WIN: IE7, FF2; OSX: FF2, Safari3; LINUX: FF2, Opera 9.2, KHTML ?
	
To Do Next
----------
 Languages - create non-english language support
 Modules - archive, media (manipulation & management)
 Multiuser - extend from current v4.32 to support skins (other options)

To Do till v5
-------------
 Skins - integrate other FileWalker skins and modules
 Skin Objects - seperate frontend from backend (easy development)
 Console - "skin" & refactored "backend" functions (standalone)
 New Ajax - To upload files, to get file properties, in parallel.
 Use JSON - standards to support AJAX communication

License:
--------
 Copyright (C) 2006 Cyril Sebastian <cyril2net@yahoo.com>
 Distributed under GNU General Public License

 This script uses zip library by Eric Mueller <eric@themepark.com>, PhpConcept
 Library - Zip Module 2.1 by Vincent Blavet- http://www.phpconcept.net and OEdit
 javascript library by Peter Andreas Harteg - http://www.harteg.dk
 
 Feel free to inform me if you use PHP Navigator in your server.

Known Versions
--------------
Japanese version using ftp for updating
Dutch version 'upload only' for FTP management

Note:
-------
 A basic http authentication is provided with the script. The Login works ONLY IF it is
enabled in 'config.php'. If you already use a password protected folder, you can disable
the user login. For protecting folders the simplest way is basic http authentication
using '.htaccess' files. Refer your host provider for more details.
------------------------------------------------------------------------
Contact: phpnav@isource.net.nz, phpnav-skins@isource.net.nz, devedit@isource.net.nz
Mailing List: (to come)
Web: 'http://phpnav.isource.net.nz/', 'http://skins.phpnav.isource.net.nz'

