<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../BackMambot.php';

class AdminOfferAndOrder extends BackMambot 
{
    protected static $instance;

    public function onBeforeEditAddons(&$view)
    {
        $this->includeDocumentFilesForAddonParams($view);
    }

    protected function includeDocumentFilesForAddonParams(&$params)
    {
        if ( 'addon_offer_and_order' == $params->row->alias ) {
            $doc = JFactory::getDocument();
            $doc->addStyleSheet(JUri::root() . '/components/com_jshopping/css/offer_and_order.css');            
        }        
    }
}
