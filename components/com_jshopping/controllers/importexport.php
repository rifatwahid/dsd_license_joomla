<?php
/**
* @version      4.1.0 10.10.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

include_once JPATH_COMPONENT_ADMINISTRATOR . '/importexport/iecontroller.php';

class JshoppingControllerImportExport extends JshoppingControllerBase
{
    
    public function display($cachable = false, $urlparams = false)
    {
        throw new Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'),404);
    }

    public function start()
    {
        $jshopConfig = JSFactory::getConfig();
        $key = JFactory::getApplication()->input->getVar('key');

        if ($key != $jshopConfig->securitykey) {
            die();
        }
        
        $_GET['noredirect'] = 1; 
        $_POST['noredirect'] = 1; 
        $_REQUEST['noredirect'] = 1;

        $importExportModel = JSFactory::getModel('importExportFront', 'jshop');
        $listOfImportExport = $importExportModel->getDataByLatestStartLessCurrentTime();

        foreach($listOfImportExport as $ie) {
            $alias = $ie->alias;
            $pathToImportExportFile = JPATH_COMPONENT_ADMINISTRATOR . '/importexport/' . $alias . '/' . $alias . '.php';

            if (!file_exists($pathToImportExportFile)){
                echo JText::sprintf('COM_SMARTSHOP_ERROR_FILE_NOT_EXIST', $pathToImportExportFile);
                return 0;
            }

            include_once $pathToImportExportFile;
            JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');

            $classname = 'Ie' . $alias;
            $controller = new $classname($ie->id);
            $controller->set('ie_id', $ie->id);
            $controller->set('alias', $alias);
            $controller->save();

            echo $alias . "\n";
        }
        
        die();
    }
}