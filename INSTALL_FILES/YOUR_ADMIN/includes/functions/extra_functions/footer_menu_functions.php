<?php
/**
 *
 * Flexible Footer Menu Multilingual
 *
 * @package admin
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 *
 * @added for version 1.0 by ZCAdditions.com (rbarbour) 4-17-2013 $
 *
**/

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

function zen_set_flexible_footer_menu_status($page_id, $status) {
global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_FLEXIBLE_FOOTER_MENU . " set status = '1' where page_id = '" . $page_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_FLEXIBLE_FOOTER_MENU . " set status = '0' where page_id = '" . $page_id . "'");
    } else {
      return -1;
    }
  }
?>
