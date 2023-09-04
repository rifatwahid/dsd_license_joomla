<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerFree_Attribute_calcule_price extends JControllerLegacy{
    public function __construct( $config = array() ){
        parent::__construct( $config );
    }
    
    public function getadvopt(){
        $htmlDoc = JFactory::getDocument();
        $htmlDoc->addStyleSheet(JURI::base() . '/components/com_jshopping/css/free_attribute_calcule_price.css');
        $key = JFactory::getApplication()->input->getInt('key');        
        $view = $this->getView('free_attribute_calcule_price', 'html');
        $view->set('key', $key);
        echo $view->loadTemplate();
        die;
    }

    public function updateAddonParams(){    	
    	if ( !empty(JFactory::getApplication()->input->post->getArray()['params']) ) {
    		$params = JFactory::getApplication()->input->post->getArray()['params'];

    		$variablesNamesArrFromPost = $params['variablesNames'];
    		
    		$addonTable = JSFactory::getTable('addon');
    		$addonTable->loadAlias('addon_free_attribute_calcule_price');

    		$addonParams = $addonTable->getParams();
    		$addonParams['variablesNames'] = $variablesNamesArrFromPost;
    		$addonTable->setParams($addonParams);

    		$addonTable->store();
    		die;
    	}
    }
	
	public function getProductAttrPriceTypeSelect() 
	{
		$default = 0;
		$id = '';

		$freeAttrTable = JSFactory::getTable('FreeAttribut');
        $addonParams = (object) $freeAttrTable->getParams();
		
		/*
		$view = $this->getView('product_attr_price_type_select', 'html');
        $view->set('id', $id);
		$view->set('default', $default);
		$view->set('selected', $selected);
		$view->set('addonParams', $addonParams);
        echo $view->loadTemplate();
		die();
		/**/
		
		$html = '<select name="attrib_ind_price_type[]" class="inputbox"';
		if ($id != '') $html .= ' id="';
		$html .= '>';
		$selected = ($default == 0) ? ' selected="selected"' : '';
		$html .= '<option value="0"'.$selected.'>'.JText::_('COM_SMARTSHOP_SELECT').'</option>';
		if (isset($addonParams->pricetypes_formula) && count($addonParams->pricetypes_formula)) {
			foreach ($addonParams->pricetypes_formula as $key => $value) {
				if ($value == '') continue;
				$selected = ($default == $key) ? ' selected="selected"' : '';
				$html .= '<option value="'.$key.'"'.$selected.'>'.$addonParams->pricetypes_formula_name[$key].'</option>';
			}
		}
		$html .= '</select>';
		print $html;die;
	}
}