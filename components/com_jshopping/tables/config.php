<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

class jshopConfig extends JTableAvto 
{
    public $pdf_header_file_name = 'header_default.jpg';
    public $pdf_footer_file_name = 'footer_default.jpg';
    
    public function __construct( &$_db )
    {
        parent::__construct('#__jshopping_config', 'id', $_db);
        $this->updateHeaderAndFooterPdfsNames();
    }

    public function updateHeaderAndFooterPdfsNames()
    {
        $header = 'header.jpg';
        $footer = 'footer.jpg';
        
        $this->pdf_header_file_name = $header;
        $this->pdf_footer_file_name = $footer;
    }

    public function transformPdfParameters() 
    {
        if (is_array($this->pdf_parameters)) {
            $this->pdf_parameters = implode(':', $this->pdf_parameters);
        }
    }

    public function loadCurrencyValue()
    {
        $session = JFactory::getSession();
        $id_currency_session = $session->get('js_id_currency');
        $id_currency = JFactory::getApplication()->input->getInt('id_currency');
        $main_currency = $this->mainCurrency;
        
        if ($session->get('js_id_currency_orig') && $session->get('js_id_currency_orig') != $main_currency) {
            $id_currency_session = 0;
            $session->set('js_update_all_price', 1);
        }

        if (!$id_currency && $id_currency_session) {
            $id_currency = $id_currency_session;
        }

        $session->set('js_id_currency_orig', $main_currency);
        $this->cur_currency = $main_currency;

        if ($id_currency) {
            $this->cur_currency = $id_currency;
        }

        $session->set('js_id_currency', $this->cur_currency);
        $all_currency = JSFactory::getAllCurrency();
        $current_currency = $all_currency[$this->cur_currency];

        if (!$current_currency->currency_value) {
            $current_currency->currency_value = 1;
        }

        $this->currency_value = $current_currency->currency_value;
        $this->currency_code = $current_currency->currency_code;
        $this->currency_code_iso = $current_currency->currency_code_iso;
    }
    
    public function getDisplayPriceFront()
    {
        $display_price = $this->display_price_front;

        if ($this->use_extend_display_price_rule > 0) {
            $adv_user = JSFactory::getUserShop();
			JSFactory::getModel('UsersFront')->checkClientType($adv_user);
            $country_id = $adv_user->country;
            $client_type = $adv_user->client_type;

            if (!$adv_user->user_id) {
                $adv_user = JSFactory::getUserShopGuest();
				JSFactory::getModel('UsersFront')->checkClientType($adv_user);
                $country_id = $adv_user->country;
                $client_type = $adv_user->client_type;
            }

            if (!$country_id) {
                $country_id = $this->default_country;
            }    

            if ($country_id) {
                $configDisplayPrice = JSFactory::getTable('configDisplayPrice', 'jshop');
                $rows = $configDisplayPrice->getList();

                foreach($rows as $v) {
                    if (in_array($country_id, $v->countries)) {

                        $display_price = $v->display_price;

                        if ($client_type == 2) {
                            $display_price = $v->display_price_firma;
                        }
                    }
                }
            }
        }
        return $display_price;
    }
    
    public function getListFieldsRegister()
    {

        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $config = $this;//new jshopConfig($this->_db);
        include(JPATH_COMPONENT_SITE . '/lib/default_config.php');     
        $data = [];

        if (!empty($this->fields_register)) {
            $data = unserialize($this->fields_register);
        }

        foreach($fields_client as $types => $_v) {
            if($types == 'register'){$type = 1;}elseif($types == 'address' || $types == 'editaccount'){$type = 2;}
            $data[$types] = $modelOfUsersFront->getListFields($type);

            foreach($fields_client[$types] as $k => $v) {
                if (!isset($data[$types][$v])) {
                    $data[$types][$v] = [
                        'display' => 0,
                        'require' => 0
                    ];                    
                }

                if (!isset($data[$types][$v]['display'])) {
                    $data[$type][$v]['display'] = 0;
                }

                if (!isset($data[$types][$v]['require'])) {
                    $data[$types][$v]['require'] = 0;
                }
            }
        }
        return $data;
    }

    public function corectListFieldsAddress($fields)
    {
        foreach($fields as $k=>$field){
            if($k != 'privacy_statement') $fields['d_'.$k] =  $field;
        }

        return $fields;
    }

    public function corectListFieldsRegister($fields)
    {
       $fields['email']['display'] = 1;
       $fields['email']['require'] = 1;
       $fields['password']['display'] = 1;
       $fields['password']['require'] = 1;
       $fields['password_2']['display'] = 1;
       $fields['password_2']['require'] = 1;

       return $fields;
    }

    public function getEnableDeliveryFiledRegistration($type='address', $table_type = 2)
    {
        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $tmp_fields = $modelOfUsersFront->getListFields($table_type);
        //$tmp_fields = $this->getListFieldsRegister();
		 $config_fields = [];
		if(isset($tmp_fields[$type])) $config_fields = (array)$tmp_fields[$type];
        $count = 0;

        foreach($config_fields as $k => $v) {
            if (substr($k, 0, 2) == 'd_' && $v['display'] == 1) {
                $count++;
            }
        }

        return ($count > 0);
    }
    
    public function getProductListDisplayExtraFields()
    {
        return !empty($this->product_list_display_extra_fields) ? unserialize($this->product_list_display_extra_fields) : [];
    }

    public function setProductListDisplayExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->product_list_display_extra_fields = $temp;
    }

    public function getFilterDisplayExtraFields()
    {
        return !empty($this->filter_display_extra_fields) ? unserialize($this->filter_display_extra_fields) : [];
    }
    
    public function setFilterDisplayExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->filter_display_extra_fields = $temp;
    }
    
    public function getProductHideExtraFields()
    {
        return !empty($this->product_hide_extra_fields) ? unserialize($this->product_hide_extra_fields) : [];
    }
    
    public function setProductHideExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->product_hide_extra_fields = $temp;
    }
    
    public function getCartDisplayExtraFields()
    {
        return !empty($this->cart_display_extra_fields) ? unserialize($this->cart_display_extra_fields) : [];
    }
    
    public function setCartDisplayExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->cart_display_extra_fields = $temp;
    }

    public function getPdfDisplayExtraFields()
    {
        return !empty($this->pdf_display_extra_fields) ? unserialize($this->pdf_display_extra_fields) : [];
    }
    
    public function setPdfDisplayExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->pdf_display_extra_fields = $temp;
    }

    public function getMailDisplayExtraFields()
    {
        return !empty($this->mail_display_extra_fields) ? unserialize($this->mail_display_extra_fields) : [];
    }
    
    public function setMailDisplayExtraFields($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->mail_display_extra_fields = $temp;
    }

    public function getHideExtraFieldsImages()
    {
        return !empty($this->hide_extra_fields_images) ? unserialize($this->hide_extra_fields_images) : [];
    }
    
    public function setHideExtraFieldsImages($data)
    {
        $temp = is_array($data) ? serialize($data) : serialize([]);
        $this->hide_extra_fields_images = $temp;
    }
    
    public function updateNextOrderNumber()
    {
        return JSFactory::getModel('ConfigsFront')->updateNextOrderNumber($this->id);
    }
	
    public function getNextOrderNumber()
    {
        return JSFactory::getModel('ConfigsFront')->getNextOrderNumber($this->id);
    }
    
    public function loadOtherConfig()
    {
        if (!empty($this->other_config)) {
            $tmp = unserialize($this->other_config);

            foreach($tmp as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    public function getVersion()
    {
        $data = JApplicationHelper::parseXMLInstallFile($this->admin_path . 'jshopping.xml');
        return $data['version'];
    }
    
    public function loadLang()
    {
        $this->cur_lang = JFactory::getLanguage()->getTag();
    }
    
    public function loadFrontLand()
    {
        $params = JComponentHelper::getParams('com_languages');
        $this->frontend_lang = $params->get('site', 'en-GB');
    }
    
    public function setLang($lang)
    {
        $this->cur_lang = $lang;
    }
    
    public function getLang()
    {
        return $this->cur_lang;
    }
    
    public function getFrontLang()
    {
        return $this->frontend_lang;
    }
  
    public function buildArrayWithFieldsRegisterForJS($type, $table_type = 2)
    {
        $result = [
            'ids' => [],
            'params' => [],
            'type' => [],
            'errors' => []
        ];
        $except = ['password', 'password_2', 'firma_code'];
        $types = [
            'notn' => ['title', 'client_type', 'country', 'd_title', 'd_country', 'd_client_type','state'],
            'nem' => ['f_name', 'l_name', 'm_name', 'firma_name', 'birthday', 'home', 'apartment', 'street', 'street_nr', 'city', 
                'phone', 'mobil_phone', 'fax', 'ext_field_1', 'ext_field_2', 'ext_field_3', 'u_name', 'password', 'd_f_name', 'd_l_name', 
                'd_m_name', 'd_firma_name', 'd_birthday', 'd_home', 'd_apartment', 'd_street', 'd_street_nr', 'd_city',  'd_state', 'd_phone', 
                'd_mobil_phone', 'd_fax', 'd_ext_field_1', 'd_ext_field_2', 'd_ext_field_3', 'tax_number', 'd_tax_number'
            ],
            'em' => ['email', 'd_email'],
            'eqne' => ['email2'],
            'chk' => ['privacy_statement'],
            'zip'=> ['zip', 'd_zip']
        ];
        $errors = [
            'client_type' => JText::_('COM_SMARTSHOP_REGWARN_CLIENT_TYPE'),
            'title' => JText::_('COM_SMARTSHOP_REGWARN_TITLE'),
            'country' => JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'),
            'email' => JText::_('COM_SMARTSHOP_REGWARN_MAIL'),
            'f_name' => JText::_('COM_SMARTSHOP_REGWARN_NAME'),
            'l_name' => JText::_('COM_SMARTSHOP_REGWARN_LNAME'),
            'm_name' => JText::_('COM_SMARTSHOP_REGWARN_MNAME'),
            'firma_name' => JText::_('COM_SMARTSHOP_REGWARN_FIRMA_NAME'),
            'birthday' => JText::_('COM_SMARTSHOP_REGWARN_BIRTHDAY'),
            'home' => JText::_('COM_SMARTSHOP_REGWARN_HOME'),
            'password' => JText::_('COM_SMARTSHOP_REGWARN_PASSWORD'),
            'password_2' => JText::_('COM_SMARTSHOP_REGWARN_PASSWORD_AGAIN'),
            'apartment' => JText::_('COM_SMARTSHOP_REGWARN_APARTMENT'),
            'street' => JText::_('COM_SMARTSHOP_REGWARN_STREET'),
            'city' => JText::_('COM_SMARTSHOP_REGWARN_CITY'),
            'state' => JText::_('COM_SMARTSHOP_REGWARN_STATE'),
            'phone' => JText::_('COM_SMARTSHOP_REGWARN_PHONE'),
            'mobil_phone' => JText::_('COM_SMARTSHOP_REGWARN_MOBIL_PHONE'),
            'fax' => JText::_('COM_SMARTSHOP_REGWARN_FAX'),
            'ext_field_1' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_1'),
            'ext_field_2' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_2'),
            'ext_field_3' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_3'),
            'u_name' => JText::_('COM_SMARTSHOP_REGWARN_UNAME'),
            'privacy_statement' => JText::_('COM_SMARTSHOP_QC_CONFIRM_POLICY'),
            'zip' => JText::_('COM_SMARTSHOP_REGWARN_ZIP'),
            'tax_number' => JText::_('COM_SMARTSHOP_REGWARN_TAX_NR'),
            'street_nr' => JText::_('COM_SMARTSHOP_REGWARN_STREET_NR'),
            'firma_code' => JText::_('COM_SMARTSHOP_REGWARN_FIRMA_CODE'),
            'd_client_type' => JText::_('COM_SMARTSHOP_REGWARN_CLIENT_TYPE_DELIVERY'),
            'd_title' => JText::_('COM_SMARTSHOP_REGWARN_TITLE_DELIVERY'),
            'd_country' => JText::_('COM_SMARTSHOP_REGWARN_COUNTRY_DELIVERY'),
            'd_email' => JText::_('COM_SMARTSHOP_REGWARN_MAIL_DELIVERY'),
            'd_f_name' => JText::_('COM_SMARTSHOP_REGWARN_NAME_DELIVERY'),
            'd_l_name' => JText::_('COM_SMARTSHOP_REGWARN_LNAME_DELIVERY'),
            'd_m_name' => JText::_('COM_SMARTSHOP_REGWARN_MNAME_DELIVERY'),
            'd_firma_name' => JText::_('COM_SMARTSHOP_REGWARN_FIRMA_NAME_DELIVERY'),
            'd_birthday' => JText::_('COM_SMARTSHOP_REGWARN_BIRTHDAY_DELIVERY'),
            'd_home' => JText::_('COM_SMARTSHOP_REGWARN_HOME_DELIVERY'),
            'd_apartment' => JText::_('COM_SMARTSHOP_REGWARN_APARTMENT_DELIVERY'),
            'd_street' => JText::_('COM_SMARTSHOP_REGWARN_STREET_DELIVERY'),
            'd_city' => JText::_('COM_SMARTSHOP_REGWARN_CITY_DELIVERY'),
            'd_state' => JText::_('COM_SMARTSHOP_REGWARN_STATE_DELIVERY'),
            'd_phone' => JText::_('COM_SMARTSHOP_REGWARN_PHONE_DELIVERY'),
            'd_mobil_phone' => JText::_('COM_SMARTSHOP_REGWARN_MOBIL_PHONE_DELIVERY'),
            'd_fax' => JText::_('COM_SMARTSHOP_REGWARN_FAX_DELIVERY'),
            'd_ext_field_1' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_1_DELIVERY'),
            'd_ext_field_2' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_2_DELIVERY'),
            'd_ext_field_3' => JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_3_DELIVERY'),
            'd_zip' => JText::_('COM_SMARTSHOP_REGWARN_ZIP_DELIVERY'),
            'd_tax_number' => JText::_('COM_SMARTSHOP_REGWARN_TAX_NR_DELIVERY'),
            'd_street_nr' => JText::_('COM_SMARTSHOP_REGWARN_STREET_NR_DELIVERY'),
            'd_firma_code' => JText::_('COM_SMARTSHOP_REGWARN_FIRMA_CODE_DELIVERY')
        ];

        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $fields = $modelOfUsersFront->getListFields($table_type);

        if($type == 'address') {
            $fields = $this->corectListFieldsAddress($fields);
        }

        if($type == 'register') {
            $fields = $this->corectListFieldsRegister($fields);
        }

        foreach($fields as $fieldName => $fieldParams) {
            if (in_array($fieldName, $except)) {
				
				$result['except'][$fieldName] = $errors[$fieldName];
                continue;
            }

            if ($fieldParams['require']) {
                $result['ids'][] = $fieldName;
                $result['params'][] = $fieldName;
                $result['errors'][] = $errors[$fieldName];

                foreach($types as $fieldType => $typeFields) {
                    if (in_array($fieldName, $typeFields)) {
                        $result['type'][] = $fieldType;
                    }
                }

                if ($fieldName == 'email2') {
                    $result['ids'][] = 'email';
                    $result['type'][] = 'eqne';
                    $result['params'][] = $fieldName;
                    $result['errors'][] = $errors['email'];
                }
            }
        }

		if (isset($fields['password']) && isset($fields['password_2']) && $fields['password'] && $fields['password_2']) {
            $result['ids'][] = 'password';
            $result['params'][] = 'password_2';
            $result['type'][] = 'eqne';
            $result['errors'][] = $errors['password'];
        } else if (isset($fields['password']) && $fields['password']) {
            $result['ids'][] = 'password';
            $result['params'][] = '';
            $result['type'][] = 'nem';
            $result['errors'][] = $errors['password'];
        }

        return json_encode($result);
    }
	
    
    public function updateNextInvoiceNumber(int $num = 0)
    {
        return JSFactory::getModel('ConfigsFront')->updateNextInvoiceNumber($num, $this->id);
    }
    
    public function updateNextRefundNumber(int $num = 0)
    {
        return JSFactory::getModel('ConfigsFront')->updateNextRefundNumber($num, $this->id);
    }
}