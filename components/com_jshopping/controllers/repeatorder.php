<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class JshoppingControllerRepeatOrder extends JshoppingControllerBase 
{

    public function display($cachable = false, $urlparams = false) 
    {
        $cart = JModelLegacy::getInstance('cart', 'jshop');
		$cart->load();
        $order_id = JFactory::getApplication()->input->getInt('order_id');

        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;
		
        $orderProductInfo = $this->orderProductInfo($order_id);
		
        foreach ($orderProductInfo as $key => $product) {
            $product_id = $product['product_id'];
            $product_quantity = intval($product['product_quantity']);

            $attr_id = [];
            $attr_id = unserialize($product['attributes']);

            $freeattributes = [];
            $freeattributes = unserialize($product['freeattributes']);
            $addInfo = [];
            $addInfo['reorder'] = $product['reorder'];
            $addInfo['reorder_num'] = $product['reorder_num'];
            $addInfo['order_item_id'] = $product['order_item_id'];
            $product = JTable::getInstance('product', 'jshop');
            $product->load($product_id);

            if ($product->product_publish == 0) {
                //JError::raiseWarning(108, JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT', 0));
				$errors = JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT', 0);
				\JFactory::getApplication()->enqueueMessage($errors,'error');
                continue;
            }
            foreach($attr_id as $id=>$val){
				if($val == -1){
					unset($attr_id[$id]);
					$usetriggers = 1;
				}
			}
		$productTable = JSFactory::getTable('product');
        $productTable->load($product_id);
		$attr_id = is_array($attr_id) ? $attr_id : [];
		$attributesDatas = $productTable->getAttributesDatas($attr_id, JSFactory::getUser()->usergroup_id);
		$attr_id=$attributesDatas['attributeActive'];		
		foreach ($attr_id as $key=>$val){
			if ($val<=0){unset($attr_id[$key]);}
		}
        $productTable->setAttributeActive($attr_id);
        $productTable->getExtendsData();

			if($this->ifProductExist($product_id, $attr_id)){
				$cart->add($product_id, $product_quantity, $attr_id, $freeattributes, $addInfo, $usetriggers, $errors, $displayErrorMessage, $orderProductInfo[$key]['uploadData']);
			}else{
				$errors = JText::_('COM_SMARTSHOP_SELECT_PRODUCT_OPTIONS');
				\JFactory::getApplication()->enqueueMessage($errors,'error');
				 continue;
			}
        }
			
        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function add($cachable = false, $urlparams = false) 
    {
        $cart = JModelLegacy::getInstance('cart', 'jshop');
		$cart->load();
        $order_id = JFactory::getApplication()->input->getInt('order_id');
        $order_product_id = JFactory::getApplication()->input->getInt('product_id');
        $order_item_id = JFactory::getApplication()->input->getInt('order_item_id');

        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;

        $orderProductInfo = $this->orderProductInfo($order_id);
        $product = false;
        $orderUploadInfo = [];

        foreach ($orderProductInfo as $item) {
            if ($item['product_id'] == $order_product_id && $order_item_id == $item['order_item_id']) {
                $product = $item;
                $orderUploadInfo = $product['uploadData'];
            }
        }
        
        if (!$product) {
            //JError::raiseWarning(505, sprintf(JText::_('COM_SMARTSHOP_ERROR_DATA'), 0));
			\JFactory::getApplication()->enqueueMessage(sprintf(JText::_('COM_SMARTSHOP_ERROR_DATA'), 0),'error');
			 $this->setRedirect(SEFLink('index.php', 0, 1));
          return 1;
        }

        $product_id = $product['product_id'];
        $product_quantity = intval($product['product_quantity']);

        $attr_id = [];
        $attr_id = unserialize($product['attributes']);

        $freeattributes = [];
        $freeattributes = unserialize($product['freeattributes']);

        $product_table = JTable::getInstance('product', 'jshop');
        $product_table->load($product_id);
        if ($product_table->product_publish == 0) {
			\JFactory::getApplication()->enqueueMessage(sprintf(JText::_('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT'), 0),'error');
        }

		$addInfo = [];
		$addInfo['reorder'] = $product['reorder'];
		$addInfo['reorder_num'] = $product['reorder_num'];
		foreach($attr_id as $id=>$val){
			if($val == -1){
				unset($attr_id[$id]);
				$usetriggers = 1;
			}
		}
        $cart->add($product_id, $product_quantity, $attr_id, $freeattributes, $addInfo, $usetriggers, $errors, $displayErrorMessage, $orderUploadInfo);

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function orderProductInfo($order_id) 
    {
        $order = JTable::getInstance('order', 'jshop');
        $order->load($order_id);
        $orderListProducts = $order->getAllItems();

        foreach ($orderListProducts as $pkey => $or_prod) {
            $orderProductInfo[$pkey]['product_id'] = $or_prod->product_id;
            $orderProductInfo[$pkey]['product_quantity'] = $or_prod->product_quantity;
            $orderProductInfo[$pkey]['attributes'] = $or_prod->attributes;
            $orderProductInfo[$pkey]['freeattributes'] = $or_prod->freeattributes;
            $orderProductInfo[$pkey]['charakteristik'] = $or_prod->extra_fields;
            $orderProductInfo[$pkey]['weight'] = $or_prod->weight;
            $orderProductInfo[$pkey]['thumb_image'] = $or_prod->thumb_image;
            $orderProductInfo[$pkey]['product_old_price'] = $or_prod->product_item_price;
            $orderProductInfo[$pkey]['reorder'] = $order->order_number;
            $orderProductInfo[$pkey]['reorder_num'] = $pkey + 1;
            $orderProductInfo[$pkey]['order_item_id'] = $or_prod->order_item_id;
            $orderProductInfo[$pkey]['uploadData'] = $or_prod->uploadData;
        }

        return $orderProductInfo;
    }
	
	public function ifProductExist($product_id, $attr_id){
		$product = JSFactory::getTable('product', 'jshop');
		$product->load($product_id,true);
		$attributesDatas = $product->getAttributesDatas();
		$attr_val = [];
		if(!empty($attributesDatas['attributeValues']) && !empty($attr_id)){
			foreach($attr_id as $attr=>$val){
				if(!array_key_exists($attr, $attributesDatas['attributeValues'])){
					return false;
				}
				
				foreach($attributesDatas['attributeValues'][$attr] as $key => $value){
					$attr_val[$attr][] = $value->val_id;
				}
				if(!empty($attr_val[$attr]) && !in_array($val, $attr_val[$attr])){
					return false;
				}
			}
		}
		return true;
	}

}