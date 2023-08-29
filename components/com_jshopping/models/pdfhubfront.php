<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelPdfHubFront extends jshopBase
{
    public function checkGenerateInvoice($manuallysend = 0) 
    {
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->order_send_pdf_client || $jshopConfig->order_send_pdf_admin || $jshopConfig->send_invoice_manually) {
			return true;
		}else{
			return false;
		}
    }
	
	public function checkGenerateDeliveryNote($manuallysend = 0) 
	{
		$jshopConfig = JSFactory::getConfig();
		//$pdfsend = ($jshopConfig->send_invoice_manually && !$manuallysend) ? 0 : 1;
		//if ($pdfsend && ($jshopConfig->order_send_pdf_client || $jshopConfig->order_send_pdf_admin)) {
		if ($jshopConfig->order_send_pdf_client || $jshopConfig->order_send_pdf_admin || $jshopConfig->send_invoice_manually) {
			return true;
		}else{
			return false;
		}
	}
	
	public function getOrderPDFs($order) {		
		include_once JPATH_ROOT.'/components/com_expresseditor/engine/jshopping/helper.php';
		if (!class_exists('SEConfig')) {
			if (defined('JPATH_CONFIGURATION')) {
				if (file_exists(JPATH_CONFIGURATION . '/smarteditor_configuration.php')) {
					require_once JPATH_CONFIGURATION . '/smarteditor_configuration.php';
				} else {
					return;
				}
			} else {
				if (file_exists(JPATH_SITE . '/smarteditor_configuration.php')) {
					require_once JPATH_SITE . '/smarteditor_configuration.php';
				} else {
					return;
				}
			}
		}
		$seconfig = new SEConfig;
		$db = JFactory::getDBO();
        
		$html = "";
		$order = $order;
		
		try {
			// Try to execute the query with the 'publish_editor_pdf' column
			$query = "SELECT oi.*, p.publish_editor_pdf 
					  FROM `#__jshopping_order_item` AS oi 
					  JOIN `#__jshopping_products` AS p 
					  ON oi.product_id = p.product_id 
					  WHERE oi.order_id = " . $db->quote($order->order_id);
			$db->setQuery($query);
			$order_items = $db->loadObjectList();
		} catch (Exception $e) {
			// If the query fails, catch the exception and try again without the 'publish_editor_pdf' column
			$query = "SELECT oi.*
					  FROM `#__jshopping_order_item` AS oi 
					  JOIN `#__jshopping_products` AS p 
					  ON oi.product_id = p.product_id 
					  WHERE oi.order_id = " . $db->quote($order->order_id);
			$db->setQuery($query);
			$order_items = $db->loadObjectList();
		}
		
		foreach ($order_items as $order_item) {
			$attrs = unserialize($order_item->freeattributes);
			if (!empty($attrs)) {
				foreach ($attrs as $attr) {
					if (class_exists('FATHelper')) {
						$quantity = FATHelper::getStrFreeAttrQuantity($attr, true);
					}
					if (strpos($attr, '|') > 0) {
						$atts = explode('|', $attr);
						if (strtoupper($atts[0]) == 'FILE') {
							$html.="<br><a href='" . JURI::root() . "components/com_jshopping/files/addonuploadfile/" . trim($atts[1]) . "'>" . $atts[1] . "</a>";
						}
						if (strtoupper($atts[0]) == 'SMARTEDITOR') {
							$html.="<br><a href='" . JURI::root() . "products/" . trim($atts[1]) . "'>" . $atts[1] . "</a>";
						}
					}
					if ($quantity && class_exists('FATHelper')) {
						$html.= FATHelper::getTextStrQuanity($quantity);
					}
				}
			}
		}


		$products = EngineHelper::getOrderProducts_1($order, 'de-DE');
		
		foreach ($products as $product) {
			if ($product->userupload == 'on') {
				$html.="<BR><a href='../images/userproducts/" . $product->jsfile . " target='_blank'>" . $product->jsfile . "</a>";
			} else {
				$xml32png = @simplexml_load_file(JPATH_ROOT . "/" . $seconfig->editor_saved_xml . "loadXML/" . $product->xml . ".xml");
				$file_pdf_no = true;
				$file_info_pdf_no = false;
				if ($file_pdf_no && !empty($xml32png)) {
					$xml32png->product->colorspng32[0];
					if ((file_exists(JPATH_ROOT . '/products/' . $xml32png->product->colorspng32[0]))AND ( strlen($xml32png->product->colorspng32[0]) > 0)) {
						if (!file_exists(JPATH_ROOT . '/products/' . $xml32png->product->colorspng32[0]) . '_' . $order->order_number . '.png') {
							copy(JPATH_ROOT . '/products/' . $xml32png->product->colorspng32[0], JPATH_ROOT . '/products/' . $xml32png->product->colorspng32[0] . '_' . $order->order_number . '.png');
						}
						$html.="<BR><a href='" . JURI::root() . "products/" . $xml32png->product->colorspng32[0] . "_" . $order->order_number . ".png' target='_blank'>" . $xml32png->product->colorspng32[0] . "_" . $order->order_number . ".png</a>";
						$file_pdf_no = false;
					}
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_' . $order->order_number . '.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . '_' . $order->order_number . ".pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . ".pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_' . $order->order_number . '_1.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . '_' . $order->order_number . "_1.pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . "_1.pdf</a>";
					$file_pdf_no = false;
				}

				if ($file_pdf_no && (strlen($product->file) > 2)AND ( file_exists(JPATH_ROOT . '/products/' . $product->file . '_' . $order->order_number))) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->file . '_' . $order->order_number . "' target='_blank'>" . $product->file . '_' . $order->order_number . "</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/n' . $product->xml_id . '_' . $order->order_number . '.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/n" . $product->xml_id . '_' . $order->order_number . ".pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . ".pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/n' . $product->xml_id . '_' . $order->order_number . '_1.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/n" . $product->xml_id . '_' . $order->order_number . "_1.pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . "_1.pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/jpg/n' . $product->xml_id . '_' . $order->order_number . '.pdf.jpg')) {
					$html.= "<BR><a href='" . JURI::root() . "products/jpg/n" . $product->xml_id . '_' . $order->order_number . ".pdf.jpg' target='_blank'>" . $product->xml_id . '_' . $order->order_number . ".pdf.jpg</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . ".pdf' target='_blank'>" . $product->xml_id . ".pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_1.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . "_1.pdf' target='_blank'>" . $product->xml_id . "_1.pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && (strlen($product->file) > 2)AND ( file_exists(JPATH_ROOT . '/products/' . $product->file))) {
					$html.= "<BR><a href='" . JURI::root() . "products/" . $product->file . "' target='_blank'>" . $product->file . "</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/n' . $product->xml_id . '.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/n" . $product->xml_id . ".pdf' target='_blank'>" . $product->xml_id . ".pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/n' . $product->xml_id . '_1.pdf')) {
					$html.= "<BR><a href='" . JURI::root() . "products/n" . $product->xml_id . "_1.pdf' target='_blank'>" . $product->xml_id . "_1.pdf</a>";
					$file_pdf_no = false;
				}
				if ($file_pdf_no && file_exists(JPATH_ROOT . '/products/jpg/n' . $product->xml_id . '.pdf.jpg')) {
					$html.= "<BR><a href='" . JURI::root() . "products/jpg/n" . $product->xml_id . ".pdf.jpg' target='_blank'>" . $product->xml_id . ".pdf.jpg</a>";
					$file_pdf_no = false;
				}
			}

			if ($file_info_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_' . $order->order_number . '_info.pdf')) {
				$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . '_' . $order->order_number . "_info.pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . "_info.pdf</a>";					
				$file_info_pdf_no = false;
			}
			if ($file_info_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_info.pdf')) {
				$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . "_info.pdf' target='_blank'>" . $product->xml_id . "_info.pdf</a>";
				$file_info_pdf_no = false;
			}
			if ($file_info_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_' . $order->order_number . '_info_php.pdf')) {
				$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . '_' . $order->order_number . "_info_php.pdf' target='_blank'>" . $product->xml_id . '_' . $order->order_number . "_info_php.pdf</a>";
				$file_info_pdf_no = false;
			}
			if ($file_info_pdf_no && file_exists(JPATH_ROOT . '/products/' . $product->xml_id . '_info_php.pdf')) {
				$html.= "<BR><a href='" . JURI::root() . "products/" . $product->xml_id . "_info_php.pdf' target='_blank'>" . $product->xml_id . "_info_php.pdf</a>";
				$file_info_pdf_no = false;
			}

			$tmpfilename = 'pistd_' . $product->order_id . '_' . $product->order_item_id . '_' . $product->product_id . '.pdf';
			if (file_exists(JPATH_ROOT . '/products/' . $tmpfilename)) {
				$html.= "<div><a href='" . JURI::root() . "/products/" . $tmpfilename . "' target='_blank'>" . $tmpfilename . "</a></div>";
			}
			$products_pdf[$product->product_id].=$html;
		}
		return $products_pdf;	
	}
}