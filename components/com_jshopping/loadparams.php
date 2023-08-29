<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/extrascoupon/checkout_extrascoupon_mambot.php';

$session =JFactory::getSession();
$ajax = JFactory::getApplication()->input->getInt('ajax');

if (!JFactory::getApplication()->input->getInt('no_lang')){
    JSFactory::loadLanguageFile();
}
$jshopConfig = JSFactory::getConfig();
setPrevSelLang($jshopConfig->getLang());

//reload price for new currency
if (JFactory::getApplication()->input->getInt('id_currency')){
    header("Cache-Control: no-cache, must-revalidate");
    updateAllprices();
    $back = JFactory::getApplication()->input->getVar('back');
    $mainframe =JFactory::getApplication();
    if ($back!='') $mainframe->redirect($back);
}

$user = JFactory::getUser();

$js_update_all_price = $session->get('js_update_all_price');
$js_prev_user_id = $session->get('js_prev_user_id');
if ($js_update_all_price || ($js_prev_user_id!=$user->id)){
    updateAllprices();
    $session->set('js_update_all_price', 0);
}
$session->set("js_prev_user_id", $user->id);

if (!$ajax){
    installNewLanguages();
    $document = JFactory::getDocument();
    $viewType = $document->getType();
    if ($viewType=="html"){
        JSFactory::loadCssFiles();
        JSFactory::loadJsFiles();
    }
}else{
    //header for ajax
    header('Content-Type: text/html;charset=UTF-8');
}
$dispatcher = \JFactory::getApplication();
$dispatcher->triggerEvent('onAfterLoadShopParams', []);
CheckoutExtrascouponMambot::getInstance()->onAfterLoadShopParams([]);
loadJSLanguageKeys();
?>