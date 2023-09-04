<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

use Joomla\CMS\Language\Text;

class JshoppingControllerUser extends JshoppingControllerBase
{

    public function __construct($config = [])
    {
        parent::__construct( $config );
        JPluginHelper::importPlugin('jshoppingcheckout');
        JPluginHelper::importPlugin('jshoppingorder');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerUser', [&$currentObj]);
		setSeoMetaData();
    }
    
    public function display($cachable = false, $urlparams = false)
    {
        $this->myaccount();
    }

    public function wishlist()
    {
        $app = JFactory::getApplication();
        return $app->redirect(SEFLink('/index.php?option=com_jshopping&controller=wishlist', 0, 1));
    }

    public function authentication()
    {
        $app = JFactory::getApplication();
        return $app->redirect(SEFLink('/index.php?option=com_users&view=profile&layout=edit', 0, 1));
    }

    public function offer_and_order()
    {
        $app = JFactory::getApplication();
        return $app->redirect(SEFLink('/index.php?option=com_jshopping&controller=offer_and_order', 0, 1));
    }
    
    public function login()
    {
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
       
        $user = JFactory::getUser();
        if ($user->id) {
            $view = $this->getView('user', getDocumentType(), '', [
                'template_path' => viewOverride('user', 'logout.php')
            ]);

            $layout = getLayoutName('user', 'logout');
            $view->setLayout($layout);
            $view->set('component', 'Logout');
            $view->set('logout', SEFLink("index.php?option=com_jshopping&controller=user&task=logout"));

            $view->set('sef', JFactory::getConfig()->get('sef'));
            $document->addScriptDeclaration('const dataJson='.json_encode($view));
            if($ajax){ print_r(json_encode($view));die; }
            $view->display();

            return 0;
        }
		
        $modelOfUsersFront = JSFactory::getModel('UsersFront');
   
        $return = !empty(JFactory::getApplication()->input->getVar('return')) ? JFactory::getApplication()->input->getVar('return') : $session->get('return');
        $show_pay_without_reg = $session->get('show_pay_without_reg');
        
        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('login');
        if (getThisURLMainPageShop()) {
            appendPathWay(JText::_('COM_SMARTSHOP_LOGIN'));

            if (empty($seodata->title)) {
                $seodata->title = JText::_('COM_SMARTSHOP_LOGIN');
            }         
        }
		setSeoMetaData($seodata->title ?? '');
        $countries = JSFactory::getModel('CountriesFront')->generateCountriesSelectMarkup();
        $select_countries = $countries->selectCountries;

        $modelOfUserFront = JSFactory::getModel('UsersFront');
        $select_titles = $modelOfUserFront->generateClientTitlesSelectMarkup()->selectTitles;
        $select_client_types = $modelOfUserFront->generateClientTypesSelectMarkup();

        //$tmp_fields = $jshopConfig->getListFieldsRegister();
        //$config_fields = $tmp_fields['register'];
		$config_fields = $modelOfUsersFront->getListFields(2);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayLogin', []);
        $dispatcher->triggerEvent('onBeforeDisplayRegister', []);
        if (method_exists('JHtmlBehavior', 'calendar')) {
            JHtmlBehavior::calendar();
        }

        if ($session->get('display_link_offer_and_order_guest')) {
            $document->addScriptDeclaration('moveBlockAnfrageGuest();');
        }
        loadJSLanguageKeys();
        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'login.php')
        ]);
        $layout = getLayoutName('user', 'login');
        $view->setLayout($layout);
        $view->set('component', 'Login');

        $view->set('href_register', SEFLink('index.php?option=com_jshopping&controller=user&task=register', 1, 0, $jshopConfig->use_ssl));
        $view->set('href_lost_pass', SEFLInk('index.php?option=com_users&view=reset', 0, 0, $jshopConfig->use_ssl));
        $view->set('return', $return);
        $view->set('Itemid', JFactory::getApplication()->input->getVar('Itemid'));
        $view->set('config', $jshopConfig);
        $view->set('show_pay_without_reg', $show_pay_without_reg);
        $view->set('select_client_types', $select_client_types);
        $view->set('select_titles', $select_titles);
        $view->set('select_countries', $select_countries);
        $view->set('config_fields', $config_fields);
        $view->set('live_path', JURI::base());
        $view->set('isDisplayLinkOfferAndOrderGuest', $session->get('display_link_offer_and_order_guest'));
        $view->set('urlcheckdata', '/index.php?option=com_jshopping&controller=user&task=check_user_exist_ajax&ajax=1');

        $dispatcher->triggerEvent('onBeforeDisplayLoginView', [&$view]);
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeDisplayRegisterView', [&$view]);
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load("cart");
        $view->set('cart', prepareView($cart));
        //print_r($cart);die;
        $view->set('createOfferLink', SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_offer&guest=1', 1));
        $view->set('loginSaveLink', SEFLink('index.php?option=com_jshopping&controller=user&task=loginsave', 1,0, $jshopConfig->use_ssl));
        $html = JHtml::_('form.token');
        $view->set('kt', $html);

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('step2Link', SEFLink('index.php?option=com_jshopping&controller=qcheckout&view=qcheckout', 1));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));

        if($ajax){ print_r(json_encode($view));die; }
        $view->display();
    }
    
    public function loginsave()
    {
        $jshopConfig = JSFactory::getConfig(); 
        $mainframe = $dispatcher = JFactory::getApplication();
		$dispatcher->triggerEvent('onBeforeLoginSave', []);
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $ajax = JFactory::getApplication()->input->getInt('ajax');
       
        if ($return = JFactory::getApplication()->input->getVar('return', '', 'method', 'base64')) {
            $return = base64_decode($return);
            if (!JSURI::isInternal($return)) {
                $return = '';
            }
        }

        $options = [
            'remember' => $mainframe->input->getBool('remember', false),
            'return' => $return
        ];

        $method = $this->input->getMethod();   
        $credentials = [
            'username' => $mainframe->input->getVar('username', '', 'method', 'username'),
            'password' => (string)$this->input->$method->get('passwd', '', 'RAW')//'password' => $mainframe->input->getString('passwd', '', 'post', JREQUEST_ALLOWRAW)
        ];
        
        $dispatcher->triggerEvent('onBeforeLogin', [&$options, &$credentials]);

        $error = $mainframe->login($credentials, $options);
        setNextUpdatePrices();

        if ($error !== FALSE) {
            if (!$return) {
                $return = JURI::base();
            }
            if($ajax){
                $data['status'] = 1;
                $data['link'] = $return;
                print_r(json_encode($data));die;
            }
            $dispatcher->triggerEvent('onAfterLogin', [&$options, &$credentials]);

            if (empty(JSFactory::getUser()->usergroup_id)) {
                $jUser = JFactory::getUser();
                $modelOfShopUser = JSFactory::getModel('UsersFront');
                if (!$modelOfShopUser->isExistUserId($jUser->id)) {
                    $modelOfShopUser->addNewUser(1, $jUser->name, $jUser->email, $jUser->id, $jUser->name, $jUser->id);
                }
            }

            $mainframe->redirect($return);
        }

        $dispatcher->triggerEvent('onAfterLoginEror', [&$options, &$credentials]);
        if($ajax){
            $data['status'] = 0;
            $data['link'] = SEFLink('index.php?option=com_jshopping&controller=user&task=login&return='  . base64_encode($return), 0, 1, $jshopConfig->use_ssl);
            print_r(json_encode($data));die;
        }
        $mainframe->redirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login&return='  . base64_encode($return), 0, 1, $jshopConfig->use_ssl));
    }

    public function check_return_shop($return){

    }
    
    public function check_user_exist_ajax()
    {
        $username = JFactory::getApplication()->input->getVar('username');
        $email = JFactory::getApplication()->input->getVar('email');
        $modelOfUserFront = JSFactory::getModel('UsersFront');
        $result = $modelOfUserFront->checkUserLoginOrEmailExist($username, $email);
        echo (is_array($result)) ? implode("\n", $result) : '1';
        die;
    }
    
    public function register()
    {
        redirectIfNotGuest();

        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        $document = JFactory::getDocument();
        $session = JFactory::getSession();
        $adv_user = new stdClass();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        
        if (JFactory::getApplication()->input->getInt('lrd')) {
            $adv_user = (object)$session->get('registrationdata');
        }

        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('register');
        if (getThisURLMainPageShop()) {
            appendPathWay(JText::_('COM_SMARTSHOP_REGISTRATION'));

            if (!isset($seodata->title) || empty($seodata->title)) {
                $seodata->title = JText::_('COM_SMARTSHOP_REGISTRATION');
            }         
        }
		setSeoMetaData($seodata->title ?? '');
        $usersConfig = JComponentHelper::getParams('com_users');

        if ($usersConfig->get('allowUserRegistration') == '0') {
           JFactory::getApplication()->enqueueMessage(JText::_('Access Forbidden'), 'error');
            return;
        }        
        
        if (!isset($adv_user->country) || !$adv_user->country) {
            $adv_user->country = $jshopConfig->default_country;
        }        
        $modelOfCountriesFront = JSFactory::getModel('CountriesFront');
        $select_countries = $modelOfCountriesFront->generateCountriesSelectMarkup($adv_user->country)->selectCountries;
        $clientTypeId = $adv_user->client_type ?? 0;
        $dclientTypeId = $adv_user->client_type ?? 0;

        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $select_titles = $modelOfUsersFront->generateClientTitlesSelectMarkup($adv_user->title ?? 0)->selectTitles;
        $select_client_types = $modelOfUsersFront->generateClientTypesSelectMarkup($clientTypeId);
        $dselect_client_types = $modelOfUsersFront->generateClientTypesSelectMarkup($dclientTypeId);

        $config_fields = $modelOfUsersFront->getListFields(1);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayRegister', [&$adv_user]);
        
        filterHTMLSafe($adv_user, ENT_QUOTES);
        
		if ($config_fields['birthday']['display']) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }

		$captcha = $modelOfUsersFront->getCaptchaData();	
        $config_fields_js = $jshopConfig->buildArrayWithFieldsRegisterForJS('register', 1);

        $document->addScriptDeclaration('
        window.addEventListener("load", () => {
            shopUser.setFields('. $config_fields_js. ');
        });
        ');
        loadJSLanguageKeys();

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'register.php')
        ]);

        $layout = getLayoutName('user', 'register');
        $view->setLayout($layout);
        $view->set('component', 'Register');
        $view->set('config', $jshopConfig);
        $view->set('captcha', $captcha);
        $view->set('select_client_types', $select_client_types);
        $view->set('select_titles', $select_titles);
        $view->set('select_countries', $select_countries);
        $view->set('config_fields', $config_fields);
        $view->set('user', $adv_user);
        $view->set('live_path', JURI::base());        
        $view->set('urlcheckdata', '/index.php?option=com_jshopping&controller=user&task=check_user_exist_ajax&ajax=1');    
        $view->set('clientTypeId', $clientTypeId);      
        $view->set('dclientTypeId', $dclientTypeId);
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeDisplayRegisterView', [&$view]);
        $this->sendLangConstantsToJs();
        $clientTitlesOptions = $modelOfUsersFront->generateClientTitlesOptionsMarkup();
       // $markupOptionsCountries = $modelOfCountriesFront->generateCountriesSelectMarkup($adv_user->country)->selectCountries;
        $clientTypesOptions = $modelOfUsersFront->generateClientTypesOptionsMarkup($clientTypeId);
        if($captcha){
            $view->set('captchaHtml', $captcha->display('jshopping_captcha', 'jshopping_captcha', 'jshopping_captcha'));
        }
        $view->set('clientTitlesOptions', $clientTitlesOptions);
        $view->set('clientTypesOptions', $clientTypesOptions);
        $view->set('registerSaveLink', SEFLink('index.php?option=com_jshopping&controller=user&task=registersave', 1, 0, $jshopConfig->use_ssl));
        $view->set('calendarField', (JHTML::_('calendar', $this->user->birthday ?? '', 'birthday', 'birthday', $jshopConfig->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));

        $view->set('jsConfigFields', $config_fields_js);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){ print_r(json_encode($view));die; }
        $view->display();
    }
    
    protected function sendLangConstantsToJs()
    {
        JText::script('COM_SMARTSHOP_REGWARN_TITLE');
        JText::script('COM_SMARTSHOP_REGWARN_NAME');
        JText::script('COM_SMARTSHOP_REGWARN_LNAME');
        JText::script('COM_SMARTSHOP_REGWARN_MNAME');
        JText::script('COM_SMARTSHOP_REGWARN_FIRMA_NAME');
        JText::script('COM_SMARTSHOP_REGWARN_CLIENT_TYPE');
        JText::script('COM_SMARTSHOP_REGWARN_FIRMA_CODE');
        JText::script('COM_SMARTSHOP_REGWARN_TAX_NUMBER');
        JText::script('COM_SMARTSHOP_REGWARN_MAIL');
        JText::script('COM_SMARTSHOP_REGWARN_EMAIL_AGAIN');
        JText::script('COM_SMARTSHOP_REGWARN_BIRTHDAY');
        JText::script('COM_SMARTSHOP_REGWARN_HOME');
        JText::script('COM_SMARTSHOP_REGWARN_APARTMENT');
        JText::script('COM_SMARTSHOP_REGWARN_STREET');
        JText::script('COM_SMARTSHOP_REGWARN_NAME');
        JText::script('COM_SMARTSHOP_REGWARN_ZIP');
        JText::script('COM_SMARTSHOP_REGWARN_CITY');
        JText::script('COM_SMARTSHOP_REGWARN_STATE');
        JText::script('COM_SMARTSHOP_REGWARN_COUNTRY');
        JText::script('COM_SMARTSHOP_REGWARN_PHONE');
        JText::script('COM_SMARTSHOP_REGWARN_MOBIL_PHONE');
        JText::script('COM_SMARTSHOP_REGWARN_FAX');
        JText::script('COM_SMARTSHOP_REGWARN_EXT_FIELD_1');
        JText::script('COM_SMARTSHOP_REGWARN_EXT_FIELD_2');
        JText::script('COM_SMARTSHOP_REGWARN_EXT_FIELD_3');
        JText::script('COM_SMARTSHOP_REGWARN_UNAME');
        JText::script('COM_SMARTSHOP_REGWARN_PASSWORD');
        JText::script('COM_SMARTSHOP_REGWARN_PASSWORD_AGAIN');
        JText::script('COM_SMARTSHOP_QC_CONFIRM_POLICY');
        JText::script('COM_SMARTSHOP_ERROR_DATA');
        JText::script('COM_SMARTSHOP_REGWARN_STREET_NR');
    }
    
    public function registersave()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $jshopConfig = JSFactory::getConfig();
        $params = JComponentHelper::getParams('com_users');
        $lang = JFactory::getLanguage();
        $lang->load('com_users');
        $post = JFactory::getApplication()->input->post->getArray();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $useractivation = $params->get('useractivation');

        if ($params->get('allowUserRegistration') == 0) {
            throw new \Exception(JText::_('Access Forbidden'), 403);
            if($ajax){
                $data['status'] = 403;
                $data['message'] = JText::_('Access Forbidden');
                $data['redirect'] = JURI::base();
                print_r(json_encode($data));die;
            }
            return;
        }

        $post['password2'] = $post['password_2'];
        $modelOfRegistrationFront = JSFactory::getModel('RegistrationFront');
        $modelOfRegistrationFront->registerNewAccount($post, $jshopConfig);

        $message = JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS');
        if ( $useractivation == 2 ) {
            $message  = JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY');
        } elseif ( $useractivation == 1 ) {
            $message  = JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE');
        }
        
        $return = SEFLink('index.php?option=com_jshopping&controller=user&task=login', 1, 1, $jshopConfig->use_ssl);
        if($ajax){
            $data['status'] = 1;
            $data['message'] = $message;
            $data['redirect'] = $return;
            print_r(json_encode($data));die;

        }
        $this->setRedirect($return, $message);
    }
    
    public function activate()
    {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $uParams = JComponentHelper::getParams('com_users');
        $lang =  JFactory::getLanguage();
        $lang->load('com_users');
        jimport('joomla.user.helper');

        if ($user->get('id')) {
            $this->setRedirect('index.php');
            return true;
        }

        if ($uParams->get('useractivation') == 0 || $uParams->get('allowUserRegistration') == 0) {
            throw new \Exception(JText::_('Access JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 403);
            return false;
        }

        $model = JSFactory::getTable('userShop', 'jshop');
        $token = JFactory::getApplication()->input->getVar('token', null, 'request', 'alnum');

        if ($token === null || strlen($token) !== 32) {
            throw new \Exception(JText::_('Access JINVALID_TOKEN'), 403);
            return false;
        }

        $return = $model->activate($token);
        
        \JFactory::getApplication()->triggerEvent('onAfterUserActivate', [&$model, &$token, &$return]);

        if ($return === false) {
            $this->setMessage(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect('index.php');
            return false;
        }

        $useractivation = $uParams->get('useractivation');

        if ($useractivation == 0) {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 0, 1, $jshopConfig->use_ssl));

            return true;
        }elseif ($useractivation == 1) {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS'));
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 0, 1, $jshopConfig->use_ssl));

            return true;
        }elseif ($return->getParam('activate')) {
            $this->setMessage(JText::_('COM_USERS_REGISTRATION_VERIFY_SUCCESS'));
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 0, 1, $jshopConfig->use_ssl));

            return true;
        }

        $this->setMessage(JText::_('COM_USERS_REGISTRATION_ADMINACTIVATE_SUCCESS'));
        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 0, 1, $jshopConfig->use_ssl));

        return true;
    }

    /**
     * @deprecated
     */
    public function editaccount()
    {}

    /**
     * @deprecated
     */
    public function accountsave()
    {}

    public function addresses()
    {
        checkUserLogin();

        setMetaData(JText::_('COM_SMARTSHOP_USER_ADDRESSES'), '', '');
        $document = JFactory::getDocument();
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $shopUser = JSFactory::getUserShop();
        $userAddresses = $modelOfUserAddressesFront->getAllByUserId($shopUser->user_id);
        $ajax = JFactory::getApplication()->input->getInt('ajax');

        if (empty($userAddresses)) {
            raiseMsgsWithOneTypeStatus([Text::_('COM_SMARTSHOP_YOUR_ADDRESS_LIST_CURRENTLY_EMPTY')], 'Info');
        }

        loadJSLanguageKeys();

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'addresses.php')
        ]);

        $layout = getLayoutName('user', 'addresses');
        $view->setLayout($layout);
        $view->set('component', 'Addresses');
		$view->set('configFields', JSFactory::getConfig()->getListFieldsRegister()['address']);



        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('userAddresses', $userAddresses);
        $dispatcher->triggerEvent('onBeforeDisplayAddressesView', [&$view]);
        $view->set('addNewAddressLink', SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress', 1));
        $document->addScriptDeclaration('const dataJson='.json_encode($view));
		
        if($ajax){
            print json_encode(prepareView($view));die;
        }
        $view->display();
    }

    public function addNewAddress()
    {
        checkUserLogin();
        
        $dispatcher = \JFactory::getApplication();
        $post = $this->input->getArray();
        $flashData = JSFactory::getFlashData();
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
            
        setMetaData(JText::_('COM_SMARTSHOP_ADD_NEW_ADDRESS'), '', '');
        
        $modelOfCountriesFront = JSFactory::getModel('CountriesFront');
        $modelOfUsersFront = JSFactory::getModel('UsersFront');

        $flashSavedData = $flashData->get('postOfNewAddressSave');
        $clientTitleId = $flashSavedData['title'] ?? 0;
        $clientTypeId = $flashSavedData['client_type'] ?? 0;
        $dclientTypeId = $flashSavedData['d_client_type'] ?? 0;
        $countryId = $flashSavedData['country'] ?? 0;
        $isCloseTabAfterSave = 0;
		if(isset($post['isCloseTabAfterSave']) && $post['isCloseTabAfterSave']) $isCloseTabAfterSave = $post['isCloseTabAfterSave'];
		elseif(isset($flashSavedData['isCloseTabAfterSave']) && $flashSavedData['isCloseTabAfterSave']) $isCloseTabAfterSave = $flashSavedData['isCloseTabAfterSave'];
		
        $markupSelectList = $modelOfUsersFront->generateClientTitlesSelectMarkup($clientTitleId)->selectTitles;
        $markupSelectCountries = $modelOfCountriesFront->generateCountriesSelectMarkup($countryId)->selectCountries;
        $markupSelectClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($clientTypeId);
        $markupSelectDClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($dclientTypeId);

       $configFields = $modelOfUsersFront->getListFields(2);
        $countFiledDelivery = $jshopConfig->getEnableDeliveryFiledRegistration('address', 2);

		if (!empty($configFields['birthday']['display'])) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }

        $jsConfigFields = $jshopConfig->buildArrayWithFieldsRegisterForJS('address', 2);
        $document->addScriptDeclaration('
        window.addEventListener("load",() => {
            shopUser.setFields('. $jsConfigFields . ');
        });');

        loadJSLanguageKeys();

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'newaddress.php')
        ]);

        $layout = getLayoutName('user', 'newaddress');
        $view->setLayout($layout);
        $view->set('component', 'Newaddress');

		$view->set('config', $jshopConfig);
        $view->set('select_countries', $markupSelectCountries);
        $view->set('select_titles', $markupSelectList);
        $view->set('select_client_types', $markupSelectClientTypes);
        $view->set('live_path', JURI::base());
        $view->set('action', SEFLink('/index.php?option=com_jshopping&controller=user&task=newAddressSave', 0, 0, $jshopConfig->use_ssl));
        $view->set('config_fields', $configFields);
        $view->set('count_filed_delivery', $countFiledDelivery);
        $view->set('flashDataOfNewAddressSave', $flashSavedData);
        $view->set('isCloseTabAfterSave', $isCloseTabAfterSave);
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeDisplayaddNewAddress', [&$view]);

        $clientTitlesOptions = $modelOfUsersFront->generateClientTitlesOptionsMarkup();
        $clientTypesOptions = $modelOfUsersFront->generateClientTypesOptionsMarkup($clientTypeId);

        $view->set('clientTitlesOptions', $clientTitlesOptions);
        $view->set('clientTypesOptions', $clientTypesOptions);
        $view->set('jsConfigFields', $jsConfigFields);
        $view->set('registerSaveLink', SEFLink('index.php?option=com_jshopping&controller=user&task=registersave', 1, 0, $jshopConfig->use_ssl));
        $view->set('calendarField', (JHTML::_('calendar', $this->user->birthday ?? '', 'birthday', 'birthday', $jshopConfig->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));
        $view->set('clientTitlesOptions', $clientTitlesOptions);
        $view->set('clientTypesOptions', $clientTypesOptions);
        $view->set('registerSaveLink', SEFLink('index.php?option=com_jshopping&controller=user&task=registersave', 1, 0, $jshopConfig->use_ssl));
        $view->set('calendarField', (JHTML::_('calendar', $this->user->birthday ?? '', 'birthday', 'birthday', $jshopConfig->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode($view));
        if($ajax){
            print json_encode(prepareView($view));die;
        }
        $view->display();
    }

    public function editAddress()
    {
        checkUserLogin();

        $dispatcher = \JFactory::getApplication();
        $get = $this->input->getArray();
        $flashData = JSFactory::getFlashData();
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $tableOfUserAddress = JSFactory::getTable('UserAddress');
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $modelOfCountriesFront = JSFactory::getModel('CountriesFront');
        $modelOfUsersFront = JSFactory::getModel('UsersFront');

        $tableOfUserAddress->load($get['editId']);
        $flashSavedData = $flashData->get('editAddressSave');

        if (!$modelOfUserAddressesFront->isCurrentUserOwnedAddressId($get['editId']) || !empty($tableOfUserAddress->id)) {
            throw new \Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
        }

        $clientType = 0;
		if(isset($flashSavedData['client_type']) && $flashSavedData['client_type']){
			$clientType = $flashSavedData['client_type'];
		}elseif(isset($tableOfUserAddress->client_type) && $tableOfUserAddress->client_type){			
			$clientType = $tableOfUserAddress->client_type;
		}
        
        $isCloseTabAfterSave = 0;
		if(isset($get['isCloseTabAfterSave']) && $get['isCloseTabAfterSave']){
			$isCloseTabAfterSave = $get['isCloseTabAfterSave'];
		}elseif(isset($flashSavedData['isCloseTabAfterSave']) && $flashSavedData['isCloseTabAfterSave']){
			$isCloseTabAfterSave = $flashSavedData['isCloseTabAfterSave'];
		}

        $tableOfUserAddress->country = $jshopConfig->default_country;
		if(isset($flashSavedData['country']) && $flashSavedData['country']){
			$tableOfUserAddress->country = $flashSavedData['country'];
		}elseif(isset($tableOfUserAddress->country) && $tableOfUserAddress->country){
			$tableOfUserAddress->country = $tableOfUserAddress->country;
		}

		$tableOfUserAddress->birthday = getDisplayDate($tableOfUserAddress->birthday, $jshopConfig->field_birthday_format);
		if(!$tableOfUserAddress->birthday){
			unset($tableOfUserAddress->birthday);
		}
        
        $selectMarkUpTitles = $modelOfUsersFront->generateClientTitlesSelectMarkup($flashSavedData['title'] ?? $tableOfUserAddress->title)->selectTitles;
        $selectMarkUpClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($clientType);
        $selectMarkUpCountries = $modelOfCountriesFront->generateCountriesSelectMarkup($tableOfUserAddress->country)->selectCountries;

        $configFields = $modelOfUsersFront->getListFields(2);

        filterHTMLSafe($tableOfUserAddress, ENT_QUOTES);
   
		if (method_exists('JHtmlBehavior', 'calendar')) {
            JHtmlBehavior::calendar();
        }

        appendPathWay(JText::_('COM_SMARTSHOP_EDIT_ADDRESS'));          
        $this->setPageSeoMetaData('editaccount', 'COM_SMARTSHOP_EDIT_ADDRESS');

        $config_fields_js = $jshopConfig->buildArrayWithFieldsRegisterForJS('address', 2);
        $document->addScriptDeclaration('
        window.addEventListener("load",() => {
            shopUser.setFields('. $config_fields_js . ');
        });');

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'editaddress.php')
        ]);
        $layout = getLayoutName('user', 'editaddress');
        $view->setLayout($layout);
        $view->set('component', 'Editaddress');

		$view->set('config', $jshopConfig);
        $view->set('select_countries', $selectMarkUpCountries);
        $view->set('select_titles', $selectMarkUpTitles);
        $view->set('select_client_types', $selectMarkUpClientTypes);
        $view->set('live_path', JURI::base());
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=user&task=editAddressSave&editId1=' . $tableOfUserAddress->address_id, 0, 0, $jshopConfig->use_ssl));
      
	   $view->set('address', $tableOfUserAddress);
        $view->set('config_fields', $configFields);
        $view->set('isCloseTabAfterSave', $isCloseTabAfterSave);
        $view->set('flashSavedData', $flashSavedData);
        $view->set('clientType', $clientType);
        $view->set('jsConfigFields', $config_fields_js);
        $view->set('clientType', $config_fields_js);
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeEditAddress', [&$view]);

        $clientTitlesOptions = $modelOfUsersFront->generateClientTitlesOptionsMarkup();
        $clientTypesOptions = $modelOfUsersFront->generateClientTypesOptionsMarkup($clientType);

        $view->set('clientTitlesOptions', $clientTitlesOptions);
        $view->set('clientTypesOptions', $clientTypesOptions);
        $view->set('registerSaveLink', SEFLink('index.php?option=com_jshopping&controller=user&task=registersave', 1, 0, $jshopConfig->use_ssl));
        $view->set('calendarField', (JHTML::_('calendar', $tableOfUserAddress->birthday, 'birthday', 'birthday', $jshopConfig->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode($view));
        if(isset($get['ajax']) && $get['ajax']){
            print_r(json_encode($view));die;
        }
        $view->display();
    }

    public function editAddressSave()
    {
        $this->checkToken();
        checkUserLogin();

        $flashData = JSFactory::getFlashData();
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $tableOfUserAddress = JSFactory::getTable('UserAddress');

        $post = $this->input->getArray();
		$UsersFront = JSFactory::getModel('UsersFront');
		$user=$UsersFront->getJoomlaUserById(JSFactory::getUserShop()->user_id);
		$post['email']=$user['email'];
        $editId = $post['editId'] ?: 0;
        $editId1 = $post['editI1'] ?: null;
        $linkToEditAddressPage = SEFLink('index.php?option=com_jshopping&controller=user&task=editAddress&editId=' . $post['editId'], 0, 1, $jshopConfig->use_ssl);
        $linkToMyAccountPage = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 0, 1, $jshopConfig->use_ssl);
        $tableOfUserAddress->load($post['editId']);

        if ($editId !== $editId1 && empty($tableOfUserAddress->address_id)) {
			if($post['ajax']){
                $data['redirectLink'] = $linkToEditAddressPage;
                $data['message'] = JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE');
                $data['status'] = 2;
                print_r(json_encode($data));die;

            }
            return $this->setRedirect($linkToEditAddressPage, JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE'), 'error');
        }

        $isStoredUserAddress = $tableOfUserAddress->bindAndSave($post, JSFactory::getUserShop()->user_id);

        if (!$isStoredUserAddress) {
            $flashData->set('editAddressSave', $post);
            raiseMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), 'error');
			if($post['ajax']){
                $data['redirectLink'] = $linkToEditAddressPage;
                $data['message'] = 'error';
                $data['status'] = 2;
                print_r(json_encode($data));die;
            }
            return $this->setRedirect($linkToEditAddressPage);
        }

        if (!empty($post['isCloseTabAfterSave'])) {
			if($post['ajax']){
				$data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1);
				$data['message'] = JText::_('COM_SMARTSHOP_ADDRESS_SUCCESSFULLY_UPDATED');
				$data['status'] = 1;
				$data['close'] = 1;
				print_r(json_encode($data));die;
			}
            echo '<script>window.close();</script>';
            die;
        }
		if($post['ajax']){
			$data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1);
			$data['message'] = JText::_('COM_SMARTSHOP_ADDRESS_SUCCESSFULLY_UPDATED');
			$data['status'] = 1;
			print_r(json_encode($data));die;
		}

        $dispatcher->triggerEvent('onAfterEditAddressSave', []);
        return $this->setRedirect($linkToMyAccountPage, JText::_('COM_SMARTSHOP_ADDRESS_SUCCESSFULLY_UPDATED'));
    }

    public function newAddressSave()
    {
        $this->checkToken();
        checkUserLogin();

        $flashData = JSFactory::getFlashData();

        $post = $this->input->getArray();
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $userAddressTable = JSFactory::getTable('UserAddress');
        $shopUser = JSFactory::getUserShop();

        $linkToNewAddressPage = SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress', 0, 1, $jshopConfig->use_ssl);
        $linkToMyAccountPage = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 0, 1, $jshopConfig->use_ssl);

        $dispatcher->triggerEvent('onBeforeAddNewAddress', [&$post]);

        $countAddressesOfUser = $userAddressTable->countByUserId($shopUser->user_id);
        $post['is_default'] = empty($countAddressesOfUser) ? 1 : 0;
        $post = $this->input->getArray();
		$UsersFront = JSFactory::getModel('UsersFront');
		$user=$UsersFront->getJoomlaUserById(JSFactory::getUserShop()->user_id);
		$post['email']=$user['email'];
		$isStoredUserAddress = $userAddressTable->bindAndSave($post, $shopUser->user_id);

        if (!$isStoredUserAddress) {
            $flashData->set('postOfNewAddressSave', $post);
            raiseMsgsWithOneTypeStatus($userAddressTable->getErrors(), 'error');
            if($post['ajax']){
                $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress', 1);
                $data['message'] = 'error';
                $data['status'] = 2;
                print_r(json_encode($data));die;
            }
            return $this->setRedirect($linkToNewAddressPage);
        }

        $dispatcher->triggerEvent('onAfterAddNewAddress', [&$userAddressTable, &$isStoredUserAddress]);

        if ($isStoredUserAddress) {

            if (!empty($post['isCloseTabAfterSave'])) {
                echo '<script>window.close();</script>';
                die;
            }
            if($post['ajax']){
                $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1);
                $data['message'] = JText::_('COM_SMARTSHOP_NEW_ADDRESS_WAS_ADDED');
                $data['status'] = 1;
                print_r(json_encode($data));die;
            }
            return $this->setRedirect($linkToMyAccountPage, JText::_('COM_SMARTSHOP_NEW_ADDRESS_WAS_ADDED'));
        }
    }

    public function deleteAddress()
    {
        $this->checkToken('get');
        checkUserLogin();

        $deleteStatus = false;
        $post = $this->input->getArray();
        $deleteId = $post['deleteId'] ?: null;
        $ajax = $post['ajax'] ?: null;
        $jshopConfig = JSFactory::getConfig();
        $linkToAddressPage = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 0, 1, $jshopConfig->use_ssl);
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $tableUserAddress = JSFactory::getTable('UserAddress');
        $tableUserAddress->load($deleteId);

        if (!empty($tableUserAddress->address_id) && $modelOfUserAddressesFront->isCurrentUserOwnedAddressId($deleteId) && empty($tableUserAddress->is_default)) {
            $deleteStatus = $tableUserAddress->delete($deleteId);
        }

        $data = [];
        if ($deleteStatus) {
            if($ajax == 1){
                $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1);
                $data['message'] = JText::_('COM_SMARTSHOP_ADDRESS_DELETED_SUCCESS');
                $data['status'] = 1;
                $data['id'] = $deleteId;
                print_r(json_encode($data));die;
            }
            return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_ADDRESS_DELETED_SUCCESS'));
        }

        if($ajax == 1){
            $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1);
            $data['message'] = 'error';
            $data['status'] = 0;
            print_r(json_encode($data));die;
        }
        return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_ADDRESS_DELETED_FAILED'), 'error');
    }

    public function setDefaultAddress()
    {
        $this->checkToken('get');
        checkUserLogin();

        $status = false;
        $post = $this->input->getArray();
        $defaultId = $post['defaultId'] ?: null;
        $ajax = $post['ajax'] ?: null;
        $isBill = (isset($post['isBill']) && !empty($post['isBill'])) ? true: false;
        $jshopConfig = JSFactory::getConfig();
        $tableOfUserAddress = JSFactory::getTable('UserAddress');
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $linkToAddressPage = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 0, 1, $jshopConfig->use_ssl);
        $tableOfUserAddress->load($defaultId);

        if (!empty($tableOfUserAddress->address_id) && $modelOfUserAddressesFront->isCurrentUserOwnedAddressId($tableOfUserAddress->address_id)) {
            if ($isBill) {
                if (empty($tableOfUserAddress->is_default_bill)) {
                    $status = $tableOfUserAddress->setAsBillDefault();
                }
            } elseif (empty($tableOfUserAddress->is_default)) {
                $status = $tableOfUserAddress->setAsDefault();
            }
        }

        if ($status) {
            if($ajax){
                $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses',1);
                $data['message'] = JText::_('COM_SMARTSHOP_SUCCESSFULLY_UPDATED');
                $data['status'] = 1;
                $data['id'] = $defaultId;
                print_r(json_encode($data));die;
            }
            return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_SUCCESSFULLY_UPDATED'));
        }

        if($ajax){
            $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=user&task=addresses',1);
            $data['message'] = JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE');
            $data['status'] = 0;
            $data['id'] = $defaultId;
            print_r(json_encode($data));die;
        }
        return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE'), 'error');
    }

    public function orders()
    {
        $jshopConfig = JSFactory::getConfig();
        checkUserLogin();
        $user = JFactory::getUser();
        $order = JSFactory::getTable('order', 'jshop');
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        
        appendPathWay(JText::_('COM_SMARTSHOP_MY_ORDERS'));
        $this->setPageSeoMetaData('myorders', 'COM_SMARTSHOP_MY_ORDERS');
        
        $orders = $order->getOrdersForUser($user->id);
        $total = 0;
        foreach($orders as $key => $value) {
            $orders[$key]->order_href = SEFLink('index.php?option=com_jshopping&controller=user&task=order&order_id=' . $value->order_id, 0, 0, $jshopConfig->use_ssl);
            $total += $value->currency_exchange > 0 ? $value->order_total / $value->currency_exchange : $value->order_total;
            $orders[$key]->total = formatprice($value->order_total, $value->currency_code);
            $orders[$key]->order_date = formatdate($value->order_date, 0);
        }
        loadJSLanguageKeys();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeDisplayListOrder', [&$orders]);

        $view = $this->getView('order', getDocumentType(), '', [
            'template_path' => viewOverride('order', 'listorder.php')
        ]);

        $layout = getLayoutName('order', 'listorder');
        $view->setLayout($layout);
        $view->set('component', 'Listorder');

        $view->set('orders', $orders);
        $view->set('image_path', $jshopConfig->live_path . 'images');
        $view->set('total', $total);
        $dispatcher->triggerEvent('onBeforeDisplayOrdersView', [&$view]);

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('linksearch', SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 1));
        $document->addScriptDeclaration('const dataJson='.json_encode($view));
		
		if($ajax){
			print json_encode(prepareView($view));die;
		}
        $view->display();
    }
    
    public function order()
    {
		require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/refund.php';
        $orderStatusModel = JSFactory::getModel('OrdersStatusFront');
		$_storage=JSFactory::getModel('storage');
		$_storage->checkDeleteUploads();
		$document = JFactory::getDocument();
		
        $jshopConfig = JSFactory::getConfig();
        checkUserLogin();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();
        $dispatcher = \JFactory::getApplication();

        appendPathWay(JText::_('COM_SMARTSHOP_MY_ORDERS'), SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 0, $jshopConfig->use_ssl));
        $this->setPageSeoMetaData('myorder-detail', 'COM_SMARTSHOP_MY_ORDERS');
        
        $order_id = JFactory::getApplication()->input->getInt('order_id');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $dispatcher->triggerEvent('onAfterLoadOrder', [&$order, &$user]);
        
        appendPathWay(JText::_('COM_SMARTSHOP_ORDER_NUMBER') . ': ' . $order->order_number);
        
        if ($user->id != $order->user_id) {
            throw new \Exception('Error order number. You are not the owner of this order', 500);
        }
        $linkToAjaxUploadFiles = '/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile';
        $document->addScriptOptions('link_to_ajax_upload_files', $linkToAjaxUploadFiles);
        $order->items = $order->getAllItems();
        $order->weight = $order->getWeightItems();
        $order->status_name = $order->getStatus();
        $order->history = $order->getHistory();
        $allow_cancel = 0;
        if ($jshopConfig->client_allow_cancel_order && $order->order_status != $jshopConfig->payment_status_for_cancel_client && !in_array($order->order_status, $jshopConfig->payment_status_disable_cancel_client)) {
            $allow_cancel = 1;
        }
		
        if ($order->weight == 0 && $jshopConfig->hide_weight_in_cart_weight0) {
            $jshopConfig->show_weight_order = 0;
        }
        
		$order->birthday = getDisplayDate($order->birthday, $jshopConfig->field_birthday_format);
        $order->d_birthday = getDisplayDate($order->d_birthday, $jshopConfig->field_birthday_format);
        //print_r($order->items);die;
        $shipping_method =JSFactory::getTable('shippingMethod', 'jshop');
        $shipping_method->load($order->shipping_method_id);
        
        $name = $lang->get('name');
        $description = $lang->get('description');
        if(isset($order->shippings) && $order->shippings && explode('_', $order->shippings) > 1){
		    $order->shipping_info = JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
        }else{
			$order->shipping_info = $shipping_method->$name;
		}
        
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;
        $order->payment_description = '';
        if ($pm_method->show_descr_in_email) {
            $order->payment_description = $pm_method->$description;
        }
        
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($order->country);
        $field_country_name = $lang->get('name');
        $order->country = $country->$field_country_name;
        
        $d_country = JSFactory::getTable('country', 'jshop');
        $d_country->load($order->d_country);
        $field_country_name = $lang->get('name');
        $order->d_country = $d_country->$field_country_name;
        
        $jshopConfig->user_field_client_type['0'] = '';
        $order->client_type_name = $jshopConfig->user_field_client_type[$order->client_type];
        
        $order->delivery_time_name = '';
        $order->delivery_date_f = '';
        if ($jshopConfig->show_delivery_time_checkout) {
            $deliverytimes = JSFactory::getAllDeliveryTime();
            $order->delivery_time_name = $deliverytimes[$order->delivery_times_id];

            if ($order->delivery_time_name == '') {
                $order->delivery_time_name = $order->delivery_time;
            }
        }

        if ($jshopConfig->show_delivery_date && !datenull($order->delivery_date)) {
            $order->delivery_date_f = formatdate($order->delivery_date);
        }
        
        $order->order_tax_list = $order->getTaxExt();
        $order->order_tax_list_format = $order->getTaxExtFormat();
        $show_percent_tax = 0;
        $hide_subtotal = 0;
        if (count($order->order_tax_list) > 1 || $jshopConfig->show_tax_in_product) {
            $show_percent_tax = 1;
        }

        if ($jshopConfig->hide_tax) {
            $show_percent_tax = 0;
        }
        
        if (($jshopConfig->hide_tax || empty($order->order_tax_list)) && $order->order_discount == 0 && $order->order_payment == 0 && $jshopConfig->without_shipping) {
            $hide_subtotal = 1;
        }
        
        $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL');
        if (($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && (!empty($order->order_tax_list))) {
            $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX');
        }
        
        $tmp_fields = $jshopConfig->getListFieldsRegister();

        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address', 2);
        $order->shipping_info = JSFactory::getModel('OrdersFront')->getOrderShippingsMethodsNames($order);
        $isOrderHasBeenPaid = in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file);
        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
		$uploadData = [];
		$_uploadData = [];

        $_uploadData = array_map(function($product) use($uploadCommonSettings) {
			if (empty($product->uploadData['files'])) {
				$product->uploadData['files'][] = '';
			} ;
            $arr = [
                'upload_common_settings' => $uploadCommonSettings,
                'isSupportUpload' => $product->is_allow_uploads,
                'isMultiUpload' => $product->is_unlimited_uploads,
                'productMaxQty' => (string)$product->productMaxQty,
                'maxFilesUploads' => (string)$product->max_allow_uploads,
                'is_required_upload' => $product->is_required_upload,
                'is_upload_independ_from_qty' => $product->is_upload_independ_from_qty,
                'order_item_id' => $product->order_item_id
            ];
            return $arr;
        }, $order->items);

		foreach($_uploadData as $k=>$val){
			$uploadData[$val['order_item_id']] = $val;
		}
		
        $order_status_for_upload = explode(',',$uploadCommonSettings->order_status_for_upload);
        if(!empty($order_status_for_upload)){
            $isUpoad = in_array($order->order_status, $order_status_for_upload);
        }else{
            $isUpoad = false;
        }

        $order_status_for_return = explode(',',$jshopConfig->order_status_for_return);
        if(!empty($order_status_for_return)){
            $isReturn = in_array($order->order_status, $order_status_for_return);
        }else{
            $isReturn = false;
        }
			
		$refunds = JSFactory::getModel("refund")->getList($order->order_id);
		
        $dispatcher->triggerEvent('onBeforeDisplayOrder', [&$order]);
        loadJSLanguageKeys();

        $view = $this->getView('order', getDocumentType(), '', [
            'template_path' => viewOverride('order', 'order.php')
        ]);

        $layout = getLayoutName('order', 'order');
        $view->setLayout($layout);
        $view->set('component', 'Order');

		
		JSFactory::getModel('storage')->checkFilesForReorder($order);			
        
        $sertainCancellations = $orderStatusModel->getListOrdersStatusWithCertainCancellation();
        $isDisabledCancelOrder = !isset($sertainCancellations[$order->order_status]);
		
		$order->products_pdf=JSFactory::getModel("pdfhubfront")->getOrderPDFs($order);

        $view->set('order', $order);
        $view->set('config', $jshopConfig);
        $view->set('text_total', $text_total);
        $view->set('show_percent_tax', $show_percent_tax);
        $view->set('hide_subtotal', $hide_subtotal);
        $view->set('image_path', $jshopConfig->live_path . 'images');
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('allow_cancel', $allow_cancel);
        $view->set('isDisabledCancelOrder', $isDisabledCancelOrder);
        $view->set('isOrderHasBeenPaid', $isOrderHasBeenPaid);
        $dispatcher->triggerEvent('onBeforeDisplayOrderView', [&$view]);

        $view->set('jsUri', JSFactory::getJSUri());
        $view->set('reorderLink', SEFLink('index.php?option=com_jshopping&controller=repeatorder&order_id=' . $order->order_id, 1));
        $view->set('order_date', formatdate($order->order_date, 0));
        $view->set('order_subtotal', formatprice($order->order_subtotal, $order->currency_code));
        $view->set('order_total', formatprice($order->order_total, $order->currency_code));
        $view->set('order_discount', formatprice(-$order->order_discount, $order->currency_code));
        $view->set('order_shipping', formatprice($order->order_shipping, $order->currency_code));
        $view->set('order_package', formatprice($order->order_package, $order->currency_code));
        $view->set('weight', formatweight($order->weight));
        $view->set('displayTotalCartTaxName', displayTotalCartTaxName($order->display_price));
        $view->set('img_path', $jshopConfig->image_product_live_path);
        $view->set('urlToInvoice', $jshopConfig->pdf_orders_live_path . '/' . $order->pdf_file);
        $view->set('cancelOrderLink', SEFLink('index.php?option=com_jshopping&controller=user&task=cancelorder&order_id=' . $order->order_id));
        $view->set('juri_root', JURI::root());
        $view->set('upload_common_settings', $uploadCommonSettings);
        $view->set('order_status_for_upload', $order_status_for_upload);
        $view->set('isUpoad', $isUpoad);
        $view->set('isReturn', $isReturn);
        $view->set('refunds', $refunds);
        $view->set('link_to_ajax_upload_files', $linkToAjaxUploadFiles);
        $view->set('link_save_order_upload', SEFLink('index.php?option=com_jshopping&controller=user&task=save_order_upload'));

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptOptions('uploadData', $uploadData);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){
            print json_encode(prepareView($view));die;
        }
        $view->display();
    }
    
    public function cancelorder()
    {
        $jshopConfig = JSFactory::getConfig();
        checkUserLogin();
    
        if (!$jshopConfig->client_allow_cancel_order) {
            return 0;
        }
        
        $user = JFactory::getUser();
        $order_id = JFactory::getApplication()->input->getInt('order_id');
        
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $orderStatusModel = JSFactory::getModel('OrdersStatusFront');
        $sertainCancellations = $orderStatusModel->getListOrdersStatusWithCertainCancellation();
        $isDisabledCancelOrder = !isset($sertainCancellations[$order->order_status]);
    
        appendPathWay(JText::_('COM_SMARTSHOP_ORDER_NUMBER') . ': ' . $order->order_number);
        
        if ($user->id != $order->user_id) {
            throw new \Exception('Error order number', 500);
        }
        $status = $jshopConfig->payment_status_for_cancel_client;
        $orderSefLink = SEFLink('index.php?option=com_jshopping&controller=user&task=order&order_id=' . $order_id, 0, 1, $jshopConfig->use_ssl);
        
        if ($order->order_status == $status || in_array($order->order_status, $jshopConfig->payment_status_disable_cancel_client) || $isDisabledCancelOrder) {
            $this->setRedirect($orderSefLink);
            return 0;
        }
        
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->changeStatusOrder($order_id, $status, 1);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterUserCancelOrder', [&$order_id]);
        
        $this->setRedirect($orderSefLink, JText::_('COM_SMARTSHOP_ORDER_CANCELED'));
    }

    public function myaccount()
    {
        $config = JFactory::getConfig();
        $jshopConfig = JSFactory::getConfig();
        checkUserLogin();
        $document = JFactory::getDocument();

        $adv_user = JSFactory::getUserShop();
        $lang = JSFactory::getLang();
        $offerAndOrder = JTable::getInstance('offer_and_order', 'jshop');
        
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($adv_user->country);
        $field_name = $lang->get('name');
        $adv_user->country = $country->$field_name;
        
        $group = JSFactory::getTable('userGroup', 'jshop');
        $group->load($adv_user->usergroup_id);
        $adv_user->groupname = $group->usergroup_name;

        if ($group->$field_name != '') {
            $adv_user->groupname = $group->$field_name;
        }

        $adv_user->discountpercent = floatval($group->usergroup_discount);
        $this->setPageSeoMetaData('myaccount', 'COM_SMARTSHOP_MY_ACCOUNT');
        
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayMyAccount', [&$adv_user, &$config_fields]);
        loadJSLanguageKeys();

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'myaccount.php')
        ]);

        $layout = getLayoutName('user', 'myaccount');
        $view->setLayout($layout);
        $view->set('component', 'Myaccount');

        $view->set('config', $jshopConfig);
        $view->set('user', $adv_user);
        $view->set('config_fields', $config_fields);
        $view->set('url_myoffer_and_order', $offerAndOrder->getUrlMyOfferAndOrder());
        $view->set('href_user_group_info', SEFLink('index.php?option=com_jshopping&controller=user&task=groupsinfo'));
        $view->set('href_edit_data', SEFLink('index.php?option=com_jshopping&controller=user&task=editaccount', 0, 0, $jshopConfig->use_ssl));
        $view->set('href_show_orders', SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 0, $jshopConfig->use_ssl));
        $view->set('href_logout', SEFLink('index.php?option=com_jshopping&controller=user&task=logout'));
        //$dispatcher->triggerEvent('onBeforeDisplayMyAccountView', [&$view]);
        $view->set('addressesLink', SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1));
        $view->set('profileEditLink', SEFLink('index.php?option=com_users&view=profile&layout=edit', 1));
        $view->set('ordersLink', SEFLink('index.php?option=com_jshopping&view=user&task=orders', 1));
        $view->set('offerAndOrderLink', SEFLink('index.php?option=com_jshopping&view=offer_and_order', 1));
        $view->set('wishlistLink', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $view->set('editorWishlistLink', SEFLink('index.php?option=com_expresseditor&task=wishlist&Itemid=894', 1));
        $view->set('logoutLink', SEFLink('index.php?option=com_jshopping&view=user&task=logout', 1));
        $view->set('generatePathLink', SEFLink('index.php?option=com_jshopping&controller=functions&task=generate_link', 1));
        $view->set('isSmartEditorEnabled', isSmartEditorEnabled());
        $view->set('config', $jshopConfig);
        $view->set('user', $adv_user);
        $view->set('config_fields', $config_fields);
        $view->set('url_myoffer_and_order', $offerAndOrder->getUrlMyOfferAndOrder());
        $view->set('href_user_group_info', SEFLink('index.php?option=com_jshopping&controller=user&task=groupsinfo'));
        $view->set('href_edit_data', SEFLink('index.php?option=com_jshopping&controller=user&task=editaccount', 0, 0, $jshopConfig->use_ssl));
        $view->set('href_show_orders', SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 0, $jshopConfig->use_ssl));
        $view->set('href_logout', SEFLink('index.php?option=com_jshopping&controller=user&task=logout'));
        $dispatcher->triggerEvent('onBeforeDisplayMyAccountView', [&$view]);
        $view->set('addressesLink', SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1));
        $view->set('profileEditLink', SEFLink('index.php?option=com_users&view=profile&layout=edit', 1));
        $view->set('ordersLink', SEFLink('index.php?option=com_jshopping&view=user&task=orders', 1));
        $view->set('offerAndOrderLink', SEFLink('index.php?option=com_jshopping&view=offer_and_order', 1));
        $view->set('wishlistLink', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $view->set('editorWishlistLink', SEFLink('index.php?option=com_expresseditor&task=wishlist&Itemid=894', 1));
        $view->set('logoutLink', SEFLink('index.php?option=com_jshopping&view=user&task=logout', 1));
        $view->set('isSmartEditorEnabled', isSmartEditorEnabled());
        $view->set('sef', JFactory::getConfig()->get('sef'));

        $document->addScriptDeclaration('const dataJson='.json_encode($view));

        $view->display();
    }

    public function groupsinfo()
    {
        $jshopConfig = JSFactory::getConfig();
        setMetaData(JText::_('COM_SMARTSHOP_USER_GROUPS_INFO'), '', '');
        $document = JFactory::getDocument();
        
        $group = JSFactory::getTable('userGroup', 'jshop');
        $list = $group->getList();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayGroupsInfo', []);

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'groupsinfo.php')
        ]);

        $layout = getLayoutName('user', 'groupsinfo');
        $view->setLayout($layout);
        $view->set('component', 'Groupsinfo');
        $view->set('rows', $list);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $dispatcher->triggerEvent('onBeforeDisplayGroupsInfoView', [&$view]);
        $document->addScriptDeclaration('const dataJson='.json_encode($view));

        $view->display();
    }
    
    public function logout()
    {		
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $mainframe = JFactory::getApplication();
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLogout', []);

        $error = $mainframe->logout();

        $session = JFactory::getSession();
        $session->set('user_shop_guest', null);
        $session->set('cart', null);

        if ($error !== false) {
            if ($return = JFactory::getApplication()->input->getVar('return', '', 'method', 'base64')) {
                $return = base64_decode($return);
                if (!JURI::isInternal($return)) {
                    $return = '';
                }
            }

            setNextUpdatePrices();

            $dispatcher->triggerEvent('onAfterLogout', []);

            if ($return && !(strpos( $return,'com_user'))) {
				if($ajax){
					print $return;die;
				}
                return $mainframe->redirect($return);
            }
			if($ajax){
				print JURI::base();die;
			}
            $mainframe->redirect(JURI::base());
        }
    }

    protected function setPageSeoMetaData(string $alias, string $pageTitle)
    {
        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData($alias);

        if (empty($seodata->title)) {
            $seodata->title = JText::_($pageTitle);
        }

        setMetaData($seodata->title ?? '', $seodata->keyword ?? '', $seodata->description ?? '');
    }

    public function addressPopup()
    {   
        checkUserLogin();

        JHTML::_('bootstrap.loadCss');
        JHTML::_('bootstrap.framework');

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $shopUser = JSFactory::getUserShop();
		$ajax = JFactory::getApplication()->input->getInt('ajax');
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');

        $jshopConfig = JSFactory::getConfig();

        $this->input->set('tmpl', 'component');
        $context = 'smartshop.front.user.addressPopup';
        $searchText = $this->input->get('search_text', '');
        $addrType = $this->input->get('addrType', '');
        $isSearchReset = (bool)$this->input->get('search_text_reset', false);
        $searchLikeWord = [];

        if ($isSearchReset) {
            $app->setUserState('smartshop.popupSearch', '');
        }

        if (!empty($searchText)) {
            $app->setUserState('smartshop.popupSearch', $searchText);
        } else {
            $searchText = $app->getUserState('smartshop.popupSearch', '');
        }
        
        $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

        if (!empty($searchText)) {
            $searchLikeWord = [
                'word' => "{$searchText}%",
                'byColumns' => [
                    'l_name',
                    'f_name',
                    'street',
                    'street_nr',
                    'zip',
                    'city',
					'firma_name'
                ]
            ];
        }
        $allUserAddresses = $modelOfUserAddressesFront->getAllByUserId($shopUser->user_id, $limitstart, $limit, $searchLikeWord);
        $configFields = JSFactory::getConfig()->getListFieldsRegister()['address'];
        if (!empty($allUserAddresses)) {
            foreach ($allUserAddresses as $key => $address) {
                if (empty($configFields['l_name']['display'])) {
                    $allUserAddresses[$key]->l_name = '';
                }
            }
        }
        $countOfAddresses = $modelOfUserAddressesFront->getCountByUserId($shopUser->user_id, $searchLikeWord);
        $pagination = new JPagination($countOfAddresses, $limitstart, $limit);
        $pagenavdata = _buildPaginationDataObject($countOfAddresses, $limitstart, $limit);
        $addrTypeScr = '';
        if(!empty($addrType)){
            $addrTypeScr = 'parent.shopUserAddressesPopup.setAddressTypeToHandler("'.$addrType.'");';
        }
		$cart = JModelLegacy::getInstance('cart', 'jshop');
		$cart->load("cart");
		$shippingAddressId = $cart->getShippingAddressId();
        $doc->addScriptDeclaration('
            document.addEventListener("DOMContentLoaded", function () {
				parent.shopUserAddressesPopup.setShippingAddresses(' . $shippingAddressId . ');
                parent.shopUserAddressesPopup.setUserAddresses(' . json_encode($allUserAddresses) . ');

                parent.document.addEventListener("visibilitychange", function(e) {
                    var isIframeDisplayes = false;

                    try {
                        isIframeDisplayes = parent.document.querySelector("#userAddressesPopup").style.display == "block";
                    } catch (error) {}

                    if (isIframeDisplayes) {
                        location.reload();
                    }
                });
            });
        ');

        $view = $this->getView('user', getDocumentType(), '', [
            'template_path' => viewOverride('user', 'addresses_modal.php')
        ]);

        $layout = getLayoutName('user', 'addresses_modal');
        $view->setLayout($layout);


        $view->set('component', 'Addressesmodal');
        $view->set('addresses', $allUserAddresses);
        $view->set('pagination', $pagination);
        $view->set('limitstart', $limitstart);
        $view->set('searchText', $searchText);
        $view->set('addrType', $addrType);
		$view->set('configFields', JSFactory::getConfig()->getListFieldsRegister()['address']);

        $view->set('pagesLinks', $pagination->getPagesLinks());
        $view->set('limitBox', $pagination->getLimitBox());
		
        $view->set('addressPopup', "/index.php?option=com_jshopping&controller=user&task=addressPopup&limitstart=".$limitstart);
        $view->set('layoutSearch', JLayouthelper::render('smartshop.helpers.search', ['searchText' => $searchText]) );
        $view->set('addNewAddressLink', SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress&isCloseTabAfterSave=1', 1) );
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('pagenavdata', $pagenavdata);
        $doc->addScriptDeclaration('const dataJson='.json_encode($view));
		if($ajax){
			print json_encode(prepareView($view));die;
		}
        $view->display();
    }

    function save_order_upload(){
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();

        $checkout = JModelLegacy::getInstance('checkout', 'jshop');
        $modelOfOrderItemsNativeUploadsFiles = JSFactory::getModel('OrderItemsNativeUploadsFiles');
        $modelOfOrderItemsNativeUploadsFiles->deleteFilesFromOrder($post['order_id']);

        $modelOfOrderItemsNativeUploadsFiles->insertOrderUploadFiles($post);
        $linkToOrderPage = SEFLink('index.php?option=com_jshopping&controller=user&task=order&order_id='.$post['order_id'], 0, 1, $jshopConfig->use_ssl);

        $checkout->uploadAfterPurchaseMessage($post['order_id']);

        return $this->setRedirect($linkToOrderPage, JText::_('COM_SMARTSHOP_SUCCESSFULLY_UPDATED'));
    }

    
}