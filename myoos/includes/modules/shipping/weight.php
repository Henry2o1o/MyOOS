<?php
/* ----------------------------------------------------------------------
   $Id: weight.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2020 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: weight.php,v 1.05 2003/02/18 03:37:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class weight {
    var $code, $title, $description, $icon, $enabled = FALSE;

// class constructor
    public function __construct() {
      global $oOrder, $aLang;

      $this->code = 'weight';
      $this->title = $aLang['module_shipping_weight_text_title'];
      $this->description = $aLang['module_shipping_weight_text_description'];
      $this->sort_order = (defined('MODULE_SHIPPING_WEIGHT_SORT_ORDER') ? MODULE_SHIPPING_WEIGHT_SORT_ORDER : null);
      $this->icon = '';
      $this->tax_class = (defined('MODULE_SHIPPING_WEIGHT_TAX_CLASS') ? MODULE_SHIPPING_WEIGHT_TAX_CLASS : null);
      $this->enabled = (defined('MODULE_SHIPPING_WEIGHT_STATUS') && (MODULE_SHIPPING_WEIGHT_STATUS == 'true') ? true : false);

      if ( ($this->enabled == TRUE) && ((defined('MODULE_SHIPPING_WEIGHT_ZONE') && (int)MODULE_SHIPPING_WEIGHT_ZONE > 0)) ) {
        $check_flag = FALSE;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $check_result = $dbconn->Execute("SELECT zone_id FROM " . $oostable['zones_to_geo_zones'] . " WHERE geo_zone_id = '" . MODULE_SHIPPING_WEIGHT_ZONE . "' AND zone_country_id = '" . $oOrder->delivery['country']['id'] . "' ORDER BY zone_id");
        while ($check = $check_result->fields) {
          if ($check['zone_id'] < 1) {
            $check_flag = TRUE;
            break;
          } elseif ($check['zone_id'] == $oOrder->delivery['zone_id']) {
            $check_flag = TRUE;
            break;
          }

          // Move that ADOdb pointer!
          $check_result->MoveNext();
        }

        // Close result set
        $check_result->Close();

        if ($check_flag == FALSE) {
          $this->enabled = FALSE;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $oOrder, $aLang, $shipping_weight;

      $weight_cost = preg_split("/[:,]/" , MODULE_SHIPPING_WEIGHT_COST);

      if ($shipping_weight > $weight_cost[count ($weight_cost)-2]) {
          $shipping = ($shipping_weight-$weight_cost[count ($weight_cost)-2])* MODULE_SHIPPING_WEIGHT_STEP +$weight_cost[count ($weight_cost)-1];
      }
      for ($i = 0; $i < count($weight_cost); $i+=2) {
        if ($shipping_weight <= $weight_cost[$i]) {
            $shipping = $weight_cost[$i+1];
            break;
        }
      }

      $this->quotes = array('id' => $this->code,
                            'module' => $aLang['module_shipping_weight_text_title'],
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $aLang['module_shipping_weight_text_way'],
                                                     'cost' => $shipping + MODULE_SHIPPING_WEIGHT_HANDLING)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = oos_get_tax_rate($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
      }

      if (oos_is_not_null($this->icon)) $this->quotes['icon'] = oos_image($this->icon, $this->title);

      return $this->quotes;
    }


    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_SHIPPING_WEIGHT_STATUS');
      }

      return $this->_check;
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_HANDLING', '5', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_TAX_CLASS', '0', '6', '0', 'oos_cfg_get_tax_class_title', 'oos_cfg_pull_down_tax_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_COST', '31:15,40:28,50:30.5,100:33', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_STEP', '0.28', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_MODE', 'weight', '6', '0', 'oos_cfg_select_option(array(\'weight\', \'price\'), ', now())");
    }


    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }


    function keys() {
      return array('MODULE_SHIPPING_WEIGHT_STATUS', 'MODULE_SHIPPING_WEIGHT_HANDLING', 'MODULE_SHIPPING_WEIGHT_COST', 'MODULE_SHIPPING_WEIGHT_STEP', 'MODULE_SHIPPING_WEIGHT_TAX_CLASS', 'MODULE_SHIPPING_WEIGHT_ZONE', 'MODULE_SHIPPING_WEIGHT_SORT_ORDER');
    }
  }

