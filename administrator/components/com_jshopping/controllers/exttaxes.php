<?php
/**
* @version      3.9.0 25.07.2012
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerExtTaxes extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("exttaxes");
		$id=0;if (isset($this->item)) $id=$this->item->id;
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $id);
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();
        $back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.exttaxes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ET.id", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');
		$additional_taxes=$taxExtAdditional->getAllAdditionalTaxes();		
		
        $_taxes = JSFactory::getModel("taxes");
        $rows = $_taxes->getExtTaxes($back_tax_id, $filter_order, $filter_order_Dir);
        
        $_countries = JSFactory::getModel("countries");
        $list = $_countries->getAllCountries(0);
        $countries_name = array();
        foreach($list as $v){
            $countries_name[$v->country_id] = $v->name;
        }

        foreach($rows as $k=>$v){
            $list = unserialize($v->zones);

            foreach($list as $k2=>$v2){
                $list[$k2] = $countries_name[$v2];
            }
            if (count($list) > 10){
                $tmp = array_slice($list, 0, 10);
                $rows[$k]->countries = implode(", ", $tmp)."...";
            }else{
                $rows[$k]->countries = implode(", ", $list);
            }
        }
				
		$stataa = JTable::getInstance('state', 'jshop');
		$db = \JFactory::getDBO();
		$lang = JSFactory::getLang();
		$query = "SELECT country_id, `".$lang->get("name")."` as name FROM `#__jshopping_countries`";
		$db->setQuery($query);
		foreach($db->loadObjectList() as $data)
		{
			$countryById[$data->country_id]=$data->name;
		}
		foreach($rows as $key=>$data)
		{
			if($data->zones && $data->zones_states)
			{
				$name=null;
				$country=unserialize($data->zones);
				$states=unserialize($data->zones_states);
				foreach($country as $dta)
				{
					if($name)$name.=", ";
					$name .= $countryById[$dta];
					$stt = null;
					$a = 0;
				
					if($states && $states[$dta])
					{
						foreach($stataa->getStates($dta) as $dt)
						{
							$stat[$dt->state_id]=$dt->name;
						}
					
						foreach($states[$dta] as $dt)
						{
						
							if($stt)$stt.=", ";
						
							if($a++>10)
							{
								$stt.='...';
								break;
							}
							$stt.=$stat[$dt];
						}
						$name.="(".$stt.") ";
					}
				}

				$rows[$key]->countries=$name;
			}
		}
				
        $view = $this->getView("taxes_ext", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $this->canDo);
        $view->set('rows', $rows); 
		$view->set('additional_taxes', $additional_taxes);
        $view->set('back_tax_id', $back_tax_id);
        $view->set('config', $jshopConfig);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforedisplayExtTax', array(&$view)); 
        $view->displayList();
    }

    function edit(){
        $jshopConfig = JSFactory::getConfig();
        $back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $id = JFactory::getApplication()->input->getInt("id");
        
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');
		$additional_taxes=$taxExtAdditional->getAllAdditionalTaxes();		
		
        $tax = JSFactory::getTable('taxExt', 'jshop');
        $tax->load($id);
        
        if (!$tax->tax_id && $back_tax_id){
            $tax->tax_id = $back_tax_id;
        }

        $list_c = $tax->getZones();
        $zone_countries = array();
        foreach($list_c as $v){
            $obj = new stdClass();
            $obj->country_id = $v;
            $zone_countries[] = $obj;
        }

        $_taxes = JSFactory::getModel("taxes");
        $all_taxes = $_taxes->getAllTaxes();
        $list_tax = array();
        foreach ($all_taxes as $_tax) {
            $list_tax[] = JHTML::_('select.option', $_tax->tax_id,$_tax->tax_name, 'tax_id', 'tax_name');
        }
        $lists['taxes'] = JHTML::_('select.genericlist', $list_tax, 'tax_id', 'class="form-select"', 'tax_id', 'tax_name', $tax->tax_id);
        
        $_countries = JSFactory::getModel("countries");
        $lists['countries'] = JHTML::_('select.genericlist', $_countries->getAllCountries(0), 'countries_id[]', 'class="form-select" size = "10", multiple = "multiple"', 'country_id', 'name', $zone_countries);
		
		loadingStatesScriptsAdmin();

        $view = $this->getView("taxes_ext", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $this->canDo);
        JFilterOutput::objectHTMLSafe($tax, ENT_QUOTES);
        $view->set('tax', $tax);
		$view->set('additional_taxes', $additional_taxes);
        $view->set('back_tax_id', $back_tax_id);
        $view->set('lists', $lists);
        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditExtTax', array(&$view));
        $view->displayEdit();
    }

    function save(){
        $back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $id = JFactory::getApplication()->input->getInt("id");
        $tax = JSFactory::getTable('taxExt', 'jshop');
        $post = $this->input->post->getArray(); 
        $post['tax'] = saveAsPrice($post['tax']);
        $post['firma_tax'] = saveAsPrice($post['firma_tax']);
		
		if (is_array($post['countries_id'])&& is_array($post['states_id'])) {
			$st = $post['states_id'];
			$countr = [];
			foreach($post['countries_id'] as $data) {
				$countr[$data]=$st[$data];
			}

			$post['countries_id'] = $countr;
		}		
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveExtTax', array(&$post) );
        
        if (!$tax->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
            return 0;
        }
        $tax->setZones($post['countries_id']);

        if (!$tax->store()){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
            return 0; 
        }
        
        updateCountExtTaxRule();
        
		$id = $tax->id;
		$db = \JFactory::getDBO();  

		if($tax->zones) {
			$countr = unserialize($tax->zones);
			$a_countr = [];
			$a_states = [];

			foreach($countr as $key=>$data) {
				if (is_array($data)) {
					$a_states[$key]=$data;
				}
				$a_countr[] = $key;
			}
		}

		if(count($a_states) > 0) {
			$query = "UPDATE `#__jshopping_taxes_ext` SET `zones_states`= '".serialize($a_states)."',`zones`= '".serialize($a_countr)."' WHERE id='".$id."' ";
		} else {
			$query = "UPDATE `#__jshopping_taxes_ext` SET `zones_states`= '' WHERE id='".$id."' ";
		}
		$db->setQuery($query);
		$db->execute();
				
        $dispatcher->triggerEvent( 'onAfterSaveExtTax', array(&$tax) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=edit&id=".$tax->id."&back_tax_id=".$back_tax_id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
        }
    }

    function remove(){
        $back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveExtTax', array(&$cid) );
		$_exttaxes = JSFactory::getModel('exttaxes');
		$text=$_exttaxes->deleteExttaxes($cid);
        updateCountExtTaxRule();
        $dispatcher->triggerEvent( 'onAfterRemoveExtTax', array(&$cid) );
		if(is_array($text)){
			$text = implode("</li><li>",$text);
		}
        $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id, $text);
    }
    
    function back(){
        $this->setRedirect("index.php?option=com_jshopping&controller=taxes");
    }
    
	public function additional_taxes($cachable = false, $urlparams = false) 
    {
        $jshopConfig = JSFactory::getConfig();
        include $jshopConfig->path . 'lib/default_config.php';
        $countriesModel = JSFactory::getModel('countries');	
		$back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
		$taxExt = JSFactory::getTable('taxExt', 'jshop');
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');		
		$columns=$taxExtAdditional->getExttaxesColums();
		$lists=$taxExtAdditional->getAllAdditionalTaxes();
		
		
		//$exttaxes->getExttaxesColums();
        /*
        $tax_rule_for = [
            JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_FIRMA_CLIENT'), 'id', 'name' ),
            JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_VAT_NUMBER'), 'id', 'name' )
        ];
*/
        $euCountries = $jshopConfig->eu_countries_to_show_b2b_msg ? explode(',', $jshopConfig->eu_countries_to_show_b2b_msg): [];
/*
        $lists = [
            'tax_rule_for' => JHTML::_('select.genericlist', $tax_rule_for, 'ext_tax_rule_for','class = "inputbox" size = "1"','id','name', $jshopConfig->ext_tax_rule_for),
            'countries' => JHTML::_('select.genericlist', $countriesModel->getAllCountries(0),'eu_countries_to_show_b2b_msg[]','class = "inputbox" size = "10", multiple = "multiple"', 'country_id','name', $euCountries),
            'applies_to' => JHTML::_('select.genericlist', $config->b2b_applies_to_options,'eu_countries_selected_applies_to','class = "inputbox"', 'id','name', [$jshopConfig->eu_countries_selected_applies_to], false, true)
        ];
	*/	
		$view = $this->getView('taxes_ext', 'html');
        $view->setLayout('additional_taxes');
		$view->set('canDo', $canDo ?? '');

        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
		$view->set('lists', $lists);
		$view->set('columns', $columns);
		
		$view->set('back_tax_id', $back_tax_id);
        
        $view->displayAdditional_taxes();
	}
	
	function back_exttaxes(){
		$back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
    }
	
	public function add_additional_taxes(){
		$jshopConfig = JSFactory::getConfig();
        include $jshopConfig->path . 'lib/default_config.php';
		$back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
		$id = JFactory::getApplication()->input->getInt("id");
		$languages = JSFactory::getModel('languages')->getAllLanguages();
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');
		if ($id>0) $row=$taxExtAdditional->loadTax($id);
		$view = $this->getView('taxes_ext', 'html');
        $view->setLayout('additional_taxes_edit');
		$view->set('canDo', $canDo ?? '');
        $view->set('other_config', $other_config);
        $view->set('other_config_checkbox', $other_config_checkbox);
        $view->set('other_config_select', $other_config_select);
        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
		$view->set('row', isset($row) ? $row : "");
		
		$view->set('languages', $languages);
		$view->set('back_tax_id', $back_tax_id);
        
        $view->displayAdditional_taxes_edit();
	}
	
	public function add_additional_taxes_apply(){
		$this->add_additional_taxes_save();
	}
	public function add_additional_taxes_save(){
		$back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $id = JFactory::getApplication()->input->getInt("id");
        $taxExt = JSFactory::getTable('taxExt', 'jshop');
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');
        $post = $this->input->post->getArray();
		$languages = JSFactory::getModel('languages')->getAllLanguages();
		//if ($id==0) {
			//$id=$taxExt->getNextAdditionalTaxId();
			$id=$taxExtAdditional->addNewAditionalTaxFields($id,$languages,$post);
			$taxExt->addAditionalTaxesId($id);
		//}
        /*$post['tax'] = saveAsPrice($post['tax']);
        $post['firma_tax'] = saveAsPrice($post['firma_tax']);
		
		if (is_array($post['countries_id'])&& is_array($post['states_id'])) {
			$st = $post['states_id'];
			$countr = [];
			foreach($post['countries_id'] as $data) {
				$countr[$data]=$st[$data];
			}

			$post['countries_id'] = $countr;
		}		
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveAdditionalTax', array(&$post) );
        
        if (!$tax->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
            return 0;
        }
        $tax->setZones($post['countries_id']);

        if (!$tax->store()){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&back_tax_id=".$back_tax_id);
            return 0; 
        }
        
        updateCountExtTaxRule();
        
		$id = $tax->id;
		$db = \JFactory::getDBO();  

		if($tax->zones) {
			$countr = unserialize($tax->zones);
			$a_countr = [];
			$a_states = [];

			foreach($countr as $key=>$data) {
				if (is_array($data)) {
					$a_states[$key]=$data;
				}
				$a_countr[] = $key;
			}
		}

		if(count($a_states) > 0) {
			$query = "UPDATE `#__jshopping_taxes_ext` SET `zones_states`= '".serialize($a_states)."',`zones`= '".serialize($a_countr)."' WHERE id='".$id."' ";
		} else {
			$query = "UPDATE `#__jshopping_taxes_ext` SET `zones_states`= '' WHERE id='".$id."' ";
		}
		$db->setQuery($query);
		$db->execute();
				
        $dispatcher->triggerEvent( 'onAfterSaveExtTax', array(&$tax) );
        */
		
        if ($this->getTask()=='add_additional_taxes_apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=add_additional_taxes&id=".$id."&back_tax_id=".$back_tax_id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=additional_taxes&back_tax_id=".$back_tax_id);
        }
		
	}
	
	function add_additional_taxes_delete(){
        $back_tax_id = JFactory::getApplication()->input->getInt("back_tax_id");
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveExtTax', array(&$cid) );
		$_exttaxes = JSFactory::getModel('exttaxes');
		$text=$_exttaxes->deleteExtAditionaltaxes($cid);
        updateCountExtTaxRule();
        $dispatcher->triggerEvent( 'onAfterRemoveExtTax', array(&$cid) );
		if(is_array($text)){
			$text = implode("</li><li>",$text);
		}
        $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=additional_taxes&back_tax_id=".$back_tax_id, $text);
		
    }
}
?>