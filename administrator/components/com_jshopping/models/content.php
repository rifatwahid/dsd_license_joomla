<?php
/**
* @version      4.6.0 26.06.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelContent extends JModelLegacy
{ 

	protected $arrWithContentNames = [
		'agb',
		'shipping',
		'return_policy',
		'privacy_statement',
		'order_success_page',
		'return_finish_page'
	];

	/**
	*	@return array
	*/
	public function getList()
    {		
    	foreach($this->arrWithContentNames as $key => $contentName ) {
			$res[$contentName] = $this->getPolicyByName($contentName);  		
    	}
        return $res;
    }	

    /**
    *	@return array
    */
    public function getPolicyByName($policyName)
    {
    	$res = [];

    	if ( !empty($policyName) ) {
			$lang = JSFactory::getLang();
	        $db = \JFactory::getDBO();   

			$where = "1";
			$orderby="";
			$dispatcher = \JFactory::getApplication();
			$dispatcher->triggerEvent('onBeforeGetContentList', array(&$where,&$orderby));
          	
			$query = "SELECT jc.id, jc.link, jc.content, jc.lang, jc.type 
				FROM `#__jshopping_content` as jc  
				WHERE jc.content = '" . $policyName . "' AND {$where} {$orderby}";
			$db->setQuery($query);
		
			$order_success_page = $db->loadObjectList();	

			foreach ($order_success_page as $key=>$value) {
				if($value->type == 2){
					$query = "SELECT `title`
						FROM `#__sppagebuilder` 
						WHERE `id` = '" . $value->link . "'";
					$db->setQuery($query);
					$res[$value->lang]['title'] = $db->loadResult();	
				}else{
					$query = "SELECT `title`
						FROM `#__content` 
						WHERE `id` = '" . $value->link . "'";
					$db->setQuery($query);
					$res[$value->lang]['title'] = $db->loadResult();		
				}
				$res[$value->lang]['link'] = $value->link;				
				$res[$value->lang]['id'] = $value->id;			
				$res[$value->lang]['type'] = $value->type;	
			}
			
		}

		return $res;    	
    }

	public function storeList($post, $languages)
	{     
		$db = \JFactory::getDBO(); 
		
		foreach($languages as $lang) {
			foreach($this->arrWithContentNames as $contentName) {
				$where = '';
				$additionlFieldsOfUpdate = '';
				$additionalFieldsOfStore = '';
				$additionalValuesOfStore = '';
				$dispatcher = \JFactory::getApplication();
				$dispatcher->triggerEvent('onBeforeSetContentList', [&$where, &$additionlFieldsOfUpdate, &$additionalFieldsOfStore, &$additionalValuesOfStore]);
				
				$query = "SELECT `id` FROM `#__jshopping_content` WHERE `content` = " . $db->q($contentName) . " and lang = '" . $lang->lang . "' {$where}";
				$db->setQuery($query);
				$res = $db->loadObject();
				$postName = $contentName . '_' . $lang->lang;
				$postNameType = $contentName . '_' . $lang->lang . '_type';
				if ($additionalValuesOfStore == ',') {
					$additionlFieldsOfUpdate = '';
				}

				if (!empty($res->id)) {
					$query = "UPDATE #__jshopping_content set `link` ='" . $post[$postName] . "', `type` = '" . $post[$postNameType] . "' {$additionlFieldsOfUpdate} where id=" . $res->id;
					$db->setQuery($query);
					$db->execute();			
				} else {
					$query = 'INSERT INTO #__jshopping_content (`lang`,`content`,`link`, `type` '.$additionalFieldsOfStore.') VALUES ("' . $lang->lang . '","' . $contentName . '","' . $post[$postName] . '","' . $post[$postNameType] . '" '.$additionalValuesOfStore.')';
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	public function getContentCount($lang = '')
	{
		$db = \JFactory::getDBO();    
		$where = '';
		
		if (!empty($lang)) {
			$where = "WHERE language='" . $lang . "'";
		}

		$query = "SELECT language FROM `#__content` $where";
		$db->setQuery($query);
		$contentlist = $db->loadObjectList();

		return count($contentlist);
	}

	public function getContentList($page = 0, $per_page = 10, $lang = '')
	{
		$db = \JFactory::getDBO();  
		$where = '';
		
		if ( !empty($lang) ){
			$where = "WHERE cnt.language='" . $lang . "'";
		}

		$query = "SELECT cnt.state,cnt.publish_up,cnt.publish_down,cnt.id,cnt.title,cnt.ordering,cnt.language,cnt.access,cnt.created,u.title as access_title FROM `#__content` as cnt LEFT JOIN #__usergroups as u ON cnt.access=u.id $where ORDER BY cnt.ordering ASC, cnt.id DESC Limit " . ($page * $per_page) . ",$per_page";
		$db->setQuery($query);
		$contentlist = $db->loadObjectList();	

		return $contentlist;
	}

	public function getPBCount($lang = '*')
	{
		$db = \JFactory::getDBO();    
		
		if ($lang != '') {
			$where = " AND `language`='" . $lang . "'";
		}else{			
			$where = " AND `language`='*'";
		}

		$query = "SELECT `language` FROM `#__sppagebuilder` WHERE `extension`='com_sppagebuilder' AND `extension_view`='page' $where";
		$db->setQuery($query);
		$contentlist = $db->loadObjectList();

		return count($contentlist);
	}

	public function getPBList($page = 0, $per_page = 10, $lang = '')
	{
		$db = \JFactory::getDBO();  
		$where = '';
		
		if ( !empty($lang) ){
			$where = " AND cnt.language='" . $lang . "'";
		}

		$query = "SELECT cnt.published as state,cnt. created_on aspublish_up,cnt.checked_out_time as publish_down,cnt.id,cnt.title,cnt.ordering,cnt.language,cnt.access,u.title as access_title 
			FROM `#__sppagebuilder` as cnt 
			LEFT JOIN #__usergroups as u ON cnt.access=u.id 
			 WHERE `extension`='com_sppagebuilder' AND `extension_view`='page' $where ORDER BY cnt.ordering ASC, cnt.id DESC Limit " . ($page * $per_page) . ",$per_page";
		$db->setQuery($query);
		$contentlist = $db->loadObjectList();	

		return $contentlist;
	}
	public function ifPageBuilderEnabled(){
		$db = \JFactory::getDBO();
		
		$db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'com_sppagebuilder'");
		return $db->loadResult();
	}
}
