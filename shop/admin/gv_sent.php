<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_sent.php,v 1.2.2.1 2003/04/18 16:17:14 wilt 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  require 'includes/header.php'; 
?>
<div class="wrapper">
	<!-- Header //-->
	<header class="topnavbar-wrapper">
		<!-- Top Navbar //-->
		<?php require 'includes/menue.php'; ?>
	</header>
	<!-- END Header //-->
	<aside class="aside">
		<!-- Sidebar //-->
		<div class="aside-inner">
			<?php require 'includes/blocks.php'; ?>
		</div>
		<!-- END Sidebar (left) //-->
	</aside>
	
	<!-- Main section //-->
	<section>
		<!-- Page content //-->
		<div class="content-wrapper">		

			<!-- Breadcrumbs //-->
			<div class="row wrapper gray-bg page-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->
			
			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">		
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SENDERS_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VOUCHER_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_SENT; ?></td>		
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $couponstable = $oostable['coupons'];
  $coupon_email_tracktable = $oostable['coupon_email_track'];
  $gv_result_raw = "SELECT c.coupon_amount, c.coupon_code, c.coupon_id, et.sent_firstname, et.sent_lastname,
                           et.customer_id_sent, et.emailed_to, et.date_sent, c.coupon_id
                    FROM $couponstable c,
                         $coupon_email_tracktable et
                    WHERE c.coupon_id = et.coupon_id";
  $gv_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $gv_result_raw, $gv_result_numrows);
  $gv_result = $dbconn->Execute($gv_result_raw);
  while ($gv_list = $gv_result->fields) {
    if ((!isset($_GET['gid']) || (isset($_GET['gid']) && ($_GET['gid'] == $gv_list['coupon_id']))) && !isset($gInfo)) {
      $gInfo = new objectInfo($gv_list);
    }
    if (isset($gInfo) && is_object($gInfo) && ($gv_list['coupon_id'] == $gInfo->coupon_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin('gv_sent.php', oos_get_all_get_params(array('gid', 'action')) . 'gid=' . $gInfo->coupon_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin('gv_sent.php', oos_get_all_get_params(array('gid', 'action')) . 'gid=' . $gv_list['coupon_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $gv_list['sent_firstname'] . ' ' . $gv_list['sent_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $currencies->format($gv_list['coupon_amount']); ?></td>
                <td class="dataTableContent" align="center"><?php echo $gv_list['coupon_code']; ?></td>
                <td class="dataTableContent" align="right"><?php echo oos_date_short($gv_list['date_sent']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($gInfo) && is_object($gInfo) && ($gv_list['coupon_id'] == $gInfo->coupon_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['gv_sent'], 'page=' . $_GET['page'] . '&gid=' . $gv_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $gv_result->MoveNext();
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_links($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text' => '[' . $gInfo->coupon_id . '] ' . ' ' . $currencies->format($gInfo->coupon_amount));
  $redeem_result = $dbconn->Execute("SELECT * FROM " . $oostable['coupon_redeem_track'] . " WHERE coupon_id = '" . $gInfo->coupon_id . "'");
  $redeemed = 'No';
  if ($redeem_result->RecordCount() > 0) $redeemed = 'Yes';
  $contents[] = array('text' => TEXT_INFO_SENDERS_ID . ' ' . $gInfo->customer_id_sent);
  $contents[] = array('text' => TEXT_INFO_AMOUNT_SENT . ' ' . $currencies->format($gInfo->coupon_amount));
  $contents[] = array('text' => TEXT_INFO_DATE_SENT . ' ' . oos_date_short($gInfo->date_sent));
  $contents[] = array('text' => TEXT_INFO_VOUCHER_CODE . ' ' . $gInfo->coupon_code);
  $contents[] = array('text' => TEXT_INFO_EMAIL_ADDRESS . ' ' . $gInfo->emailed_to);
  if ($redeemed=='Yes') {
    $redeem = $redeem_result->fields;
    $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_REDEEMED . ' ' . oos_date_short($redeem['redeem_date']));
    $contents[] = array('text' => TEXT_INFO_IP_ADDRESS . ' ' . $redeem['redeem_ip']);
    $contents[] = array('text' => TEXT_INFO_CUSTOMERS_ID . ' ' . $redeem['customer_id']);
  } else {
    $contents[] = array('text' => '<br />' . TEXT_INFO_NOT_REDEEMED);
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2015 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>