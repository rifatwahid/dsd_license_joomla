<?php
/**
* @version      4.6.1 31.07.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

use Joomla\CMS\Language\Text;

class JshoppingControllerReviews extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("reviews");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    function custom_options($cachable = false, $urlparams = false) {
        $_reviews = JSFactory::getModel("reviews");
		$view=$this->getView("comments", 'html');
        $view->setLayout("configurations");
		$view->set('canDo', $canDo ?? '');
		$jshopConfig = JSFactory::getConfig();
        $view->set('config', $jshopConfig);
        $view->set('select', $_reviews->selectReviewStars($jshopConfig->rating_starparts));
        $view->displayConfigurations();
	}
	function configurations_apply($cachable = false, $urlparams = false) {
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$post = $this->input->post->getArray();
	
		$array = array('allow_reviews_uploads','max_mark','allow_reviews_prod', 'allow_reviews_only_registered', 'allow_reviews_only_buyers','hide_text_product_not_available','sendmail_reviews_admin_email','sendmail_reviews_admin_email_all_reviews','sendmail_reviews_admin_email_require_confirmation','sendmail_reviews_admin_email_from_guests', 'display_reviews_without_confirm');	
		foreach ($array as $key => $value) {
			if (!isset($post[$value])) $post[$value] = 0;
        }
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
        }
        $result['rating_starparts'] = $post['rating_starparts'];
        $post['other_config'] = serialize($result);
		
		$config = new jshopConfig($db);
		$config->id = $jshopConfig->load_id;
		if (!$config->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=reviews');
			return 0;
		}		
		if (!$config->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=reviews');
			return 0;
        }
		$this->setRedirect('index.php?option=com_jshopping&controller=reviews',JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
	
    function display($cachable = false, $urlparams = false) {
        $mainframe = JFactory::getApplication();
		$dispatcher = \JFactory::getApplication();        	        
        $_reviews = JSFactory::getModel("reviews");
        $_products = JSFactory::getModel("products");
        $context = "jshoping.list.admin.reviews";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $category_id = $mainframe->getUserStateFromRequest( $context.'category_id', 'category_id', 0, 'int' );            
        $text_search = $mainframe->getUserStateFromRequest( $context.'text_search', 'text_search', '');
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "pr_rew.review_id", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "desc", 'cmd');
        
        if ($category_id){
            $product_id = $mainframe->getUserStateFromRequest( $context.'product_id', 'product_id', 0, 'int' );
        } else {
            $product_id = null;
        }
        
        $products_select = "";
        
        if ($category_id){
            $prod_filter = array("category_id"=>$category_id);            
			$dispatcher->triggerEvent('onBeforeDisplayReviewsBeforeGetAllProducts', array(&$prod_filter));
            $products = $_products->getAllProducts($prod_filter, 0, 100);
            if (count($products)) {
                $start_pr_option = JHTML::_('select.option', '0', JText::_('COM_SMARTSHOP_SELECT_PRODUCT') , 'product_id', 'name');
                array_unshift($products, $start_pr_option);   
                $products_select = JHTML::_('select.genericlist', $products, 'product_id', 'class = "inputbox form-select" onchange="document.adminForm.submit();" size = "1" ', 'product_id', 'name', $product_id);
            }
        }
        
        $total = $_reviews->getAllReviews($category_id, $product_id, NULL, NULL, $text_search, "count", $filter_order, $filter_order_Dir);
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
    
        $reviews = $_reviews->getAllReviews($category_id, $product_id, $pagination->limitstart, $pagination->limit, $text_search, "list", $filter_order, $filter_order_Dir);
        
        $start_option = JHTML::_('select.option', '0', JText::_('COM_SMARTSHOP_SELECT_CATEGORY'),'category_id','name'); 
        
        $categories_select = buildTreeCategory(0,1,0);
        array_unshift($categories_select, $start_option);
        
        $categories = JHTML::_('select.genericlist', $categories_select, 'category_id', 'class = "inputbox form-select" onchange="document.adminForm.submit();" size = "1" ', 'category_id', 'name', $category_id);
        $view=$this->getView("comments", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('categories', $categories);
        $view->set('reviews', $reviews); 
        $view->set('limit', $limit);
        $view->set('limitstart', $limitstart);
        $view->set('text_search', $text_search); 
        $view->set('pagination', $pagination); 
        $view->set('products_select', $products_select);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayReviews', array(&$view));		
        $view->displayList();
     }
     
     function remove(){
        $_reviews = JSFactory::getModel("reviews");
        $cid = JFactory::getApplication()->input->getVar('cid');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRemoveReview', array(&$cid) );
        
        foreach($cid as $key => $value) {
             $review = JSFactory::getTable('review', 'jshop');
             $review->load($value);
             $_reviews->deleteReview($value);
             $product = JSFactory::getTable('product', 'jshop');
             $product->load($review->product_id);
             $product->loadAverageRating();
             $product->loadReviewsCount();
             $product->store();
             unset($product);
             unset($review);
        }
        $dispatcher->triggerEvent('onAfterRemoveReview', array(&$cid));
        $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
     }
     
     function edit(){
        $mainframe = JFactory::getApplication();
        $_reviews = JSFactory::getModel("reviews");
        $cid = JFactory::getApplication()->input->getVar('cid');
        $review = $_reviews->getReview($cid[0] ?? '');
         
        $jshopConfig = JSFactory::getConfig();
        $options = array();
        $options[] = JHTML::_('select.option', 0, 'none','value','text');
        for($i=1;$i<=$jshopConfig->max_mark;$i++){
            $options[] = JHTML::_('select.option', $i, $i,'value','text'); 
        }
        
        $mark = JHTML::_('select.genericlist', $options, 'mark', 'class = "inputbox form-select" size = "1" ', 'value', 'text', $review->mark ?? ''); 
        JFilterOutput::objectHTMLSafe($review, ENT_QUOTES);
        
        $view=$this->getView("comments", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        if ($this->getTask()=='edit'){
            $view->set('edit', 1);
        }
        $view->set('review', $review); 
        $view->set('mark', $mark);
        $view->set('etemplatevar', '');
        $view->set('config', $jshopConfig);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditReviews', array(&$view));
        $view->displayEdit();
     }

    public function ajaxDeleteAttachedFile()
    {
        $answer = [
            'isDeleted' => false,
            'messages' => []
        ];
        $fileName = $this->input->getVar('fileName');
        $reviewId = $this->input->getInt('id');

        if (!empty($fileName) && !empty($reviewId)) {
            JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/com_jshopping/models');
            $modelAdminOfReviews = JSFactory::getModel('reviews');
            $answer['isDeleted'] = $modelAdminOfReviews->deleteAttachedFile($reviewId, $fileName);
            $answer['messages'][] = ($answer['isDeleted'] == true) ? Text::_('COM_SMARTSHOP_ATTACHED_FILES_SUCCESSFULLY_DELETED') : Text::_('COM_SMARTSHOP_ATTACHED_FILES_UNSUCCESSFULLY_DELETED');
        }

        echo json_encode($answer);
        die;
    }
     
     function save(){
        $review = JSFactory::getTable('review', 'jshop');
        $post = JFactory::getApplication()->input->post->getArray();
        if (intval($post['review_id'])==0) $post['time'] = getJsDate();
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveReview', array(&$post) );

        if (!$post['product_id']){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_DATA'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
            return 0;
        }

        if (!$review->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
            return 0;
        }
        if (!$review->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=reviews&task=edit&cid[]=".$review->review_id);
            return 0;
        }
        
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($review->product_id);
        $product->loadAverageRating();
        $product->loadReviewsCount();
        $product->store();
        
        $dispatcher->triggerEvent( 'onAfterSaveReview', array(&$review) );
        
        if ($this->getTask()=='apply')
            $this->setRedirect("index.php?option=com_jshopping&controller=reviews&task=edit&cid[]=".$review->review_id);             
        else 
            $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
    }
     
    function publish(){
        $this->_publish(1);
        $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
    }
    
    function unpublish(){
        $this->_publish(0);
        $this->setRedirect("index.php?option=com_jshopping&controller=reviews");
    }    
    
    function _publish($flag) {
        $jshopConfig = JSFactory::getConfig();
		$_reviews = JSFactory::getModel('reviews');        
        $cid = JFactory::getApplication()->input->getVar('cid');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishReview', array(&$cid, &$flag) );
        foreach ($cid as $key => $value) {
			$_reviews->setPublishById($flag,$value);
			$review = JSFactory::getTable('review', 'jshop');
            $review->load($value);
            $product = JSFactory::getTable('product', 'jshop');
            $product->load($review->product_id);
            $product->loadAverageRating();
            $product->loadReviewsCount();
            $product->store();
            unset($product);
            unset($review);
        }
        
        $dispatcher->triggerEvent('onAfterPublishReview', array(&$cid, &$flag) );
    }
}
?>