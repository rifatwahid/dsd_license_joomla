<?php
/**
* @version      2.0.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelLanguages extends JModelLegacy{ 

    public function getAllLanguages($publish = 1) {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $where_add = $publish ? "where `published`='1'": ""; 
		$query = "SELECT *,`title` as `name`,`lang_code` as `language`,`lang_id` as `id`,`published` as `publish` FROM `#__languages` ".$where_add." order by `ordering`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $rowssort = array();
        $rows = $db->loadObjectList();
        foreach($rows as $k=>$v){
            $rows[$k]->lang = substr($v->language, 0, 2);
            if ($jshopConfig->cur_lang == $v->language) $rowssort[] = $rows[$k];
        }
        foreach($rows as $k=>$v){
            if (isset($rowssort[0]) && $rowssort[0]->language==$v->language) continue;
            $rowssort[] = $v;            
        }
        unset($rows);
        return $rowssort;
    }
	
	public function publishLanguages($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__languages","lang_id",$value,"publish",$flag);			
		}
	}
	
	public function productSave_setPostValues(&$post){
		$_alias = JSFactory::getModel("alias");
		$languages = $this->getAllLanguages(1);
        foreach($languages as $lang){
            $post['name_'.$lang->language] = trim($post['name_' . $lang->language]);            
            
            if (empty($post['alias_' . $lang->language])) {
                $post['alias_' . $lang->language] = $post['name_' . $lang->language];
            }

            $post['alias_' . $lang->language] = \JApplicationHelper::stringURLSafe($post['alias_'.$lang->language]);
            if ($post['alias_'.$lang->language]!="" && !$_alias->checkExistAlias2Group($post['alias_'.$lang->language], $lang->language, $post['product_id'])){
                $post['alias_'.$lang->language] = "";
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ALIAS_ALREADY_EXIST'),'error');
            } 
            
            if ($_POST['description'.$lang->id]!="") $post['description_'.$lang->language] = $_POST['description'.$lang->id];
			
            if ($_POST['short_description_'.$lang->language]!="") $post['short_description_'.$lang->language] = $_POST['short_description_'.$lang->language];
        }
	}
      
}

?>