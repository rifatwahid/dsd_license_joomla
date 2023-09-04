<?php
/**
* @version      4.7.0 10.10.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerWishlist extends JshoppingControllerBase
{
    
    public function __construct($config = array())
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingcheckout');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerWishlist', [&$currentObj]);
		setSeoMetaData();
    }

    public function display($cachable = false, $urlparams = false)
    {
        $this->view();
    }

    public function view()
    {		
		checkUserLogin();
        $document = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
	    $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $params = $mainframe->getParams();
        $ajax = JFactory::getApplication()->input->getInt('ajax');

		$cart = JSFactory::getModel('cart', 'jshop');
		$cart->load('wishlist');
		$cart->addLinkToProducts(1, 'wishlist');
        $cart->setDisplayFreeAttributes();
        $cart->setDisplayData();

        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('wishlist');
        if (getThisURLMainPageShop()) {
            appendPathWay(JText::_('COM_SMARTSHOP_WISHLIST'));
            if (!isset($seodata->title) || $seodata->title == '') {
                $seodata->title = JText::_('COM_SMARTSHOP_WISHLIST');
            }         
        }
		setSeoMetaData($seodata->title ?? '');

        $shopurl = SEFLink('index.php?option=com_jshopping&controller=category', 1);
        if ($jshopConfig->cart_back_to_shop == 'product') {
            $endpagebuyproduct = xhtmlUrl($session->get('jshop_end_page_buy_product'));
        } elseif ($jshopConfig->cart_back_to_shop == 'list') {
            $endpagebuyproduct = xhtmlUrl($session->get('jshop_end_page_list_product'));
        }

        if (isset($endpagebuyproduct) && $endpagebuyproduct) {
            $shopurl = $endpagebuyproduct;
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeDisplayWishlist', [&$cart]);
        loadJSLanguageKeys();

        $view = $this->getView('cart', getDocumentType(), '', [
            'template_path' => viewOverride('cart', 'wishlist.php')
        ]);

        $layout = getLayoutName('cart', 'wishlist');
        $view->setLayout($layout);
        $view->set('component', 'Wishlist');

		$view->set('config', $jshopConfig);
		$view->set('products', $cart->products);
		//print_r($cart->products);die;
		$view->set('image_product_path', $jshopConfig->image_product_live_path);
		$view->set('image_path', $jshopConfig->live_path);
		$view->set('no_image', $jshopConfig->noimage);
		$view->set('href_shop', $shopurl);
		$view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $dispatcher->triggerEvent('onBeforeDisplayWishlistView', [&$view]);
        $view->set('isUrl_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=isUrl', 1));
        $view->set('price_format_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=getPriceFormat', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('href_to_cart', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));

        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
		
		if($ajax){ print json_encode(prepareView($view));die; }
		
        $view->display();
        
        if ($ajax) {
            die();
        }
    }

    public function delete()
    {
        header('Cache-Control: no-cache, must-revalidate');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $rajax = JFactory::getApplication()->input->getInt('rajax');
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('wishlist');    
        $cart->delete(JFactory::getApplication()->input->getInt('number_id'));
//print_r($rajax);die;
        if ($rajax) {
            echo print_r(1);
            die();
        }
        if ($ajax) {
            echo getOkMessageJson($cart);
            die();
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 0, 1));
    }

    public function remove_to_cart()
    {
        header('Cache-Control: no-cache, must-revalidate');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $rajax = JFactory::getApplication()->input->getInt('rajax');
        $number_id = JFactory::getApplication()->input->getInt('number_id');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadWishlistRemoveToCart', [&$number_id]);

        $additional_fields = [];
        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('wishlist');
        $prod = $cart->products[$number_id];
        $attr = unserialize($prod['attributes']);
        $freeattribut = unserialize($prod['freeattributes']);
                        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('cart');       
		if($cart->add($prod['product_id'], $prod['quantity'], $attr, $freeattribut, $additional_fields, $usetriggers, $errors, $displayErrorMessage)){
			$cart = JSFactory::getModel('cart', 'jshop');
			$cart->load('wishlist');
		}
        $dispatcher->triggerEvent('onAfterWishlistRemoveToCart', [&$cart]);

        if ($ajax) {
            echo getOkMessageJson($cart);
            die();
        }
        if ($rajax) {
            echo 1;
            die();
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1, 1));
    }
}