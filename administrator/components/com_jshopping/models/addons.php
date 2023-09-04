<?php
/**
* @version      4.7.0 02.05.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelAddons extends JModelLegacy{

    public function getList(){
        $db = \JFactory::getDBO(); 
        $query = "SELECT * FROM `#__jshopping_addons`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	private function checkConfigFile($alias){
		if (file_exists(JPATH_COMPONENT_SITE."/addons/".$alias."/config.tmpl.php"))
             return 1;
		else
			return 0;		
	}
	private function checkInfoFile($alias){
		if (file_exists(JPATH_COMPONENT_SITE."/addons/".$alias."/info.tmpl.php"))
             return 1;
		else
			return 0;		
	}
	private function checkVersionFile($alias){
		if (file_exists(JPATH_COMPONENT_SITE."/addons/".$alias."/version.tmpl.php"))
             return 1;
		else
			return 0;		
	}
	
	public function checkConfigFiles($rows){
		foreach($rows as $k=>$v){
			$rows[$k]->config_file_exist=$this->checkConfigFile($v->alias);
			$rows[$k]->info_file_exist=$this->checkInfoFile($v->alias);
			$rows[$k]->version_file_exist=$this->checkVersionFile($v->alias);
        }
		return $rows;
	}
	
	public function getAddonsListWithConfigFiles(){
		$rows = $this->getList();
		$rows = $this->checkConfigFiles($rows);
		return $rows;
	}
}
?>