# Flexible Footer Menu Multilingual v1.6beta: 
July 2023: WORK IN PROGRESS  
Status: working with ZC158 and php82.  
Report bugs in the GitHub Repository.
Installation
------------

1.  Backup both your Zen Cart installation and database — this plugin will modify both.
2.  Double-check the backup you just created. Better safe than sorry!
3.  Unzip the plugin's distribution zip-file into a temporary directory. Rename the "YOUR\_TEMPLATE" directory to match your custom template's name and the "YOUR\_ADMIN" directory to match your renamed admin folder.
4.  Your best approach for installing any new software is to use a local copy for your testing before deploying the changes to your live store. If you must install this directly on your live store, put your store into Maintenance Mode using your admin's Configuration->Maintenance Mode->Down for Maintenance setting, first. You'll then either copy the files to your local installation or use your FTP/SFTP program to copy the files to your hosted store.
5.  Check to see if your installation already has the following template-override file. If the file is not present, copy the file from the template\_default directory; otherwise, use file-merging software to merge this plugin's changes into the file prior to copying:
    1.  /includes/templates/YOUR\_TEMPLATE/common/tpl\_footer.php
6.  Sign into your Zen Cart admin.
7.  Copy the admin-level files to your store:
    1.  /YOUR\_ADMIN/ffmm\_install.php
    2.  /YOUR\_ADMIN/flexible\_footer\_menu.php
    3.  /YOUR\_ADMIN/includes/extra\_datafiles/flexible\_footer\_menu\_database\_names.php
    4.  /YOUR\_ADMIN/includes/extra\_datafiles/flexible\_footer\_menu\_filenames.php
    5.  /YOUR\_ADMIN/includes/functions/extra\_functions/footer\_menu\_install\_functions.php
    6.  /YOUR\_ADMIN/includes/functions/extra\_functions/footer\_menu\_functions.php
    7.  /YOUR\_ADMIN/includes/languages/english/flexible\_footer\_menu.php
    8.  /YOUR\_ADMIN/includes/languages/english/extra\_definitions/flexible\_footer\_menu.php
8.  Copy the store-side files, now that the database table has been created:
    1.  /images/footer\_images/facebook.png
    2.  /images/footer\_images/Home.png
    3.  /images/footer\_images/information.png
    4.  /images/footer\_images/instagram.png
    5.  /images/footer\_images/twitter.png
    6.  /includes/extra\_datafiles/flexible\_footer\_menu.php
    7.  /includes/functions/extra\_functions/flexible\_footer\_menu\_functions.php
    8.  /includes/modules/YOUR\_TEMPLATE/flexible\_footer\_menu.php
    9.  /includes/templates/YOUR\_TEMPLATE/common/tpl\_footer.php
    10.  /includes/templates/YOUR\_TEMPLATE/css/stylesheet\_flexible\_footer\_menu.css
    11.  /includes/templates/YOUR\_TEMPLATE/templates/tpl\_flexible\_footer\_menu.php
9.  Click the Home Link
10.  Go to Tools>Flexible Footer Menu Install
11.  Follow the Install / Upgrade or Uninstall Instructions

Changelog
---------

Version 1.6 03-07-2023 torvista: reviewed and modified for use with ZC 1.5.8 and php 8.2 Version 1.3 04-12-2017 Design75

*   Removed file "YOUR\_ADMIN/includes/init\_includes/overrides/init\_sanitize.php", and replaced by "YOUR\_ADMIN/includes/extra\_datafiles/flexible\_footer\_sanitizer.php".  
    This eliminate the need of editing core files.  
    I also added additional fields, there are four in total:
    *   col\_header -> The column header
    *   page\_title -> The title
    *   col\_html\_text -> The html text
    *   page\_url -> The url
*   Restored the "YOUR\_TEMPLATE" folder names, after they ere renamed in the previous version to the writers own template name

Version 1.2 06-24-2015 Nick1973

*   added and changed includes/init\_includes/overrides/init\_sanitize.php -  
      
    Around line 214/216 depending on your editor:  
      
    `$group = array('products_description', 'coupon_desc', 'file_contents', 'categories_description', 'message_html', 'banners_html_text', 'pages_html_text', 'comments', 'products_options_comment');`  
      
    Changed to:  
      
    `$group = array('products_description', 'coupon_desc', 'file_contents', 'categories_description', 'message_html', 'banners_html_text', 'pages_html_text', 'comments', 'products_options_comment', 'col_html_text');`  
      
    'col\_html\_text' added. This ensures rendering of HTML correctly if using CK Editor or Tiny MCE.  
      
    As described by rbarbour here https://www.zen-cart.com/showthread.php?209715-Flexible-Footer-Columns-Menu-for-1-5-x/page7 on the 4th post.  
    

Version 1.1 06-24-2015 Design75

*   updated includes\\modules\\YOUR\_TEMPLATE\\flexible\_footer\_menu.php
*   updated YOUR\_ADMIN\\includes\\languages\\english\\extra\_definitions\\flexible\_footer\_menu.php
*   added YOUR\_ADMIN\\includes\\languages\\english\\flexible\_footer\_menu.php