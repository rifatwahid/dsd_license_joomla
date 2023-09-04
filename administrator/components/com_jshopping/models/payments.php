<?php
/**
* @version      4.7.1 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelPayments extends JModelLegacy{
    
    public function getAllPaymentMethods($publish = 1, $order = null, $orderDir = null) {
        $database = JFactory::getDBO(); 
        $query_where = ($publish)?("WHERE payment_publish = '1'"):("");
        $lang = JSFactory::getLang();
        $ordering = 'payment_ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT payment_id, `".$lang->get("name")."` as name, `".$lang->get("description")."` as description , payment_code, payment_class, scriptname, payment_publish, payment_ordering, payment_params, payment_type, usergroup_id FROM `#__jshopping_payment_method`
                  $query_where
                  ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $database->setQuery($query);
        return $database->loadObjectList();
    }
    
    public function getTypes(){
    	return array('1' => JText::_('COM_SMARTSHOP_TYPE_DEFAULT'),'2' => JText::_('COM_SMARTSHOP_PAYPAL_RELATED'));
    }
    
    public function getMaxOrdering(){
        $db = \JFactory::getDBO(); 
        $query = "select max(payment_ordering) from `#__jshopping_payment_method`";
        $db->setQuery($query);
        return $db->loadResult();
    }
    
	public function getListNamePaymens($publish = 1){
        $_list = $this->getAllPaymentMethods($publish);
        $list = array();
        foreach($_list as $v){
            $list[$v->payment_id] = $v->name;
        }
        return $list;
    }	
	
	public function uploadImage($post){
		$mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
		require_once ($jshopConfig->path.'lib/image.lib.php');
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        $dispatcher = \JFactory::getApplication();
        
        $upload = new UploadFile($_FILES['image']);
        $upload->setAllowFile(array('jpeg','jpg','gif','png'));
        $upload->setDir($jshopConfig->image_payments_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        if ($upload->upload()){
            $name = $upload->getName();
            if ($post['old_image'] && $name!=$post['old_image']){
                @unlink($jshopConfig->image_payments_path."/".$post['old_image']);
            }
            @chmod($jshopConfig->image_payments_path."/".$name, 0777);
            
            if ($post['size_im_payments'] < 3){
                if($post['size_im_payments'] == 1){
                    $payments_width_image = $jshopConfig->image_payments_width; 
                    $payments_height_image = $jshopConfig->image_payments_height;
                }else{
                    $payments_width_image = JFactory::getApplication()->input->getInt('payments_width_image'); 
                    $payments_height_image = JFactory::getApplication()->input->getInt('payments_height_image');
                }

                $path_full = $jshopConfig->image_payments_path."/".$name;
                $path_thumb = $jshopConfig->image_payments_path."/".$name;
                if ($payments_width_image || $payments_height_image){
                    if (!ImageLib::resizeImageMagic($path_full, $payments_width_image, $payments_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
                        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_CREATE_THUMBAIL'),'error');
                        saveToLog("error.log", "SaveCategory - Error create thumbail");
                    }
                }
                @chmod($jshopConfig->image_payments_path."/".$name, 0777);
            }
            $payments_image = $name;
            $dispatcher->triggerEvent('onAfterSavePaymentsImage', array(&$post, &$payments_image, &$path_full, &$path_thumb));
        }else{
            $payments_image = '';
            if ($upload->getError() != 4){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_IMAGE'),'error');
                saveToLog("error.log", "SavePayments - Error upload image. code: ".$upload->getError());
            }
        }
        return $payments_image;
    }
	
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_payment_method',"payment_id","payment_ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_payment_method',"payment_id","payment_ordering",$number,$cid);
		}	
	}
	
	public function deletePayments($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_payment_method","payment_id",$value))
                $text .= JText::_('COM_SMARTSHOP_PAYMENT_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_ERROR_PAYMENT_DELETED')."<br>";
		}
		return $text;
	}
	
	public function publishPayments($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_payment_method","payment_id",$value,"payment_publish",$flag);			
		}
	}
}
?>