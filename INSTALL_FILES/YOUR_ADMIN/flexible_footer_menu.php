<?php
/**
 *
 * Flexible Footer Menu Multilingual
 *
 * @package admin
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @updated for Flexible Footer Menu v1.0 4/17/2013 ZCadditions.com (Raymond A. Barbour) $
 * @updated for Multilingual 2018-03-17 Zen4All (design75) zen4all.nl$
 */
require('includes/application_top.php');
$languages = zen_get_languages(); // modification for multi-language support
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (zen_not_null($action)) {
  switch ($action) {
    case 'setflag':
      if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {
        zen_set_flexible_footer_menu_status(zen_db_prepare_input($_GET['bID']), zen_db_prepare_input($_GET['flag']));
        $messageStack->add_session(SUCCESS_PAGE_STATUS_UPDATED, 'success');
      } else {
        $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
      }
      zen_redirect(zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']));
      break;
    case 'insert':
    case 'update':
// start modification for multi-language support
      if (isset($_POST['page_id'])) {
        $page_id = zen_db_prepare_input($_POST['page_id']);
      }
      $page_url = zen_db_prepare_input($_POST['page_url']);
      $col_id = zen_db_prepare_input($_POST['col_id']);
      $col_sort_order = zen_db_prepare_input($_POST['col_sort_order']);
      $page_date = (empty($_POST['date_added']) ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_db_prepare_input($_POST['date_added']));

      $page_error = false;
      if (empty($col_sort_order)) {
        $messageStack->add('Please add a Sort Order value', 'error');
        $page_error = true;
      }

      if ($page_error == false) {
        $language_id = (int)$_SESSION['languages_id'];
        $sql_data_array = array(
          'page_url' => $page_url,
          'col_id' => $col_id,
          'col_sort_order' => $col_sort_order,
        );

        if ($action == 'insert') {
          if (empty($_POST['date_added'])) {
            $page_date = 'now()';
          } else {
            $page_date = zen_date_raw($_POST['date_added']);
          }

          $insert_sql_data = array('status' => '0', 'date_added' => $page_date);
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          zen_db_perform(TABLE_FLEXIBLE_FOOTER_MENU, $sql_data_array);
          $page_id = zen_db_insert_id();
          $page_title_array = zen_db_prepare_input($_POST['page_title']);
          $col_header_array = zen_db_prepare_input($_POST['col_header']);
          $col_html_text_array = zen_db_prepare_input($_POST['col_html_text']);
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_array = array(
              'page_title' => $page_title_array[$language_id],
              'col_header' => $col_header_array[$language_id],
              'col_html_text' => $col_html_text_array[$language_id],
              'language_id' => $language_id,
              'page_id' => $page_id);

            zen_db_perform(TABLE_FLEXIBLE_FOOTER_MENU_CONTENT, $sql_data_array);
          }
          $messageStack->add(SUCCESS_PAGE_INSERTED, 'success');
        } elseif ($action == 'update') {
          $insert_sql_data = array('last_update' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          zen_db_perform(TABLE_FLEXIBLE_FOOTER_MENU, $sql_data_array, 'update', "page_id = '" . (int)$page_id . "'");
          $page_title_array = zen_db_prepare_input($_POST['page_title']);
          $col_header_array = zen_db_prepare_input($_POST['col_header']);
          $col_html_text_array = zen_db_prepare_input($_POST['col_html_text']);
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_array = array('page_title' => $page_title_array[$language_id],
              'col_header' => $col_header_array[$language_id],
              'col_html_text' => $col_html_text_array[$language_id]);

            zen_db_perform(TABLE_FLEXIBLE_FOOTER_MENU_CONTENT, $sql_data_array, 'update', "page_id = '" . (int)$page_id . "' and language_id = '" . $language_id . "'");
          }
          $messageStack->add(SUCCESS_PAGE_UPDATED, 'success');
        }
// end modification for multi-language support
        if ($col_image = new upload('col_image')) {
          $col_image->set_destination(DIR_FS_CATALOG_IMAGES . 'footer_images/');
          if ($col_image->parse() && $col_image->save()) {
            $col_image_name = 'footer_images/' . $col_image->filename;
          }
          if ($col_image->filename != 'none' && $col_image->filename != '') {
            $db->Execute("update " . TABLE_FLEXIBLE_FOOTER_MENU . "
                          set col_image = '" . $col_image_name . "'
                          where page_id = '" . (int)$page_id . "'");
          } else {

            if ($col_image->filename == 'none' || $_POST['image_delete'] == 1) {
//      if ($col_image->filename == 'none') {
              $db->Execute("update " . TABLE_FLEXIBLE_FOOTER_MENU . "
                            set col_image = ''
                            where page_id = '" . (int)$page_id . "'");
            }
          }
        }

        zen_redirect(zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'bID=' . $page_id));
      } else {
        $action = 'new';
      }
      break;
    case 'deleteconfirm':
      $page_id = $_POST['bID'];
      $db->Execute("delete from " . TABLE_FLEXIBLE_FOOTER_MENU . " where page_id = '" . (int)$page_id . "'");
      $db->Execute("delete from " . TABLE_FLEXIBLE_FOOTER_MENU_CONTENT . " where page_id = '" . (int)$page_id . "'");

      $messageStack->add(SUCCESS_PAGE_REMOVED, 'success');
      zen_redirect(zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page']));
      break;
  }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
    <script type="text/javascript" src="includes/menu.js"></script>
    <script type="text/javascript" src="includes/general.js"></script>
    <script type="text/javascript">
      <!--
      function init()
      {
        cssjsmenu('navbar');
        if (document.getElementById)
        {
          var kill = document.getElementById('hoverJS');
          kill.disabled = true;
        }
        if (typeof _editor_url == "string")
          HTMLArea.replaceAll();
      }
      // -->
    </script>
    <?php if ($editor_handler != '') include ($editor_handler); ?>
  </head>
  <body onLoad="init()">
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
    <!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <!-- body_text //-->
        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading">
                      <?php
                      echo HEADING_TITLE . '<a href="' . zen_href_link('ffmm_install.php') . '"> Installation/Maintenance</a>';
                      ?>
                    </td>
                    <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                    <td class="main">
                      <?php
                      if ($action != 'new') {
// toggle switch for editor
                        echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_FLEXIBLE_FOOTER_MENU, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') .
                        zen_hide_session_id() .
                        zen_draw_hidden_field('action', 'set_editor') .
                        '</form>';
                      }
                      ?>
                    </td>
                  </tr>
                </table></td>
            </tr>
            <?php
            if ($action == 'new') {
              $form_action = 'insert';

              $parameters = array(
                'language_id' => '',
                'page_title' => '',
                'page_url' => '',
                'col_header' => '',
                'col_image' => '',
                'col_html_text' => '',
                'col_id' => '',
                'col_sort_order' => '',
                'date_added' => '',
                'status' => '');

              $bInfo = new objectInfo($parameters);

              if (isset($_GET['bID'])) {
                $form_action = 'update';

                $bID = zen_db_prepare_input($_GET['bID']);

// query modified for multilanguage support
                $page_query = "select * from " . TABLE_FLEXIBLE_FOOTER_MENU . " where page_id = '" . (int)$_GET['bID'] . "'";
                $page = $db->Execute($page_query);
                $bInfo->objectInfo($page->fields);
              } elseif (zen_not_null($_POST)) {
                $bInfo->objectInfo($_POST);
              }
              ?>
              <tr>
                <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
                  <?php
                  echo zen_draw_form('new_page', FILENAME_FLEXIBLE_FOOTER_MENU, (isset($_GET['page']) ? 'page=' . zen_db_prepare_input($_GET['page']) . '&' : '') . 'action=' . $form_action, 'post', 'enctype="multipart/form-data"');
                  if ($form_action == 'update') {
                    echo zen_draw_hidden_field('page_id', $bID);
                  }
                  ?>

                  <table border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main">
                        <?php echo TEXT_COLUMN_HEADER; ?>
                      </td>
                      <td class="main">
                        <?php echo TEXT_COLUMN_HEADER_TIP; ?>
                        <?php
// modified code for multi-language support
                        $col_header = '';
                        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          if (isset($_GET['bID']) and zen_not_null($_GET['bID'])) {
                            $colHeaderQuery = "SELECT col_header
                                               FROM " . TABLE_FLEXIBLE_FOOTER_MENU_CONTENT . "
                                               WHERE page_id = '" . $_GET['bID'] . "'
                                               AND language_id = '" . $languages[$i]['id'] . "'";
                            $colHeader = $db->Execute($colHeaderQuery);
                            $col_header = $colHeader->fields['col_header'];
                          } else {
                            $col_header = '';
                          }
                          echo '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
                          echo zen_draw_input_field('col_header[' . $languages[$i]['id'] . ']', htmlspecialchars($col_header, ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_FLEXIBLE_FOOTER_MENU_CONTENT, 'col_header'), false);
                        }
// end modified code for multi-language support
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main">
                        <?php echo TEXT_PAGES_NAME; ?>
                      </td>
                      <td class="main">
                        <?php echo TEXT_PAGES_NAME_TIP; ?>
                        <?php
// modified code for multi-language support
                        $page_title = '';
                        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          if (isset($_GET['bID']) and zen_not_null($_GET['bID'])) {
                            $pageTitleQuery = "SELECT page_title
                                               FROM " . TABLE_FLEXIBLE_FOOTER_MENU_CONTENT . "
                                               WHERE page_id = '" . $_GET['bID'] . "'
                                               AND language_id = '" . $languages[$i]['id'] . "'";
                            $pageTitle = $db->Execute($pageTitleQuery);
                            $page_title = $pageTitle->fields['page_title'];
                          } else {
                            $page_title = '';
                          }
                          echo '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
                          echo zen_draw_input_field('page_title[' . $languages[$i]['id'] . ']', htmlspecialchars($page_title, ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_FLEXIBLE_FOOTER_MENU_CONTENT, 'page_title'), false);
                        }
// end modified code for multi-language support
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?>
                      </td>
                    </tr>

                    <?php if (($bInfo->col_image) != ('')) { ?>
                      <tr>
                        <td valign="top" class="main"><?php echo TEXT_HAS_IMAGE; ?></td>
                        <td class="main"><?php echo $bInfo->col_image; ?></td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_USE_IMAGE; ?></td>
                      <td class="main"><?php echo TEXT_USE_IMAGE_TIP; ?><br /><?php echo zen_draw_file_field('col_image'); ?></td>
                    </tr>

                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>

                    <tr>
                      <td class="main"><?php echo TEXT_DELETE_IMAGE; ?></td>
                      <td class="main"><?php echo zen_draw_radio_field('image_delete', '0', 'checked="checked"', $off_image_delete) . '&nbsp;' . TEXT_DELETE_IMAGE_NO . ' ' . zen_draw_radio_field('image_delete', '1', $on_image_delete) . '&nbsp;' . TEXT_DELETE_IMAGE_YES; ?></td>
                    </tr>

                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>

                    <?php /*
                      if ($form_action == 'insert') {
                      echo zen_draw_input_field('date_added', zen_date_short($bInfo->date_added), '', false);
                      }
                     */ ?>

                    <tr>
                      <td class="main"><?php echo TEXT_LINKAGE; ?></td>
                      <td class="main"><?php echo TEXT_LINKAGE_TIP; ?><br /><?php echo zen_draw_input_field('page_url', zen_not_null($bInfo->page_url) ? $bInfo->page_url : '', zen_set_field_length(TABLE_FLEXIBLE_FOOTER_MENU, page_url), false); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_ADD_TEXT; ?></td>
                      <td class="main">
                        <?php echo TEXT_ADD_TEXT_TIP; ?>
                        <?php
// modified code for multi-language support
                        $col_html_text = '';

                        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                          if (isset($_GET['bID']) && zen_not_null($_GET['bID'])) {
                            $colTextQuery = "SELECT col_html_text
                                             FROM " . TABLE_FLEXIBLE_FOOTER_MENU_CONTENT . "
                                             WHERE page_id = '" . $_GET['bID'] . "'
                                             AND language_id = '" . $languages[$i]['id'] . "'";
                            $colText = $db->Execute($colTextQuery);
                            $col_html_text = $colText->fields['col_html_text'];
                          } else {
                            $col_html_text = '';
                          }
                          echo '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
                          echo zen_draw_textarea_field('col_html_text[' . $languages[$i]['id'] . ']', 'soft', '100%', '40', htmlspecialchars($col_html_text, ENT_COMPAT, CHARSET, TRUE));
                        }
// end modified code for multi-language support
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '30'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_COLUMN; ?></td>
                      <td class="main"><?php echo TEXT_COLUMN_TIP; ?><br /><?php echo zen_draw_input_field('col_id', $bInfo->col_id, '', false); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo TEXT_COLUMN_SORT; ?></td>
                      <td class="main"><?php echo TEXT_COLUMN_SORT_TIP; ?><br /><?php echo zen_draw_input_field('col_sort_order', $bInfo->col_sort_order, '', false); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td colspan="2" class="main" align="left" valign="top" nowrap>
                        <?php echo (($form_action == 'insert') ? zen_image_submit('button_insert.gif', IMAGE_INSERT) : zen_image_submit('button_update.gif', IMAGE_UPDATE)) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['bID']) ? 'bID=' . $_GET['bID'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
                      </td>
                    </tr>
                  </table>
                  </form>
                </td>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr class="dataTableHeadingRow" width="100%">
                            <td class="dataTableHeadingContent"><?php echo FFM_TABLE_TITLE_HEADER; ?></td>
                            <td class="dataTableHeadingContent"><?php echo FFM_TABLE_TITLE_PAGE_NAME; ?></td>
                            <td class="dataTableHeadingContent"><?php echo FFM_TABLE_TITLE_IMAGE; ?></td>
                            <td class="dataTableHeadingContent"><?php echo TABLE_COLUMN_ID; ?></td>
                            <td class="dataTableHeadingContent"><?php echo TABLE_SORT_ORDER; ?></td>
                            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_STATUS; ?></td>
                            <td class="dataTableHeadingContent">&nbsp;</td>
                            <td class="dataTableHeadingContent">&nbsp;</td>
                          </tr>

                          <?php
// query modified for multi-language support
                          $flexfooter_query_raw = "SELECT f.page_id, f.language_id, f.col_image, f.status, f.date_added, f.last_update, f.col_sort_order, f.col_id,
                                                          ft.page_title, ft.col_header, ft.col_html_text
                                                   FROM " . TABLE_FLEXIBLE_FOOTER_MENU . " f, " . TABLE_FLEXIBLE_FOOTER_MENU_CONTENT . " ft
                                                   WHERE f.page_id = ft.page_id
                                                   AND ft.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                                   ORDER BY col_id ASC, col_sort_order";
// end of modification
// Split Page
// reset page when page is unknown
                          if (($_GET['page'] == '' or $_GET['page'] == '1') and $_GET['bID'] != '') {
                            $check_page = $db->Execute($flexfooter_query_raw);
                            $check_count = 1;
                            if ($check_page->RecordCount() > MAX_DISPLAY_SEARCH_RESULTS_EZPAGE) {
                              while (!$check_page->EOF) {
                                if ($check_page->fields['customers_id'] == $_GET['cID']) {
                                  break;
                                }
                                $check_count++;
                                $check_page->MoveNext();
                              }
                              $_GET['page'] = round((($check_count / MAX_DISPLAY_SEARCH_RESULTS_EZPAGE) + (fmod_round($check_count, MAX_DISPLAY_SEARCH_RESULTS_EZPAGE) != 0 ? .5 : 0)), 0);
                            } else {
                              $_GET['page'] = 1;
                            }
                          }

                          $pages_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_EZPAGE, $flexfooter_query_raw, $pages_query_numrows);
                          $flexfooter = $db->Execute($flexfooter_query_raw);

                          while (!$flexfooter->EOF) {
                            if ((!isset($_GET['bID']) || (isset($_GET['bID']) && ($_GET['bID'] == $flexfooter->fields['page_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
                              $bInfo_array = array_merge($flexfooter->fields);
                              $bInfo = new objectInfo($bInfo_array);
                            }
                            if (isset($bInfo) && is_object($bInfo) && ($flexfooter->fields['page_id'] == $bInfo->page_id)) {
                              echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $flexfooter->fields['page_id']) . '\'">' . "\n";
                            } else {
                              echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $flexfooter->fields['page_id']) . '\'">' . "\n";
                            }
                            ?>
                            <td class="dataTableContent"><?php echo $flexfooter->fields['col_header']; ?></td>
                            <td class="dataTableContent"><?php echo $flexfooter->fields['page_title']; ?></td>
                            <td class="dataTableContent"><?php echo zen_image((($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_SERVER . DIR_WS_CATALOG ) . DIR_WS_IMAGES . $flexfooter->fields['col_image']); ?></td>
                            <td class="dataTableContent"><?php echo $flexfooter->fields['col_id']; ?></td>
                            <td class="dataTableContent"><?php echo $flexfooter->fields['col_sort_order']; ?></td>
                            <td class="dataTableContent" align="center">
                              <?php
                              if ($flexfooter->fields['status'] == '1') {
                                echo zen_image(DIR_WS_IMAGES . 'icon_status_green.gif', ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $flexfooter->fields['page_id'] . '&action=setflag&flag=0') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', ICON_STATUS_RED, 10, 10) . '</a>';
                              } else {
                                echo '<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $flexfooter->fields['page_id'] . '&action=setflag&flag=1') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', ICON_STATUS_GREEN, 10, 10) . '</a>&nbsp;&nbsp;' . zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', ICON_STATUS_RED, 10, 10);
                              }
                              ?>
                            </td>
                            <td class="dataTableContent" align="right">
                              <?php
                              if (isset($bInfo) && is_object($bInfo) && ($flexfooter->fields['page_id'] == $bInfo->page_id)) {
                                echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                              } else {
                                echo '<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, zen_get_all_get_params(array('bID')) . 'bID=' . $flexfooter->fields['page_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                              }
                              ?>&nbsp;</td>
                            <td class="dataTableContent" align="right">&nbsp;</td>
                      </tr>
                      <?php
                      $flexfooter->MoveNext();
                    }
                    ?>
                    <tr>

                      <td class="smallText" valign="top" colspan="5"><?php echo $pages_split->display_count($pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_EZPAGE, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
                      <td class="smallText" align="right" colspan="3"><?php echo $pages_split->display_links($pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_EZPAGE, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('page', 'info', 'x', 'y', 'ezID'))); ?></td>
                    </tr>
                    <tr>
                      <td colspan="8"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td align="right" colspan="2">
                              <?php
                              echo '<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'action=new') . '">' .
                              zen_image_button('button_insert.gif', IMAGE_INSERT) . '</a>';
                              ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
                <?php
                if ($bInfo->status == 0) {
                  $teststatus = ICON_STATUS_RED;
                } else {
                  $teststatus = ICON_STATUS_GREEN;
                }
                $heading = array();
                $contents = array();
                switch ($action) {
                  case 'delete':
                    $heading[] = array('text' => '<b>' . $bInfo->col_header . $bInfo->page_title . '</b>');

                    $contents = array('form' => zen_draw_form('pages', FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&action=deleteconfirm') . zen_draw_hidden_field('bID', $bInfo->page_id));
                    $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                    $contents[] = array('text' => '<br /><b>' . $bInfo->page_title . '</b>');
                    if ($bInfo->col_image) {
                      $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('delete_image', 'on', true) . ' ' . FFM_TEXT_DELETE_IMAGE . '?');
                    }
                    $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;
                  default:
                    if (is_object($bInfo)) {

                      $heading[] = array('text' => '<b>' . $bInfo->col_header . $bInfo->page_title . '</b>');

                      $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $bInfo->page_id . '&action=new') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_FLEXIBLE_FOOTER_MENU, 'page=' . $_GET['page'] . '&bID=' . $bInfo->page_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br /><br /><br />');

                      $contents[] = array('text' => '<br />' . BOX_INFO_STATUS . ' ' . $teststatus);

                      if (zen_not_null($bInfo->col_image)) {
                        $contents[] = array('text' => '<br />' . zen_image(DIR_WS_CATALOG_IMAGES . $bInfo->col_image, $bInfo->page_title) . '<br />' . $bInfo->page_title);
                      } else {
                        $contents[] = array('text' => '<br />' . BOX_INFO_NO_IMAGE);
                      }

                      $contents[] = array('text' => '<br />' . BOX_INFO_TEXT . '<br /> ' . $bInfo->col_html_text);
                    }
                    break;
                }

                if ((zen_not_null($heading)) && (zen_not_null($contents))) {
                  echo '            <td width="25%" valign="top">' . "\n";

                  $box = new box;
                  echo $box->infoBox($heading, $contents);

                  echo '            </td>' . "\n";
                }
                ?>
              </tr>
            </table></td>
        </tr>
        <?php
      }
      ?>
    </table></td>
  <!-- body_text_eof //-->
</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>