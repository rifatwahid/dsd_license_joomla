<?php

require_once ('orders.php');

class JshoppingControllerOrders_addon extends JshoppingControllerOrders{	

	private $_cid;
	
	function __construct( $config = array() ){
        parent::__construct( $config );
        $this->_cid = JFactory::getApplication()->input->getVar("cid",null);
		if($this->_cid == '') $this->_cid = null;
		if(is_string($this->_cid)) $this->_cid = explode(',',$this->_cid);
    }
	
	function pdf(){		
		if(count($this->_cid) > 0){
			$order = JTable::getInstance('order', 'jshop');			
			$jshopConfig = JSFactory::getConfig();			
			include_once(JPATH_ROOT."/components/com_jshopping/addons/orders_mass_action/Zend/Pdf.php");
			$pdf = '';	
			foreach($this->_cid as $id){
				$order->load($id);
				$path = $jshopConfig->pdf_orders_path."/".$order->pdf_file;
				
				if ($order->pdf_file==""){
					continue;
				}
				
				if($pdf === ''){
					$pdf = Zend_Pdf::load($path);
				}else{
					$pdf2 = Zend_Pdf::load($path);
					foreach ($pdf2->pages as $page){
						$pdf->pages[] = clone $page;
						unset($page);
					}
					unset($pdf2);
				}
			}			
			$saveName = 'list_orers.pdf';
			if($pdf != ''){
				$pdf->save($jshopConfig->pdf_orders_path."/".$saveName);
				echo $jshopConfig->pdf_orders_live_path.'/'.$saveName;				
			}else{
				throw new Exception('Not PDF files',500);
			}		
		}else{
			throw new Exception('Not cids!',500);
		}
		exit();
	}
	
	
	function csv(){
		if (count($this->_cid) > 0){
			$order = JTable::getInstance('order', 'jshop');
			$jshopConfig = JSFactory::getConfig();			
			include_once(JPATH_ROOT."/components/com_jshopping/lib/csv.io.class.php");
			$acsv = array();
			$keys = array_keys($order->getFields());
            array_unshift($keys, 'rowtype');
			$orderitem = JTable::getInstance('orderitem', 'jshop');
			$fkpr = $kpr = array_keys($orderitem->getFields());
			unset($orderitem);	
			array_unshift($fkpr, 'itemhead');			
			
			$acsv[] = $keys;
			foreach($this->_cid as $id){				
				$order->load($id);
				$order->items = null;
				$order->products = $order->getAllItems();
				$ao = array();
				foreach($keys as $k){
					$ao[$k] = '';
                    if ($k=='rowtype'){
                        $ao[$k] = 'order';
                    }
					if (isset($order->$k)){
						$unsa = array('order_tax_ext','shipping_tax_ext','payment_tax_ext','package_tax_ext');
						if (in_array($k,$unsa)){
							$ao[$k] = $this->fieldUS($order->$k);
						}else{
							$ao[$k] = $order->$k;
						}
					}
				}
				$acsv[] = $ao;
				if (count($order->products) > 0){
					$acsv[] = $fkpr;
					foreach($order->products as $prod){
						$p = array();$p[] = 'item';
						foreach($kpr as $kpr_k){
							$p[$kpr_k] = ''; 
							if (isset($prod->$kpr_k)){
								$unsa = array('attributes','freeattributes','files');
								$na = array('product_attributes','product_freeattributes');
								if(in_array($kpr_k,$unsa)){
									$p[$kpr_k] = $this->fieldUS($prod->$kpr_k);
								}else if(in_array($kpr_k,$na)){
									$p[$kpr_k] = preg_replace("/(\r\n|\r|\n)/is",'|', trim($prod->$kpr_k));
								}else{
									$p[$kpr_k] = $prod->$kpr_k;
								}
							}
						}
						$acsv[] = $p;
					}
				}
			}			
			$saveName = 'list_orers.csv';
			if(count($acsv) > 0){
				$csv = new csv;
				if($csv->write($jshopConfig->pdf_orders_path.'/'.$saveName,$acsv)){
					echo $jshopConfig->pdf_orders_live_path.'/'.$saveName;
				}else{
					throw new Exception('Not write CSV!',500);
				}			
			}else{
				throw new Exception('Not CSV files!',500);
			}		
		}else{
			throw new Exception('Not cids!',500);
		}
		exit();
	}
	
	function savestatus(){
		$batchOrderStatus = JFactory::getApplication()->input->getVar("batch_order_status");
        $batchNotifyCustomer = JFactory::getApplication()->input->getInt("batch_notify_customer");
        $order_status = JFactory::getApplication()->input->getVar('select_status_id');
		$order_check_id = JFactory::getApplication()->input->getVar('order_check_id');
		if(count($this->_cid) > 0){
			foreach($this->_cid as $id){
				if($batchOrderStatus){
                    $this->_updateStatus($id, $batchOrderStatus, '', $batchNotifyCustomer, '', '', 0); 
                }else{
                    $notify = !empty($order_check_id[$id]) ? 1 : 0;
                    if(isset($order_status[$id])){
					    $this->_updateStatus($id,$order_status[$id],'',$notify,'','',0); 
				    }
                }
			}
		}
		$this->setRedirect("index.php?option=com_jshopping&controller=orders");
	}
	
	function fieldUS($str){
		if ($us = unserialize($str)){
			if (count($us)>0){
				$usstra = array();
				foreach($us as $uk=>$uv){
					$usstra[] = $uk != '' ? $uk.':'.$uv : $uv;
				}
				return implode(',',$usstra);
			}
		}
		return '';
	}

    public function ordersBatch(){
        $view = $this->getView("ordersBatch", "html");
        $_orders = JModelLegacy::getInstance("orders", 'JshoppingModel');
        $view->set("orderStatusOptions", array_merge(array(0 => "- ".JText::_('COM_SMARTSHOP_DEFAULT')." -"), $_orders->getAllOrderStatus()));
        $view->display();
    }    
}