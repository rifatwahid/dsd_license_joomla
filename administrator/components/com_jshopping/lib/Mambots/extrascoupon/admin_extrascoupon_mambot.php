<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../BackMambot.php';

class AdminExtrascouponMambot extends BackMambot
{
    protected static $instance;
    protected $max_count_try_generate_coupon = 100;
    protected $max_count_generate_coupon = 1000;
    protected $max_length_suffix = 32;
                
    public function onBeforeDisplayCoupons(&$view)
    {
		if(!isset($view->tmp_extra_column_headers)) $view->tmp_extra_column_headers = '';
		$view->tmp_extra_column_headers .= 
			'<th width="40" class="center">'.
				JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_COUPON_LIST_DESC'), 'C.coupon_desc', $view->coupon_desc ?? '', $view->filter_order).
            '</th>';
          
		foreach($view->rows as $row){
			if(!isset($row->tmp_extra_column_cells)) $row->tmp_extra_column_cells = '';		
			$row->tmp_extra_column_cells .= '<td>' . $row->coupon_desc . '</td>';
		}
	}
	
    public function onBeforeEditCoupons(&$view)
    {
        $productNameType = [
            JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_PRODUCT_NAME_EXACT')),
            JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PRODUCT_NAME_CONTAIN')),
            JHTML::_('select.option', -1, JText::_('COM_SMARTSHOP_NONE'))
        ];

        $languages = JSFactory::getModel('languages')->getAllLanguages(1);

        if (!$view->etemplatevar) {
            $view->etemplatevar = '';
        }

        $pathToFolderWithTmpls = JPATH_JOOMSHOPPING_ADMIN . '/views/tmpls_elements/extrascoupon/';
        $view->etemplatevar .= renderTemplate([$pathToFolderWithTmpls], 'edit_coupon_params', [
            'view' => $view,
            'productNameType' => $productNameType,
            'languages' => $languages
        ]);
    }

    public function onBeforeSaveCoupon(&$coupon)
    {
        if (isset($coupon['for_product_name_type']) && $coupon['for_product_name_type'] == -1) {
            foreach($coupon as $key => $val) {
                if ($key != 'for_product_name_type' && strpos($key, 'for_product_name') !== false) {
                    $coupon[$key] = '';
                }
            }
        }

        $this->setDefaultValsForEmptyCouponParams($coupon);
    }
    
    public function onAfterSaveCoupon($coupon)
    {
        $app = JFactory::getApplication();
        $post = JFactory::getApplication()->input->post->getArray();
        $urlToEditCoupons = 'index.php?option=com_jshopping&controller=coupons&task=edit';
        
        $post['coupon_code'] = JFactory::getApplication()->input->getVar('coupon_code');
        $post['coupon_publish'] = JFactory::getApplication()->input->getInt('coupon_publish');
        $post['finished_after_used'] = JFactory::getApplication()->input->getInt('finished_after_used');
        $post['coupon_value'] = saveAsPrice($post['coupon_value']);
        
        $this->setDefaultValsForEmptyCouponParams($post);
        $this->createCouponForEachUser($coupon);
        
        if (!$post['auto_coupons_count']) {
            return;
        }
        
        $coupon->delete(); 

        if ($post['auto_coupons_count'] > $this->max_count_generate_coupon) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX_COUNT'),'error');
            $app->redirect($urlToEditCoupons);
            return;
        }

        if (!is_numeric($post['auto_coupons_count'])) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_IS_INT_COUNT'),'error');
            $app->redirect($urlToEditCoupons);
            return;
        }

        if (!is_numeric($post['auto_coupons_length'])) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_IS_INT_LENGTH'),'error');
            $app->redirect($urlToEditCoupons);
            return;
        }

        if ($post['auto_coupons_length'] > $this->max_length_suffix) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX_LENGTH_SUFFIX'),'error'); 
            $app->redirect($urlToEditCoupons);  
            return;
        }
        
        if ($post['auto_coupons_length'] <= 0) {
            $post['auto_coupons_length'] = 1;
        }
        
        $db = \JFactory::getDBO();
        $query = "SELECT `coupon_code` FROM `#__jshopping_coupons`";
        $db->setQuery($query);
        $coupons = $db->loadResultArray();
        
        for ($i = 1; $i <= $post['auto_coupons_count']; $i++) {
            $key = $this->getCouponCode($post['coupon_code'], $post['auto_coupons_length'], $i, $post['only_numbers']);
            
            $j = 0;
            while(in_array($key, $coupons)) {
                $j++;
                $key = $this->getCouponCode($post['coupon_code'], $post['auto_coupons_length'], rand(1001, 5000000), $post['only_numbers']);
                
                if ($j > $this->max_count_try_generate_coupon) {
                    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX'),'error');
                    break(2);
                }
            }
            
            $coupons[] = $key;
            
            $table = JTable::getInstance('coupon', 'jshop');
            $table->bind($post);
            $table->coupon_id = 0;
            $table->coupon_code = $key;
            $table->store();            
        }
        
        $app->redirect('index.php?option=com_jshopping&controller=coupons', JText::_('COM_SMARTSHOP_COMPLETED'));
    }

    protected function setDefaultValsForEmptyCouponParams(&$coupon) 
    {
        if ($coupon['once_for_each_user'] || $coupon['limited_use']) {
            $coupon['finished_after_used'] = 0;
        }

        if (!$coupon['once_for_each_user']) {
            $coupon['once_for_each_user'] = 0;
        }

        if (!$coupon['limited_use']) {
            $coupon['limited_use'] = 0;
        }

        if (!$coupon['not_use_for_product_with_old_price']) {
            $coupon['not_use_for_product_with_old_price'] = 0;
        }
        
        if (!$coupon['free_shipping']) {
            $coupon['free_shipping'] = 0;
        }

        if (!$coupon['free_payment']) {
            $coupon['free_payment'] = 0;
        }

        if (!$coupon['only_numbers']) {
            $coupon['only_numbers'] = 0;
        }
    }
    
    protected function getAllEnabledUsers() 
    {
        $db = \JFactory::getDBO();
        $query = " SELECT u.id"
                ." FROM #__jshopping_users AS ju"
                ." INNER JOIN #__users AS u ON ju.user_id = u.id"
                ." WHERE u.block = 0";
        
        $db->setQuery($query);
        $userList = $db->loadObjectList();
        
        return $userList;
    }
    
    protected function createCouponForEachUser(&$coupon)
    {
        $db = \JFactory::getDBO();
        $app = JFactory::getApplication();
        $post = JFactory::getApplication()->input->post->getArray();
        $urlToEditCoupons = 'index.php?option=com_jshopping&controller=coupons&task=edit';
        $raiseWarningText = '';

        if (!isset($post['only_numbers'])) {
            $post['only_numbers'] = 0;
        }
        
        if (!$post["create_for_each_user"]) {
            return NULL;
        }
        
        $coupon->delete();
        
        $userList = $this->getAllEnabledUsers();
        $countUsers = count($userList);

        if ($countUsers <= 0) {
            return NULL;
        }
        
        if ($countUsers > $this->max_count_generate_coupon && empty($raiseWarningText)) {
            $raiseWarningText = JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX_COUNT');
        }

        if (!is_numeric($post['auto_coupons_length']) && empty($raiseWarningText)) {
            $raiseWarningText = JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_IS_INT_LENGTH');
        }

        if ($post['auto_coupons_length'] > $this->max_length_suffix && empty($raiseWarningText)) {
            $raiseWarningText = JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX_LENGTH_SUFFIX');
        }

        if (!empty($raiseWarningText)) {
            \JFactory::getApplication()->enqueueMessage($raiseWarningText,'error'); 
            $app->redirect($urlToEditCoupons);  
            return;
        }
        
        if ($post['auto_coupons_length'] <= 0) {
            $post['auto_coupons_length'] = 1;
        }
        
        $query = "SELECT `coupon_code` FROM `#__jshopping_coupons`";
        $db->setQuery($query);
        $coupons = $db->loadResultArray();

        for ($i = 1; $i <= $countUsers; $i++) {
            $post['for_user_id'] = $userList[$i-1]->id;            
            $key = $this->getCouponCode($post['for_user_id'], $post['auto_coupons_length'], $post['only_numbers']);
            
            $j = 0;
            while(in_array($key, $coupons)) {
                $j++;
                $key = $this->getCouponCode($post['coupon_code'], $post['auto_coupons_length'], rand(1001, 5000000), $post['only_numbers']);

                if ($j > $this->max_count_try_generate_coupon) {
                    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AUTO_COUPON_MAXX'),'errror');
                    break(2);
                }
            }
            
            $coupons[] = $key;
            
            $table = JTable::getInstance('coupon', 'jshop');
            $table->bind($post);
            $table->coupon_id = 0;
            $table->coupon_code = $key;
            $table->store();
        }
        
        $app->redirect('index.php?option=com_jshopping&controller=coupons', JText::_('COM_SMARTSHOP_COMPLETED'));
    }
    
    protected function getCouponCode($couponCode, $autoCouponsLength, $number = '', $onlyNumbers = 0)
    {
        $code = md5($couponCode . $number);
        
        if ($onlyNumbers) {
            $code = $this->md5HexToDec($code);
        }
        
        $suff = substr($code, 0, $autoCouponsLength);
        
        return $couponCode . $suff;
    }
    
    protected function md5HexToDec($hex_str) 
    {
        $arr = str_split($hex_str, 4);
        
        foreach ($arr as $grp) {
            $dec[] = str_pad(hexdec($grp), 4, '0');
        }

        return implode('', $dec);
    }
}