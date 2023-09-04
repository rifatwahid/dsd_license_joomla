<?php
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.controller');

class JshoppingControllerStates extends JshoppingControllerBase
{
	public function statesAjax() : void
	{
		$jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();		
		$country_id = (string)JFactory::getApplication()->input->getVar("country_id");
        $address_id = (int)JFactory::getApplication()->input->getVar("address_id");

        $d_id = JFactory::getApplication()->input->getVar("id");
        $key = JFactory::getApplication()->input->getVar("key");  

        if ($user->id){
            $adv_user = JSFactory::getUserShop();
        }else{
            $adv_user = JSFactory::getUserShopGuest();    
        }
		if ($country_id===''){
			$country_id = $adv_user->country ?: $jshopConfig->default_country;
		}
		$country_id = (int)$country_id;
        
        $list_states = $this->getListStates($country_id);

        $option_states = array();
		//$option_states[] = JHTML::_('select.option',  '', '', 'name', 'name' );
        if ($list_states){
			$option_states = array_merge($option_states, $list_states);
		
        if ($d_id=='country'){
            if($address_id){
                $state = JSFactory::getModel('UserAddressesFront')->getById($address_id)->state ?: $adv_user->state;
            }
			$select_states = JHTML::_('select.genericlist', $option_states, 'state', 'class = "input form-select" onchange="refreshDataForAllSections();" onfocusout="shopUser.validateAccountField(jQuery(this).closest(\'form\').get(0), jQuery(this).attr(\'name\'));"','name', 'name', $state ?? '');
			$select_states .= '<div class="state_error text-danger"></div>';
		}
        if ($d_id=='d_country'){
            if($address_id) {
                $state = JSFactory::getModel('UserAddressesFront')->getById($address_id)->state ?: $adv_user->d_state;
            }
			$select_states = JHTML::_('select.genericlist', $option_states, 'd_state', 'class = "input form-select" onchange="refreshDataForAllSections();"  onfocusout="shopUser.validateAccountField(jQuery(this).closest(\'form\').get(0), jQuery(this).attr(\'name\'));"','name', 'name', $state ?? '');
			$select_states .= '<div class="d_state_error text-danger"></div>';
		}
        if ($d_id=='country_cart'){
            if($address_id) {
                $state = JSFactory::getModel('UserAddressesFront')->getById($address_id)->state ?: $adv_user->state;
            }
            $option_states = array();
            //$option_states[] = JHTML::_('select.option',  '', JText::_('COM_SMARTSHOP_STATE'), 'name', 'name' );

                $option_states = array_merge($option_states, $list_states);
			$select_states = JHTML::_('select.genericlist', $option_states, 'state', 'class = "input form-select" onchange="shopCart.getShippingPrice(\'country\', document.getElementById(\'country\').value, this.value);" ','name', 'name', $state);
			$select_states .= '<div class="state_error text-danger"></div>';
		}
        if ($d_id=='country_'.$key){
			$select_states = JHTML::_('select.genericlist', $option_states, 'sm_params[shipping_pricestateweight_state_to][]', 'class="form-select" style = "width: 120px;" class = "inputbox"','name', 'name', '', 'state_'.$key );
		}
		}else{
			if ($d_id=='country'){
				$select_states="<input type='text' class='input' name='state'  onfocusout='shopUser.validateAccountField(jQuery(this).closest(\"form\").get(0), jQuery(this).attr(\"name\"));' id='state' value='".$state."'>";
				$select_states .= '<div class="state_error text-danger"></div>';
	        }
			if ($d_id=='country_cart'){
				$select_states="<input type='text' class='input' name='state' placeholder='".JText::_('COM_SMARTSHOP_STATE')."'  onfocusout='shopCart.getShippingPrice(\"country\", document.getElementById(\"country\").value, this.value);' id='state'>";
				$select_states .= '<div class="state_error text-danger"></div>';
	        }
			if ($d_id=='d_country'){
				$select_states="<input type='text' class='input' name='d_state' onfocusout='shopUser.validateAccountField(jQuery(this).closest(\"form\").get(0), jQuery(this).attr(\"name\"));' id='d_state' >";
				$select_states .= '<div class="d_state_error text-danger"></div>';
			}
			if ($d_id=='country_'.$key){
				$select_states="<input type='text' class='input' name='sm_params[shipping_pricestateweight_state_to][]' style = 'width: 120px;' id='state_$key'>";
			}
		}
		$res = array();
        $res['select_states'] =  $select_states;
        $res['status']='OK';

        echo json_encode($res);  
        die; 
	}
	
	private function getListStates($country_id) : array
	{
		$db = \JFactory::getDBO();        
        $lang = JSFactory::getLang();
		$jshopConfig = JSFactory::getConfig();    

		$ordering = "ordering";
        if ($jshopConfig->sorting_country_in_alphabet){
			$ordering = "name";      
		}
		$query = "SELECT state_id, `".$lang->get("name")."` as name FROM `#__jshopping_states` 
				WHERE country_id='".(int)$country_id."' AND state_publish=1 ORDER BY ".$ordering;
        $db->setQuery($query);                
        return $db->loadObjectList();
	}
	public function getListStatesAjax(): void
	{
        $country_id = (string)JFactory::getApplication()->input->getVar("country_id");
        $html = '';
        $list_states = $this->getListStates($country_id);
        $option_states = array();
        $option_states[] = JHTML::_('select.option',  '', '', 'name', 'name' );
        if (!empty($list_states)) {
            $option_states = array_merge($option_states, $list_states);
            $html = "<div class='form-group row align-items-center states_list'><label class='col-sm-3 col-md-2 col-xl-2 col-12 col-form-label' for='states_id'>".JText::_('COM_SMARTSHOP_STATE')."</label>";
            $html .= "<div class='col-sm-9 col-md-10 col-xl-10 col-12'>".JHTML::_('select.genericlist', $option_states, "shipping_states_id[]", 'class = "form-select inputbox" size = "9",  multiple="multiple" ' ,'state_id', 'name', 0 ).'</div></div>';

        }
        $res = array();
        $res['html'] =  $html;

        echo json_encode($res);
        die;
	}

	public function new_idAjax() : void
	{
        $new_id = JFactory::getApplication()->input->getVar("new_id");   
	
        $jshopCountry = JTable::getInstance('state', 'jshop'); 
        $option_country = array();
		$option_country[] = JHTML::_('select.option',  '0', _JSHOP_REG_SELECT, 'country_id', 'name' );
        $option_countryes = array_merge($option_country, $jshopCountry->getAllCountries());           
        $select_countries = JHTML::_('select.genericlist', $option_countryes, 'sm_params[shipping_pricestateweight_state_from][]', 'class="form-select" style = "width: 120px;" class = "inputbox" size = "1" onchange="javascript:getState0(this.id, this.value, '.$new_id.')";','country_id', 'name', '', 'country_'.$new_id );  
		
		$option_states = array();
        $option_states[] = JHTML::_('select.option',  '', '', 'state_id', 'name' ); 
        $select_states = JHTML::_('select.genericlist', $option_states, 'sm_params[shipping_pricestateweight_state_to][]', 'class="form-select" style = "width: 120px;" class = "inputbox" size = "1"','name', 'name', '', 'state_'.$new_id );  
		$res = array();
        $res['select_countries'] =  $select_countries;   
        $res['select_states'] =  $select_states;

        $res['status']='OK';

        echo json_encode($res);  
        die; 
	}

	public function states_ajax() : void
	{
		$value = JFactory::getApplication()->input->getVar("value");
		$exttaxes_id = JFactory::getApplication()->input->getInt("exttaxes_id");
		$res['html'] = '';
		if (!is_array($value)){
			$value = (array)$value;
		}
		if (is_array($value))
		{
			if((count($value) > 0) && (count($value) < 2))
			{
				$db = \JFactory::getDBO();
				$jshopConfig = JSFactory::getConfig();
				$lang = JSFactory::getLang();
				$user = JFactory::getUser();
				$tax_zones_states = [];
				$tax_zones = [];
				
				$query = "SELECT `zones_states` FROM `#__jshopping_taxes_ext` WHERE id = '".$exttaxes_id."'";
				$db->setQuery($query);  
				
				if ($row=$db->loadResult()) {
					$tax_zones=unserialize($row);
				}

				$query = "SELECT `".$lang->get("name")."` FROM `#__jshopping_countries` WHERE country_id='".intval($value[0])."'";
				$db->setQuery($query);                
				$name = $db->loadResult();

				if ($user->id) {
					$adv_user = JSFactory::getUserShop();
				} else {
					$adv_user = JSFactory::getUserShopGuest();    
				}

				if($exttaxes_id) {
					if(isset($tax_zones[$value[0]])) {
						foreach($tax_zones[$value[0]] as $data) {
							$tax_zones_states[$data]=$data;
						}
					}
				}

				$query = "SELECT state_id, `".$lang->get("name")."` as name FROM `#__jshopping_states` WHERE country_id='".intval($value[0])."'";
				$db->setQuery($query);
				$row = $db->loadObjectList();
				$ert = [];

				if($row)
				{
					$cou=count($tax_zones_states);
					foreach($row as $data) {
						$option_stats[] = JHTML::_('select.option',$data->state_id, $data->name, 'id', 'name' );
						if($cou > 0) {
							if($tax_zones_states[$data->state_id])$ert[]=$data->state_id;
						} else {
							$ert[]=$data->state_id;
						}
					}
					$res['html']  = "<div style='font-weight: bold;'>".$name."</div>";
					$res['html'] .= JHTML::_('select.genericlist', $option_stats, "states_id[".$value[0]."][]", 'class = "form-select inputbox" size = "9",  multiple="multiple" ' ,'id', 'name', $ert );
				}
			}
		}

		$res['status'] = 'OK';
		echo json_encode($res);	
		die;
	}

}