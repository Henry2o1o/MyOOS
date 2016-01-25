<?php
/* ----------------------------------------------------------------------
   $Id: class_box.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: box.php,v 1.5 2002/03/16 00:20:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- 
   Example usage:

   $heading = array(); 
   $heading[] = array('params' => 'class="menuBoxHeading"',
                      'text'  => BOX_HEADING_TOOLS,
                      'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

   $contents = array();
   $contents[] = array('text'  => SOME_TEXT);

   $box = new box;
   echo $box->infoBox($heading, $contents);
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class box extends tableBlock {
	public function __construct() {
      $this->heading = array();
      $this->contents = array();
    }

    public function infoBox($heading, $contents) {
      $this->table_row_parameters = 'class="infoBoxHeading"';
      $this->table_data_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->tableBlock($heading);

      $this->table_row_parameters = '';
      $this->table_data_parameters = 'class="infoBoxContent"';
      $this->contents = $this->tableBlock($contents);

      return $this->heading . $this->contents;
    }

    public function menuBox($heading, $contents) {

        $this->table_data_parameters = 'class="menuBoxHeading"';
        if ($heading[0]['link']) {
          $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
          $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
        } else {
          $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
        }
        $this->heading = $this->tableBlock($heading);

        $this->table_data_parameters = 'class="menuBoxContent"';
        $this->contents = $this->tableBlock($contents);

        return $this->heading . $this->contents;

    }
  }	
	
