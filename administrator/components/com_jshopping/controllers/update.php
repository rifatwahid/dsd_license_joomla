<?php
/**
* @version      4.7.0 20.12.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

class JshoppingControllerUpdate extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        $mainframe = JFactory::getApplication();
        parent::__construct( $config );
        checkAccessController("update");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
        addSubmenu("update",$this->canDo);
        $language = JFactory::getLanguage(); 
        $language->load('com_installer');
    }

    function display($cachable = false, $urlparams = false){		                
		$view=$this->getView("update", 'html');  
		$view->set("canDo", $this->canDo);
        $view->set('etemplatevar1', '');
        $view->set('etemplatevar2', '');
		$view->display(); 
    }

	function update() {       
        $installtype = JFactory::getApplication()->input->getVar('installtype');
        $jshopConfig = JSFactory::getConfig();
        $back = JFactory::getApplication()->input->getVar('back');

        if (!extension_loaded('zlib')){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLZLIB'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=update");
            return false;
        }
        
        if ($installtype == 'package'){
            $userfile = JFactory::getApplication()->input->getVar('install_package', null, 'files', 'array' );
            if (!(bool) ini_get('file_uploads')) {
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLFILE'),'error');
                $this->setRedirect("index.php?option=com_jshopping&controller=update");
                return false;
            }
            if (!is_array($userfile) ) {
                \JFactory::getApplication()->enqueueMessage(JText::_('No file selected'),'error');
                $this->setRedirect("index.php?option=com_jshopping&controller=update");
                return false;
            }
            if ( $userfile['error'] || $userfile['size'] < 1 ){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLUPLOADERROR'),'error');
                $this->setRedirect("index.php?option=com_jshopping&controller=update");
                return false;
            }
            $config = JFactory::getConfig();            
            $tmp_dest = $config->get('tmp_path').'/'.$userfile['name'];            
            $tmp_src = $userfile['tmp_name'];
            jimport('joomla.filesystem.file');
            $uploaded = JFile::upload($tmp_src, $tmp_dest, false, true);
            $archivename = $tmp_dest;            
            $tmpdir = uniqid('install_');
            $extractdir = JPath::clean(dirname($archivename).'/'.$tmpdir);
            $archivename = JPath::clean($archivename);        
        }else {
            jimport('joomla.installer.helper');
            $url = JFactory::getApplication()->input->getVar('install_url');
            if (preg_match('/(sm\d+):(.*)/',$url, $matches)){
                $url = $jshopConfig->updates_server[$matches[1]]."/".$matches[2];
            }
            if (!$url) {
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'),'error');
                $this->setRedirect("index.php?option=com_jshopping&controller=update");
                return false;
            }
            $p_file = JInstallerHelper::downloadPackage($url);
            if (!$p_file) {
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'),'error');
                $this->setRedirect("index.php?option=com_jshopping&controller=update");
                return false;
            }
            $config = JFactory::getConfig();
            $tmp_dest = $config->get('tmp_path');
            $tmpdir = uniqid('install_');
            $extractdir = JPath::clean(dirname(JPATH_BASE).'/tmp/'.$tmpdir);
            $archivename = JPath::clean($tmp_dest.'/'.$p_file);              
        }
        saveToLog("install.log", "\nStart install: ".$archivename);
        $result = JArchive::extract($archivename, $extractdir);
        if ( $result === false ) {
            \JFactory::getApplication()->enqueueMessage("Archive error",'error');
            saveToLog("install.log", "Archive error");
            $this->setRedirect("index.php?option=com_jshopping&controller=update");
            return false;
        }
        
        if (file_exists($extractdir."/checkupdate.php")) include($extractdir."/checkupdate.php");                        
        if (file_exists($extractdir."/configupdate.php")) include($extractdir."/configupdate.php");
        
        if (isset($configupdate['version']) && !$this->checkVersionUpdate($configupdate['version'])){
            $this->setRedirect("index.php?option=com_jshopping&controller=update"); 
            return 0;
        }
        
        if (!$this->copyFiles($extractdir)){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_INSTALL_THROUGH_JOOMLA'),'error');
            saveToLog("install.log", 'INSTALL_THROUGH_JOOMLA');
            $this->setRedirect("index.php?option=com_jshopping&controller=update"); 
            return 0;
        }
		
        if (file_exists($extractdir."/update.sql")){
            $db = \JFactory::getDBO();
            $lines = file($extractdir."/update.sql");
            $fullline = implode(" ", $lines);
            $queryes = $db->splitSql($fullline);            
            foreach($queryes as $query){
                if (trim($query)!=''){
                    $db->setQuery($query);
                    $db->execute();
                    if ($db->getErrorNum()) {
                        \JFactory::getApplication()->enqueueMessage($db->stderr(),'error');
                        saveToLog("install.log", "Update - ".$db->stderr());
                    }
                }
            }            
        }
        
        if (file_exists($extractdir."/update.php")) include($extractdir."/update.php");
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onAfterUpdateShop', array(&$extractdir) );
                
        @unlink($archivename);
		JFolder::delete($extractdir);
        
        $session = JFactory::getSession();
        $checkedlanguage = array();
        $session->set("jshop_checked_language", $checkedlanguage);        
        
        $msg = JText::_('COM_SMARTSHOP_COMPLETED');
        if (isset($configupdate['MASSAGE_COMPLETED'])){
            $msg = $configupdate['MASSAGE_COMPLETED'];
        }
        if ($back==''){
            $this->setRedirect("index.php?option=com_jshopping&controller=update", $msg); 
        }else{
            $this->setRedirect($back, $msg);
        }
    }
    
    function copyFiles($startdir, $subdir = ""){
        
        if ($subdir!="" && !file_exists(JPATH_ROOT.$subdir)){
            @mkdir(JPATH_ROOT.$subdir, 0755);
        }
        
        $files = JFolder::files($startdir.$subdir, '', false, false, array(), array());
        foreach($files as $file){
            if ($subdir=="" && ($file=="update.sql" || $file=="update.php" || $file=="checkupdate.php" || $file=="configupdate.php")){
                continue;
            }
            if ($subdir==""){
                $fileinfo = pathinfo($file);
                if (strtolower($fileinfo['extension'])=='xml'){
                    return 0;
                }
            }
            
			if (file_exists(JPATH_ROOT.$subdir."/".$file)){
				copy(JPATH_ROOT.$subdir."/".$file, JPATH_ROOT.$subdir."/".$file."_".date('ymdHis'));
			}
            if (@copy($startdir.$subdir."/".$file, JPATH_ROOT.$subdir."/".$file)){
                saveToLog("install.log", "Copy file: ".$subdir."/".$file);
            }else{
                \JFactory::getApplication()->enqueueMessage("Copy file: ".$subdir."/".$file." ERROR",'error');
                saveToLog("install.log", "Copy file: ".$subdir."/".$file." ERROR");
            }
        }
        
        $folders = JFolder::folders($startdir.$subdir, '');
        foreach($folders as $folder){
            $dir = $subdir."/".$folder;            
            $this->copyFiles($startdir, $dir);
        }
        return 1;
    }
    
    function checkVersionUpdate($version){
        $jshopConfig = JSFactory::getConfig();
        
        $currentVersion = $jshopConfig->getVersion();
        $groupVersion = intval($currentVersion);
        
        if (isset($version[$groupVersion])){
            $min = $version[$groupVersion]['min'];
            $max = $version[$groupVersion]['max'];
            $min_cmp = version_compare($currentVersion, $min);
            $max_cmp = version_compare($currentVersion, $max);            
            if ($min_cmp<0){
                \JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_MIN_VERSION_ERROR', $min),'error');
                saveToLog("install.log", "Error: ".JText::sprintf('COM_SMARTSHOP_MIN_VERSION_ERROR', $min));
                return 0;
            }
            if ($max_cmp>0){
                \JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_MAX_VERSION_ERROR', $max),'errror');
                saveToLog("install.log", "Error: ".JText::sprintf('COM_SMARTSHOP_MAX_VERSION_ERROR', $max));
                return 0;
            }
        }
        return 1;
    }
         
}
?>