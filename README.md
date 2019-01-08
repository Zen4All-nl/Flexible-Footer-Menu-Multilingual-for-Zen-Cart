# Flexible Footer Menu Multilingual
<h2>Version Info:</h2>
<h2>Flexible Footer Menu Multilingual v1.5</h2>
<hr />
<h2>Installation</h2>
      <ol style="list-style-type: upper-latin">
        <li>Backup both your Zen Cart installation and database — this plugin will will modify both.</li>
        <li>Double-check the backup you just created. Better safe than sorry!</li>
        <li>Unzip the plugin's distribution zip-file into a temporary directory. Rename the "YOUR_TEMPLATE" directory to match your custom template's name and the "YOUR_ADMIN" directory to match your renamed admin folder.</li>
        <li>Your best approach for installing any new software is to use a local copy for your testing before deploying the changes to your live store. If you must install this directly on your live store, put your store into Maintenance Mode using your admin's Configuration->Maintenance Mode->Down for Maintenance setting, first. You'll then either copy the files to your local installation or use your FTP/SFTP program to copy the files to your hosted store.</li>
        <li>Check to see if your installation already has the following template-override file. If the file is not present, copy the file from the template_default directory; otherwise, use file-merging software to merge this plugin's changes into the file prior to copying:
          <ol>
            <li>/includes/templates/YOUR_TEMPLATE/common/tpl_footer.php</li>
          </ol>
        </li>
        <li>Sign into your Zen Cart admin.</li>
        <li>Copy the admin-level files to your store:
          <ol>
            <li>/YOUR_ADMIN/ffmm_install.php</li>
            <li>/YOUR_ADMIN/flexible_footer_menu.php</li>
            <li>/YOUR_ADMIN/includes/extra_datafiles/flexible_footer_menu_database_names.php</li>
            <li>/YOUR_ADMIN/includes/extra_datafiles/flexible_footer_menu_filenames.php</li>
            <li>/YOUR_ADMIN/includes/functions/extra_functions/footer_menu_install_functions.php</li>
            <li>/YOUR_ADMIN/includes/functions/extra_functions/footer_menu_functions.php</li>
            <li>/YOUR_ADMIN/includes/languages/english/flexible_footer_menu.php</li>
            <li>/YOUR_ADMIN/includes/languages/english/extra_definitions/flexible_footer_menu.php</li>
          </ol>
        </li>
        <li>Copy the store-side files, now that the database table has been created:
          <ol>
            <li>/images/footer_images/facebook.png</li>
            <li>/images/footer_images/Home.png</li>
            <li>/images/footer_images/information.png</li>
            <li>/images/footer_images/instagram.png</li>
            <li>/images/footer_images/twitter.png</li>
            <li>/includes/extra_datafiles/flexible_footer_menu.php</li>
            <li>/includes/functions/extra_functions/flexible_footer_menu_functions.php</li>
            <li>/includes/modules/YOUR_TEMPLATE/flexible_footer_menu.php</li>
            <li>/includes/templates/YOUR_TEMPLATE/common/tpl_footer.php</li>
            <li>/includes/templates/YOUR_TEMPLATE/css/stylesheet_flexible_footer_menu.css</li>
            <li>/includes/templates/YOUR_TEMPLATE/templates/tpl_flexible_footer_menu.php</li></ol></li>
        <li>Click the Home Link</li>
        <li>Go to Tools>Flexible Footer Menu Install</li>
        <li>Follow the Install / Upgrade or Uninstall Instructions</li>
      </ol>

<h2>Changelog</h2>
Version 1.3 04-12-2017 Design75
      <ul>
        <li>Removed file "YOUR_ADMIN/includes/init_includes/overrides/init_sanitize.php", and replaced by "YOUR_ADMIN/includes/extra_datafiles/flexible_footer_sanitizer.php".<br>This eliminate the need of editing core files.<br> I also added additional fields, there are four in total:
          <ul>
            <li>col_header -> The column header</li>
            <li>page_title -> The title</li>
            <li>col_html_text -> The html text</li>
            <li>page_url -> The url</li>
          </ul>
        </li>
        <li>Restored the "YOUR_TEMPLATE" folder names, after they ere renamed in the previous version to the writers own template name</li>
      </ul>
      <p>Version 1.2 06-24-2015 Nick1973</p>
      <ul>
        <li>added and changed includes/init_includes/overrides/init_sanitize.php -<br />
          <br />
          Around line 214/216 depending on your editor:
          <br />
          <br />
          <code>$group = array('products_description', 'coupon_desc', 'file_contents', 'categories_description', 'message_html', 'banners_html_text', 'pages_html_text', 'comments', 'products_options_comment');</code><br />
          <br />
          Changed to:
          <br />
          <br />
          <code>$group = array('products_description', 'coupon_desc', 'file_contents', 'categories_description', 'message_html', 'banners_html_text', 'pages_html_text', 'comments', 'products_options_comment', 'col_html_text');</code><br />
          <br />
          'col_html_text' added. This ensures rendering of HTML correctly if using CK Editor or Tiny MCE.<br />
          <br />
          As described by rbarbour here https://www.zen-cart.com/showthread.php?209715-Flexible-Footer-Columns-Menu-for-1-5-x/page7
          on the 4th post.<br />
        </li>
      </ul>
Version 1.1 06-24-2015 Design75
      <ul>
        <li>updated includes\modules\YOUR_TEMPLATE\flexible_footer_menu.php</li>
        <li>updated YOUR_ADMIN\includes\languages\english\extra_definitions\flexible_footer_menu.php</li>
        <li>added YOUR_ADMIN\includes\languages\english\flexible_footer_menu.php</li>
      </ul>
    </div>
