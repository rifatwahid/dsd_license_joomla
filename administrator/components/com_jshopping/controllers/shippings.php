<?php
/**
* @version      4.8.0 24.07.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerShippings extends JControllerLegacy{
	
	protected $canDo;

    public function __construct($config = [])    {
        parent::__construct($config);

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        $this->registerTask('orderup', 'reorder');
        $this->registerTask('orderdown', 'reorder');
        $this->registerTask('publish', 'republish');
        $this->registerTask('unpublish', 'republish');
        checkAccessController("shippings");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
        addSubmenu('other',$this->canDo);
    }
    
    public function display($cachable = false, $urlparams = false)
    {		
        $mainframe = JFactory::getApplication();
        $context = 'jshoping.list.admin.shippings';
        $filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
        
		$_shippings = JSFactory::getModel('shippings');
		$rows = $_shippings->getAllShippings(0, $filter_order, $filter_order_Dir);
        
        $not_set_price = [];
        $rowsprices = $_shippings->getAllShippingPrices(0);
        $shippings_prices = [];
        foreach($rowsprices as $row){
            $shippings_prices[$row->shipping_method_id][] = $row;
        }

        foreach($rows as $k=>$v) {
            if (is_array($shippings_prices[$v->shipping_id])) {
                $rows[$k]->count_shipping_price = count($shippings_prices[$v->shipping_id]);
            } else {
				$not_set_price[] = '<a href="index.php?option=com_jshopping&controller=shippingsprices&task=edit&shipping_id_back=' . $rows[$k]->shipping_id . '">' . $rows[$k]->name . '</a>';
                $rows[$k]->count_shipping_price = 0;
            }
        }
        
        if ($not_set_price) {
            $mainframe->enqueueMessage(JText::_('COM_SMARTSHOP_CERTAIN_METHODS_DELIVERY_NOT_SET_PRICE') . ' (' . implode(', ', $not_set_price) . ')!', 'notice');
        }		
		$view = $this->getView('shippings', 'html');
        $view->setLayout('list');
		$view->set('canDo', $canDo);
		$view->set('rows', $rows);		
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayShippings', [&$view]);
		$view->displayList();
	}
	
    public function edit() 
    {
		$jshopConfig = JSFactory::getConfig();
		$shipping_id = JFactory::getApplication()->input->getInt('shipping_id');
		$shipping = JSFactory::getTable('shippingMethod', 'jshop');
		$shipping->load($shipping_id);
		$shipping->image = substr($shipping->image, strrpos($shipping->image, '/') + 1, strlen($shipping->image) - strrpos($shipping->image, '/'));
		$edit = ($shipping_id)?($edit = 1):($edit = 0);
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
		$params = $shipping->getParams();
        
        $_payments = JSFactory::getModel('payments');
        $list_payments = $_payments->getAllPaymentMethods(0);
        $active_payments = $shipping->getPayments();
        if (!count($active_payments)){
            $active_payments = array(0);
        }        
        $first = array();
        $first[] = JHTML::_('select.option', '0', JText::_('COM_SMARTSHOP_ALL'), 'id','name');
        
        $lists['payments'] = JHTML::_('select.genericlist', array_merge($first, $list_payments), 'listpayments[]', 'class="inputbox form-select" size="10" multiple = "multiple"', 'payment_id', 'name', $active_payments);

        $nofilter = array();
        JFilterOutput::objectHTMLSafe($shipping, ENT_QUOTES, $nofilter);
        $_usergroups = JSFactory::getModel("usergroups");
		$usergroups_list = $_usergroups->getAllUsergroupsSelect();
		$view=$this->getView('shippings', 'html');
        $view->setLayout('edit');
		$view->set('canDo', $canDo);
		$view->set('usergroups_list', $usergroups_list);
		$view->set('params', $params);
		$view->set('shipping', $shipping);
		$view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('lists', $lists);
		$view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditShippings', array(&$view));
		$view->displayEdit();
	}
	
    public function save()
    {
        $jshopConfig = JSFactory::getConfig();
		$shipping_id = JFactory::getApplication()->input->getInt('shipping_id', 0);
		$shipping = JSFactory::getTable('shippingMethod', 'jshop');
        $post = JFactory::getApplication()->input->post->getArray();
		$post['usergroup_id']=implode(",",(array)$post['usergroup_id']);
        if (!isset($post['published'])) $post['published'] = 0;
        if (!$post['listpayments']){
            $post['listpayments'] = array();
        }
        $shipping->setPayments($post['listpayments']);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveShipping', array(&$post));
        
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);

        foreach($languages as $lang) {
            $post['description_' . $lang->language] = $_POST['description' . $lang->id];
        }
		
		$_shippings = JSFactory::getModel('shippings');
		$upload_image = $_shippings->uploadImage($post);
        if (!empty($upload_image)) {
            $post['image'] = $jshopConfig->image_shippings_live_path . '/' . $upload_image;
        }
		
		if (!$shipping->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect('index.php?option=com_jshopping&controller=shippings');
            
			return 0;
		}
        
        if (!$shipping->shipping_id){
            $shipping->ordering = $_shippings->getMaxOrdering() + 1;
        }

		$shipping->setParams($post['s_params']);

		if (!$shipping->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect('index.php?option=com_jshopping&controller=shippings');
            
			return 0;
		}
        
        $dispatcher->triggerEvent('onAfterSaveShipping', [&$shipping]);
        
		if ($this->getTask()=='apply') {
            $this->setRedirect('index.php?option=com_jshopping&controller=shippings&task=edit&shipping_id=' . $shipping->shipping_id); 
        } else {
            $this->setRedirect('index.php?option=com_jshopping&controller=shippings');
        }

	}
	
    public function delete_foto()
    {
        $shipping_id = JFactory::getApplication()->input->getInt('shipping_id');
        //$jshopConfig = JSFactory::getConfig();
        $shipping = JSFactory::getTable('shippingMethod', 'jshop');
        $shipping->load($shipping_id);
        //@unlink($jshopConfig->image_payments_path . '/' . substr($shipping->image, strrpos($shipping->image, '/') + 1, strlen($shipping->image) - strrpos($shipping->image, '/')));
        $shipping->image = '';
        $shipping->store();

        die();
    }
	
    public function remove()
    {
		$cid = JFactory::getApplication()->input->getVar('cid');
		$_shippings = JSFactory::getModel("shippings");		
		$text = array();
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRemoveShipping', [&$cid]);

		foreach ($cid as $key => $value) {						
			if ($_shippings->deleteShippingById($value)) {
				$text[] = JText::_('COM_SMARTSHOP_SHIPPING_DELETED');
				$sh_pr_ids = $_shippings->getShippingPricesByShippingId($value);												
                foreach($sh_pr_ids as $value2){
					$_shippings->deletePriceWeightByShippingId($value2->sh_pr_method_id);                    
					$_shippings->deletePriceCountriesByShippingId($value2->sh_pr_method_id);
                }
				$_shippings->deleteShippingByShippingId($value);				
			} else {
				$text[] = JText::_('COM_SMARTSHOP_ERROR_SHIPPING_DELETED');
			}
		}
        
        $dispatcher->triggerEvent('onAfterRemoveShipping', [&$cid]);
		
		$this->setRedirect('index.php?option=com_jshopping&controller=shippings', implode('</li><li>', $text));
	}
	
    public function republish()
    {
		$cid = JFactory::getApplication()->input->getVar('cid');
        $flag = ($this->getTask() == 'publish') ? 1 : 0;
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforePublishShipping', [&$cid, &$flag]);
		$obj = JSFactory::getTable('shippingMethod', 'jshop');
        $obj->publish($cid, $flag);
        $dispatcher->triggerEvent('onAfterPublishShipping', [&$cid, &$flag]);
		$this->setRedirect('index.php?option=com_jshopping&controller=shippings');
	}
	
    public function reorder()
    {
        $ids = JFactory::getApplication()->input->getVar('cid', null, 'post', 'array');
        $move = ($this->getTask() == 'orderup') ? -1 : +1;
        $obj = JSFactory::getTable('shippingMethod', 'jshop');
        $obj->load($ids[0]);
        $obj->move($move);
        $this->setRedirect('index.php?option=com_jshopping&controller=shippings');
    }
    
    public function saveorder()
    {
        $pks = JFactory::getApplication()->input->getVar('cid', null, 'post', 'array');
        $order = JFactory::getApplication()->input->getVar('order', null, 'post', 'array');
        JArrayHelper::toInteger($pks);
        JArrayHelper::toInteger($order);
        $_shippings = JSFactory::getModel('shippings');
        $_shippings->saveorder($pks, $order);
        $this->setRedirect('index.php?option=com_jshopping&controller=shippings');
    }
    
}