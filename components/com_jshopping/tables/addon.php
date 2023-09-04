<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

if (!class_exists('JSFactory')) {
    require_once JPATH_ROOT . '/components/com_jshopping/lib/factory.php';
}

class jshopAddon extends JTableAvto
{
    public $id = null;
    public $alias = null;
    public $key = null;
    public $version = null;
    public $params = null;
    
    public function __construct(&$_db)
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        parent::__construct('#__jshopping_addons', 'id', $_db);
    }
    
    public function setParams($params)
    {
        $this->params = serialize($params);
    }
        
    public function getParams()
    {
        return !empty($this->params) ? unserialize($this->params) : [];
    }
    
    public function loadAlias(string $alias)
    {
        $modelOfAddonsFront = JSFactory::getModel('AddonsFront');
        $addonInfo = $modelOfAddonsFront->getByAlias($alias, ['id']);

        if (!empty($addonInfo)) {
            $this->load($addonInfo->id);
        }

        $this->alias = $alias;
    }
    
    public function getKeyForAlias(string $alias)
    {
        $modelOfAddonsFront = JSFactory::getModel('AddonsFront');
        $addonInfo = $modelOfAddonsFront->getByAlias($alias, ['key']);

        return $addonInfo->key;
    }
	
	public function installJoomlaExtension($data, $installexist = 0)
    {
        $db = \JFactory::getDBO();
        $modelOfExtensionsFront = JSFactory::getModel('ExtensionsFront');
        $exid = $modelOfExtensionsFront->getByElementAndFolderNames(['extension_id'], $data['element'] ?: '', $data['folder'] ?: '')->extension_id ?: '' ;

        if ($exid && !$installexist) {
            return -1;
        }
		$_data = '';
		foreach($data as $key=>$val){
			if(strlen($_data) > 0) $_data .= ',';
			$_data .= $key . '=' . $val;
		}
		$keys = array_keys($data);
		if(!in_array('manifest_cache',$keys)){
			$data['manifest_cache'] = '';
		}
		if(!in_array('params',$keys)){
			$data['params'] = '';
		}
		if(!in_array('custom_data',$keys)){
			$data['custom_data'] = '';
		}
		if($exid){
			$query = "UPDATE `#__extensions` SET ".$_data." WHERE `id`=".$exid;
			$db->setQuery($query);
			$db->execute();
						
		}else{
			$query = "INSERT INTO `#__extensions` (". implode(',', array_keys($data)) .") VALUES
			('". implode("','", $data) ."')";
		//	print_r($query);die;
			$db->setQuery($query);
			$db->execute();
			$exid = $db->insertid();
		}
		return true;
    }
	
	public function installJoomlaModule($data, $installexist = 0)
    {
        $db = \JFactory::getDBO();
		$query = "SELECT `id` FROM `#__modules` WHERE `module`='".$data['module']."'";
		$db->setQuery($query);
		$exid = $db->loadResult();
        if ($exid && !$installexist) {
            return -1;
        }
		if($exid){
			$query = "UPDATE `#__modules` SET `title`='".$data['title']."', `position`='".$data['position']."', `published`='".$data['published']."', `module`='".$data['module']."', `access`='".$data['access']."', `showtitle`='".$data['showtitle']."', `client_id`='".$data['client_id']."', `language`='".$data['language']."', `ordering`='".$data['ordering']."', `params`='' WHERE `id`=".$exid;
			$db->setQuery($query);
			$db->execute();
						
		}else{
			$query = "INSERT INTO `#__modules` (`title`, `position`, `published`, `module`, `access`, `showtitle`,`client_id`,`language`,`ordering`,`params`) VALUES
			('".$data['title']."','".$data['position']."','".$data['published']."','".$data['module']."','".$data['access']."','".$data['showtitle']."','".$data['client_id']."','".$data['language']."','".$data['ordering']."', '')";
			$db->setQuery($query);
			$db->execute();
			$exid = $db->insertid();
		}

        $extension = JSFactory::getTable('module', 'JTable');
        if ($exid) {
            $extension->load($exid);
        }

		if (!$exid) {
			$modelOfModulesMenusFront = JSFactory::getModel('ModulesMenuFront');
			$modelOfModulesMenusFront->insert(['moduleid', 'menuid'], [$module_id, 0]);
		}
        
        return 0;
    }
    
    public function installShipping($data, $installexist = 0)
    {
        $modelOfShippingExtCalc = JSFactory::getModel('ShippingExtCalc');
        $exid = (int)($modelOfShippingExtCalc->getByAliasName($data['alias'], ['id'])->id ?: 0);

        if ($exid && !$installexist) {
            return -1;
        }
        
        $extension = JSFactory::getTable('shippingExt', 'jshop');

        if ($exid) {
            $extension->load($exid);
        }

        if (!$exid) {
            $extension->ordering = (int)($modelOfShippingExtCalc->select(['MAX(ordering)'])['0']->ordering ?: 0) + 1;
        }

        $extension->bind($data);

        if ($extension->check()) {
            $extension->store();
            return 1;
        }

        return 0;
    }
	
	public function installShippingMethod($data, $installexist = 0)
    {
        $modelOfShippingMethodFront = JSFactory::getModel('shippingMethodPrice');
        $exid = (int)($modelOfShippingMethodFront->select(['id'], ['`alias` = \'' . $data['alias'] . '\''])['0']->id ?: 0);

        if ($exid && !$installexist) {
            return -1;
        }

        $extension = JSFactory::getTable('shippingMethodPrice', 'jshop');

        if ($exid) {
            $extension->load($exid);
        }

        if (!$exid) {
            $extension->ordering = (int)($modelOfShippingMethodFront->select(['MAX(ordering)'])['0']->ordering ?: 0) + 1;
        }

        $extension->bind($data);
        if ($extension->check()) {
            $extension->store();
            return 1;
        }

        return 0;
    }
    
    public function installPayment($data, $installexist = 0)
    {
        $modelOfPaymentsMethodFront = JSFactory::getModel('PaymentsFront');
        $exid = (int)($modelOfPaymentsMethodFront->select(['payment_id'], ['`payment_class` = \'' . $data['payment_class'] . '\''])['0']->payment_id ?: 0);

        if ($exid && !$installexist){
            return -1;
        }
        $extension = JSFactory::getTable('paymentMethod', 'jshop');
        if ($exid) {
            $extension->load($exid);
        }

        if (!$exid) {
            $extension->payment_ordering = (int)($modelOfPaymentsMethodFront->select(['MAX(payment_ordering)'])['0']->payment_ordering ?: 0) + 1;
        }

        $extension->bind($data);
        if ($extension->check()) {
            $extension->store();
            return 1;
        }

        return 0;
    }

    public function installImportExport($data, $installexist = 0)
    {
        $modelOfImportExportFront = JSFactory::getModel('ImportExportFront', 'jshop');
        $exid = (int)($modelOfImportExportFront->select(['id'], ['`alias` = \'' . $data['alias'] . '\''])['0']->id ?: 0);

        if ($exid && !$installexist) {
            return -1;
        }
        $extension = JSFactory::getTable('importExport', 'jshop');

        if ($exid) {
            $extension->load($exid);
        }

        $extension->bind($data);

        if ($extension->check()) {
            $extension->store();
            return 1;
        }

        return 0;
    }
    
    public function addFieldTable($table, $field, $type)
    {
        $db = \JFactory::getDBO();
        $listfields = $db->getTableColumns($table);

        if (!isset($listfields[$field])){
            $query = "ALTER TABLE ".$db->quoteName($table)." ADD ".$db->quoteName($field)." ".$type;
            $db->setQuery($query);
            $db->execute();
        }
    }

/*
    public function store($updateNulls = false)
    {
        //echo "<pre>222";
        //print_r($updateNulls);
        //die();
        $isOrderStored = parent::store($updateNulls);
        
        return $isOrderStored;
    }*/

}