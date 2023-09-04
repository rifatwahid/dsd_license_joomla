<?php
/**
* @version      4.7.0 24.07.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerContent extends JshoppingControllerBase
{
    
    public function __construct($config = [])
    {
        parent::__construct($config);        
		setSeoMetaData();
    }
    
    public function display($cachable = false, $urlparams = false)
    {
        throw new Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'),404);
    }

    public function view()
    {
        $page = JFactory::getApplication()->input->getVar('page');
        $contentFrontModel = JSFactory::getModel('contentFront', 'jshop');
        $contentLink = $contentFrontModel->getContentLink($page);	
        	
		$this->setRedirect($contentLink);
    }
}