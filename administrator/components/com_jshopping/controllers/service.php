<?php
/**
* @version      4.9.0 24.07.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerService extends JControllerLegacy
{    
    public function __construct($config = []) 
    {
        parent::__construct($config);
    }

    public function redirectBackWithMsg()
    {
        $jinput = JFactory::getApplication()->input;
        $msgText = $jinput->get('msg', '', 'string');
        $msgType = ucfirst($jinput->get('msgType', 'Message'));

        JFactory::getApplication()->redirect($_SERVER['HTTP_REFERER'], $msgText, $msgType);
        die;
    }

    public function redirectToShopDocumentation()
    {
        $shopConfig = JSFactory::getConfig();

        if (!empty($shopConfig->link_to_shop_documentation)) {
            return JFactory::getApplication()->redirect($shopConfig->link_to_shop_documentation);
        }

        $this->redirectBackWithMsg();
    }
}