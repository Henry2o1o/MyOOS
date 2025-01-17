﻿<?php
/* ----------------------------------------------------------------------
   $Id: oos160.php,v 1.3 2009/01/13 21:29:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team
   ----------------------------------------------------------------------
   Based on:

   File: pn64.php,v 1.45 2002/03/16 15:24:37 johnnyrocket
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

global $db, $prefix_table, $currentlang;

if (!$prefix_table == '') $prefix_table = $prefix_table . '_';


$today = date("Y-m-d H:i:s");

// configuration
$table = $prefix_table . 'configuration';

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('BLOG_URL', '', 11, 6, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('PHPBB_URL', '', 11, 7, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'true\', \'false\'),' WHERE set_function = 'oos_cfg_select_option(array(\'TRUE\', \'FALSE\'),'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'true\', \'false\'),' WHERE set_function = 'oos_cfg_select_option(array(\'True\', \'False\'),'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 


$result = $db->Execute("UPDATE " . $table . " SET configuration_value = 'false' WHERE configuration_value = 'FALSE'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("UPDATE " . $table . " SET configuration_value = 'false' WHERE configuration_value = 'False'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 


$result = $db->Execute("UPDATE " . $table . " SET configuration_value = 'true' WHERE configuration_value = 'TRUE'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("UPDATE " . $table . " SET configuration_value = 'true' WHERE configuration_value = 'True'");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

// product_gallery
$table = $prefix_table . 'products_images';
$new_name = $prefix_table . 'products_gallery';
$result = $db->Execute("RENAME TABLE " . $table . " TO " . $new_name . "");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 




// products_model_viewer
$table = $prefix_table . 'products_model_viewer';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `model_viewer_scale` VARCHAR(5) NOT NULL DEFAULT 'auto' AFTER `model_viewer_auto_rotate`");


// products_attributes
$table = $prefix_table . 'products_attributes';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `options_values_model` VARCHAR(12) NULL AFTER `options_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . " ADD `options_values_image` VARCHAR(255) NULL AFTER `options_values_model`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . "  ADD `options_values_base_price` DECIMAL(10,4) NOT NULL AFTER `options_values_price`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . "  ADD `options_values_quantity` DECIMAL(10,2) NOT NULL DEFAULT '1.00' `options_values_price`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . "  ADD `options_values_base_quantity` DECIMAL(10,2) NOT NULL DEFAULT '1.00' AFTER `options_values_base_price`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . "  ADD `options_values_status` VARCHAR(1) NOT NULL DEFAULT '1' AFTER `options_values_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} 

$result = $db->Execute("ALTER TABLE " . $table . "  ADD `options_values_base_unit` VARCHAR(12) DEFAULT NULL AFTER `options_values_base_quantity`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

