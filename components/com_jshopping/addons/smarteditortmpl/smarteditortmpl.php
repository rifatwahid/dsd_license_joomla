<?php
include_once JPATH_SITE."/components/com_jshopping/addons/addon_core.php";

class SmarteditorTmpl extends AddonCore{
        
    protected $addon_alias = 'smarteditortmpl';
    
    public function getView($layout = 'default'){
        $jshopConfig = JSFactory::getConfig();		
		$path = JPATH_SITE."/components/com_jshopping";
        include_once($path."/views/addons/view.html.php");
        $addon_tmpl_path = $path."/templates/addons/".$this->addon_alias;
        $addon_tmpl_js_path = $path."/templates/".$jshopConfig->template."/".$this->addon_alias;
        if (file_exists($addon_tmpl_js_path."/".$layout.".php")){
            $addon_tmpl_path = $addon_tmpl_js_path;
        }
        $view_config = array("template_path"=>$addon_tmpl_path);
        $view = new JshoppingViewAddons($view_config);
        if ($layout){
            $view->setLayout($layout);
        }
        $view->set('addon_path_images', $this->getPathImages());
        return $view;
    }
    
    
    public function getHtmlEditorButtonInCategory($category_id){
        $editorFromCat = $this->getEditorFromCategory($category_id);        
        $editor = $this->getEditorData($editorFromCat->editor_id);
        if ($editor){
            $content = $this->getEditorContent($editor->editor_id);           
            $link = $this->getUrlEditor($editor);
            
            $view = $this->getView('editor_in_category');
            $view->set('category_id', $category_id);
            $view->set('editorFromCat', $editorFromCat);
            $view->set('editor', $editor);
            $view->set('content', $content);
            $view->set('link', $link);
            
            return $view->loadTemplate();
        }else{
            return '';
        }
    }
    
    public function getEditorFromCategory($category_id){
        $db = \JFactory::getDBO();
        $db->setQuery('SELECT * FROM #__ee_editors_to_categories WHERE category_id='.(int)$category_id);
        return $db->loadObject();
    }
    
    public function getEditorData($editor_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM #__ee_editors as ed '
                . 'LEFT JOIN #__ee_editors_types as et ON ed.editor_type=et.id '
                . 'WHERE ed.editor_id='.intval($editor_id).' '
                . 'ORDER BY ed.editor_id ASC';
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    public function getProductData($product_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM #__jshopping_products where product_id='.(int)$product_id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    public function getEditorContent($editor_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM #__ee_editors_content WHERE editor_id='.(int)$editor_id;
        $db->setQuery($query);
        return $db->loadObject();        
    }
    
    public function getUrlEditor($editor, $params = array()){
        $link = 'index.php?option=com_expresseditor&controller=expresseditor&task=editor&xmlname=' . 
                $editor->editor_id . '_' . $editor->source_xml . '&product=' . $editor->flash_name . '#editor';
        return $link;
    }
    
    public function getProductEditorId($product_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT editor_id FROM #__jshopping_products where product_id='.(int)$product_id;
        $db->setQuery($query);
		return $db->loadResult();
    }
    
    public function getCartShortDescrProduct($product_id, $tmpl = ''){
        $jshopConfig = JSFactory::getConfig();                                    
        $db = \JFactory::getDBO();
        $lang = JFactory::getLanguage();
        $query = 'SELECT `short_description_'.$lang->getTag().'` as short_description FROM #__jshopping_products '
                . 'where product_id='.(int)$product_id.' AND editor_id>0';
        $db->setQuery($query);
        $short_description = $db->loadResult();
        $prices = explode('<br>', $short_description);
        $view = $this->getView('cart_short_descr_product'.$tmpl);
        $view->set('prices', $prices);
        $view->set('config', $jshopConfig);
        return $view->loadTemplate();
    }
    
    public function getEditorLinkHtmlFromProduct($product_id, $params = array()){        
        $link = $this->getEditorLinkFromProduct($product_id, 1, $params['product']);
        if ($link==''){
            return '';
        }
        if ($params['page']=='list'){
            $tmpl = 'list_products_editor_link';
        }else{
            $tmpl = 'product_editor_link';
        }
        $view = $this->getView($tmpl);
        $view->set('link', $link);        
        return $view->loadTemplate();
    }
    
    public function getEditorLinkFromProduct($product_id, $route = 1, $product = null){
        $ee = $this->getEditorToProduct($product_id);
        $jprod_ee = $this->getEditorSmDataProduct($product_id);
        
        $smart_link = '';
        if (($ee->editor_id > 0) AND (($ee->open_type == 0)OR($ee->open_type == 3))) {
            $smart_link = "index.php?option=com_expresseditor&task=editor&editor_id={$ee->editor_id}&epp_id={$ee->epp_id}&product_id={$product_id}";            
        } else if (($jprod_ee->editor_id > 0) AND ($jprod_ee->product_type_view == 0)) {
            $smart_link = "index.php?option=com_expresseditor&task=editor&editor_id={$jprod_ee->editor_id}&product_id={$product_id}";
        }        
        if ($smart_link!='' && !is_null($product)){
            $smart_link .= "&product={$product}";
        }
        if ($route && $smart_link!=''){
            $smart_link = JRoute::_($smart_link);
        }
        return $smart_link;
    }
    
    public function getHtmlOrderItemButtons($orderitem){
        $product_id = $orderitem->product_id;
        $category_id = $this->getFirstCategoryIdFromProductId($product_id);
        $product = $this->getProductData($product_id);        
        
        $view = $this->getView('user_order_items_end');
        $view->set('product_id', $product_id);        
        $view->set('category_id', $category_id);        
        $view->set('product', $product);        
        $view->set('orderitem', $orderitem);        
        return $view->loadTemplate();
    }
    
    public function loadDeliveryUserData($user){
        $lang = JSFactory::getLang();
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($user->d_country);
        $field_name = $lang->get("name");
        $user->d_country = $country->$field_name;
        if (!$user->delivery_adress){
            $user->d_f_name = $user->f_name;
            $user->d_l_name = $user->l_name;
            $user->d_firma_name = $user->firma_name;
            $user->d_street = $user->street;
            $user->d_city = $user->city;
            $user->d_zip = $user->zip;
            $user->d_state = $user->state;
            $user->d_country = $user->country;
        }
    }
    
    public function getExtraLinksInMyAccount(){
        return $this->getView('user_my_account')->loadTemplate();
    }


    protected function getEditorToProduct($product_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT `editor_id`, `open_type`, `enable`, `product_id`, `epp_id` FROM #__ee_editors_to_products '
                . 'WHERE enable=1 AND product_id='.(int)$product_id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    protected function getEditorSmDataProduct($product_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT `editor_id`, `product_type_view` FROM #__jshopping_products WHERE product_id='.(int)$product_id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    protected function getFirstCategoryIdFromProductId($product_id){
        $db = \JFactory::getDBO();
        $query = 'select category_id from #__jshopping_products_to_categories WHERE product_id='.(int)$product_id;
        $db->setQuery($query);
        return $db->loadResult();
    }
            
}