<?php
/**
* @version      4.7.0 31.05.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerUsers extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask( 'apply', 'save' );
        //checkAccessCloginontroller("users");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("users",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $currentUser = JFactory::getUser();
        $context = "jshopping.list.admin.users";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $text_search = $mainframe->getUserStateFromRequest( $context.'text_search', 'text_search', '' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "u_name", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $_users = JSFactory::getModel("users");
        
        $total = $_users->getCountAllUsers($text_search);
        
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);
        $rows = $_users->getAllUsers($pageNav->limitstart, $pageNav->limit, $text_search, $filter_order, $filter_order_Dir);
        
        $view = $this->getView("users", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);
        $view->set('pageNav', $pageNav);
        $view->set('currentUser', $currentUser);
        $view->set('text_search', $text_search);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayUsers', array(&$view));
        $view->displayList();
    }
    
    function edit(){
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $jshopConfig = JSFactory::getConfig();        
        $me =  JFactory::getUser();
        $user_id = JFactory::getApplication()->input->getInt("user_id");
        $user = JSFactory::getTable('userShop', 'jshop');
        $user->load($user_id);
        $user_site = new JUser($user_id);
        
        $userAddresses = $modelOfUserAddressesFront->getAllByUserId($user_id);
        $defaultUserAddress = getArrsWhereValueEqual($userAddresses, 'is_default', 1)['0'] ?: new StdClass();
        $_countries = JSFactory::getModel("countries");
        $countries = $_countries->getAllCountries(0);
        $lists['country'] = JHTML::_('select.genericlist', $countries,'country','class = "inputbox form-select" size = "1"','country_id','name', $user->country);
        $lists['d_country'] = JHTML::_('select.genericlist', $countries,'d_country','class = "inputbox endes form-select" size = "1"','country_id','name', $user->d_country ?? ''); 
        $user->birthday = getDisplayDate($user->birthday, $jshopConfig->field_birthday_format);
        $user->d_birthday = getDisplayDate($user->d_birthday ?? '', $jshopConfig->field_birthday_format);
        $option_title = array();
        foreach($jshopConfig->user_field_title as $key => $value){
            $option_title[] = JHTML::_('select.option', $key, JText::_($value), 'title_id', 'title_name' );
        }    
        $lists['select_titles'] = JHTML::_('select.genericlist', $option_title,'title','class = "inputbox form-select"','title_id','title_name', $user->title );
        $lists['select_d_titles'] = JHTML::_('select.genericlist', $option_title,'d_title','class = "inputbox endes form-select"','title_id','title_name', $user->d_title ?? '' );
        
        $client_types = array();
        foreach ($jshopConfig->user_field_client_type as $key => $value) {
            $client_types[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'name' );
        }
        $lists['select_client_types'] = JHTML::_('select.genericlist', $client_types,'client_type','class = "inputbox form-select" ','id','name', $user->client_type);

        $_usergroups = JSFactory::getModel("userGroups");
        $usergroups = $_usergroups->getAllUsergroups();
        $lists['usergroups'] = JHTML::_('select.genericlist', $usergroups, 'usergroup_id', 'class = "inputbox form-select" size = "1"', 'usergroup_id', 'usergroup_name', $user->usergroup_id);
        $lists['block'] = JHTML::_('select.booleanlist',  'block', 'class="inputbox form-select" size="1"', $user->block );  
        
        filterHTMLSafe($user, ENT_QUOTES);
        
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields['editaccount'];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('editaccount');
        
        if (method_exists('JHtmlBehavior', 'calendar')) {
            JHtmlBehavior::calendar();
        }
        $view=$this->getView("users", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
		$view->set('config', $jshopConfig);
        $view->set('user', $user);  
        $view->set('me', $me);       
        $view->set('user_site', $user_site);
        $view->set('lists', $lists);
        $view->set('etemplatevar', '');
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('userAddresses', $userAddresses); 
        $view->set('defaultUserAddress', $defaultUserAddress);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditUsers', array(&$view));
        $view->displayEdit();    
    }
    
    public function save() 
    {
        JSFactory::loadLanguageFile();
        $post = JFactory::getApplication()->input->post->getArray();
        $dispatcher = \JFactory::getApplication();
        $editUserId = JFactory::getApplication()->input->getInt('user_id') ?: null;
        $shopUser = JSFactory::getTable('userShop', 'jshop');
        $app = JFactory::getApplication();
        $isNewUser = empty($editUserId);
        $errors = [];
        $error = '';
        $urlToEditUserPage = 'index.php?option=com_jshopping&controller=users&task=edit';
        $urlToEditUserPageId =  $urlToEditUserPage . ($editUserId ? '&user_id=' . $editUserId : '');

        $dispatcher->triggerEvent('onBeforeSaveUser', [&$post]);
        $params = JComponentHelper::getParams('com_users');

        if (empty($post['email']) || empty($post['u_name']) || !isset($post['usergroup_id'])) {
            \JFactory::getApplication()->enqueueMessage(Text::_('COM_SMARTSHOP_ALL_REQUIRED_FIELDS_NOT_FILLED'),'error');
            return $this->setRedirect($urlToEditUserPageId);
        }

        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $userInfoClass = new StdClass();
        $userInfoClass->u_name = $post['u_name'];
        $userInfoClass->user_id = $editUserId ?: 0;
        $userInfoClass->email = $post['email'];
        $userInfoClass->password = $post['password'];
        $userInfoClass->password2 = $post['password2'];
        $userInfoClass->name = $post['u_name'];
        $userInfoClass->block = $post['block'];
        $userInfoClass->number = $post['number'] ?: $shopUser->getNewUserNumber();
		$userInfoClass->credit_limit = $post['credit_limit'];
		$userInfoClass->open_amount = $post['open_amount'];
		$dispatcher->triggerEvent('onBeforeSaveUserSetuserInfoClass', [&$joomlaUser, &$shopUser, &$post, &$userInfoClass]);

        $modelOfUserFieldsCheckerFront = JSFactory::getModel('UserFieldsCheckerFront');
        $modelOfUserFieldsCheckerFront->checkUserName('register', null, [], $userInfoClass, $error, $errors);
        $modelOfUserFieldsCheckerFront->checkUserEmail('', 'newuseraddress', [], $userInfoClass, $error, $errors);

        if (!empty($errors)) {
            return redirectMsgsWithOneTypeStatus($errors, $urlToEditUserPageId, 'error', false);
        }

        $joomlaUser = new JUser($editUserId);
        $joomlaUserData = [
            'password' => $userInfoClass->password,
            'password2' => $userInfoClass->password2,
            'email' => $userInfoClass->email,
            'name' => $userInfoClass->name,
            'username' => $userInfoClass->u_name,
            'block' => $userInfoClass->block,
            'groups' => ($isNewUser ? [$params->get('new_usertype', 2)] : $joomlaUser->groups),
			'credit_limit' => $userInfoClass->credit_limit,
			'open_amount' => $userInfoClass->open_amount
        ];
		$dispatcher->triggerEvent('onBeforeSaveUserSetjoomlaUserData', [&$joomlaUser, &$shopUser, &$post, &$userInfoClass, &$joomlaUserData]);
        $joomlaUser->bind($joomlaUserData);
        $isSavedJoomlaUser = $joomlaUser->save();

        if (!$isSavedJoomlaUser) {
            $isNewUser ? \JFactory::getApplication()->enqueueMessage(Text::_('COM_SMARTSHOP_FAILED_TO_CREATE_NEW_USER')) : \JFactory::getApplication()->enqueueMessage(Text::_('COM_SMARTSHOP_FAILED_TO_UPDATE_THE_USER_DATA'),'error');
            return $this->setRedirect($urlToEditUserPageId);
        }

        $editUserId = $joomlaUser->id;
        $urlToEditUserPageId = $urlToEditUserPage . ($editUserId ? '&user_id=' .  $joomlaUser->id : '');

        if ($isNewUser) {
            $modelOfUsersFront = JSFactory::getModel('UsersFront');
            $isSavedNewShopUser = $modelOfUsersFront->addNewUser($params->get('new_usertype', 2), $userInfoClass->u_name, $userInfoClass->email, $joomlaUser->id, '', $userInfoClass->number);

            if (!$isSavedNewShopUser) {
                $joomlaUser->delete();
                \JFactory::getApplication()->enqueueMessage(Text::_('COM_SMARTSHOP_FAILED_TO_CREATE_NEW_USER'),'error');
                return $this->setRedirect($urlToEditUserPageId);
            }
        } 
        
        $shopUser->load($editUserId);
        
        if (!$isNewUser) {
            $shopUser->email = $userInfoClass->email;
            $shopUser->u_name = $userInfoClass->u_name;
            $shopUser->name = $userInfoClass->u_name;
            $shopUser->username = $userInfoClass->u_name;
            $shopUser->usergroup_id = $post['usergroup_id'];
            $shopUser->number = $userInfoClass->number;
			$shopUser->credit_limit = $userInfoClass->credit_limit;
			$shopUser->open_amount = $userInfoClass->open_amount;
			$dispatcher->triggerEvent('onBeforeSaveUserSetshopUser', [&$joomlaUser, &$shopUser, &$post, &$userInfoClass, &$joomlaUserData]);
            $isStoredShopUser = $shopUser->store();

            if (!$isStoredShopUser) {
                \JFactory::getApplication()->enqueueMessage(Text::_('COM_SMARTSHOP_FAILED_TO_UPDATE_THE_USER_DATA'),'error');
                return $this->setRedirect($urlToEditUserPageId);
            }
        }

        $dispatcher->triggerEvent('onAfterSaveUser', [&$joomlaUser, &$shopUser, &$post]);

        $isNewUser ? $app->enqueueMessage(Text::_('COM_SMARTSHOP_SUCCESS_CREATED_NEW_USER')) : $app->enqueueMessage(Text::_('COM_SMARTSHOP_SUCCESS_UPDATE_DATA_USER'));
        if ($this->getTask() == 'apply') {
            return $this->setRedirect($urlToEditUserPageId);
        }

        return $this->setRedirect('/administrator/index.php?option=com_jshopping&controller=users');
    }

    public function editAddress()
    {
		loadingStatesScripts();
        $get = $this->input->getArray();
        $tableOfUserAddress = JSFactory::getTable('UserAddress');

        if (!empty($tableOfUserAddress->load($get['editId'])->id)) {
            throw new \Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
        }

        $flashData = JSFactory::getFlashData();
        $jshopConfig = JSFactory::getConfig();
		
		$Users = JSFactory::getModel('Users');
		$user=$Users->getJoomlaUserById($get['user_id']);	
		
        $tableOfUserAddress->country = $tableOfUserAddress->country ?: $jshopConfig->default_country;
        $tableOfUserAddress->birthday = getDisplayDate($tableOfUserAddress->birthday, $jshopConfig->field_birthday_format);
        
        $modelOfCountriesFront = JSFactory::getModel('CountriesFront');
        $modelOfUsersFront = JSFactory::getModel('UsersFront');

        $flashDataSavedPost = $flashData->get('postAddressData');
        $clientTitleId = (isset($flashDataSavedPost['title']) && $flashDataSavedPost['title']) ? $flashDataSavedPost['title'] : $tableOfUserAddress->title;
        $countryId = (isset($flashDataSavedPost['country']) && $flashDataSavedPost['country']) ? $flashDataSavedPost['country'] : $tableOfUserAddress->country;
        $clientTypeId =(isset($flashDataSavedPost['client_type']) && $flashDataSavedPost['client_type']) ?  $flashDataSavedPost['client_type'] : $tableOfUserAddress->client_type;

        $selectMarkUpTitles = $modelOfUsersFront->generateClientTitlesSelectMarkup($clientTitleId)->selectTitles;
        $selectMarkUpClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($clientTypeId);
        $selectMarkUpCountries = $modelOfCountriesFront->generateCountriesSelectMarkup($countryId)->selectCountries;

        $configFields = $jshopConfig->getListFieldsRegister()['address'];
        filterHTMLSafe($tableOfUserAddress, ENT_QUOTES);  
   
		if ($configFields['birthday']['display']) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }
		
        $view = $this->getView('users', 'html');
        $view->setLayout('editoraddaddress');        
		$view->set('config', $jshopConfig);
        $view->set('select_countries', $selectMarkUpCountries);
        $view->set('select_titles', $selectMarkUpTitles);
        $view->set('select_client_types', $selectMarkUpClientTypes);
        $view->set('address', $tableOfUserAddress);
        $view->set('config_fields', $configFields);
        $view->set('user_id', $get['user_id']);
        $view->set('flashDataSavedPost', $flashDataSavedPost);
		$view->set('user', $user);
        
        $view->displayEditAddress();
    }

    public function editAddressSave()
    {
        $this->checkToken();

        $post = $this->input->getArray();
        ['user_id' => $forUserId, 'editId' => $editId] = $post;

        $flashData = JSFactory::getFlashData();
        $jshopConfig = JSFactory::getConfig(); 
        $tableOfUserAddress = JSFactory::getTable('UserAddress');
        $linkToEditAddress = $linkToEditUserPage = '/administrator/index.php?option=com_jshopping&controller=users';

        if (!empty($forUserId)) {
            $linkToEditUserPage .= '&task=edit&user_id=' . $forUserId;
            $linkToEditAddress .= "&task=editAddress&user_id={$forUserId}&editId={$editId}";
        }

        if (!$tableOfUserAddress->load($post['editId']) && empty($tableOfUserAddress->address_id)) {
            return $this->setRedirect($linkToEditAddress, JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE'), 'error');
        }
        
        if (!empty($post['birthday'])) {
            $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        }
        
        $isStoredUserAddress = $tableOfUserAddress->bindAndSave($post, $forUserId);

        if (!$isStoredUserAddress) {
            $flashData->set('postAddressData', $post);
            raiseMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), 'error');
            return $this->setRedirect($linkToEditAddress);
        }
        
        return $this->setRedirect($linkToEditUserPage, JText::_('COM_SMARTSHOP_ADDRESS_SUCCESSFULLY_UPDATED'));
    }

    public function addNewAddress()
    {
        $post = $this->input->getArray();
        $jshopConfig = JSFactory::getConfig();
        $modelOfCountriesFront = JSFactory::getModel('CountriesFront');
        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $flashData = JSFactory::getFlashData();
		
		$Users = JSFactory::getModel('Users');
		$user=$Users->getJoomlaUserById($post['user_id']);	

        $flashDataSavedPost = $flashData->get('postAddressData');
        $clientTitleId = (isset($flashDataSavedPost['title']) && $flashDataSavedPost['title']) ? $flashDataSavedPost['title'] : 0;
        $countryId = (isset($flashDataSavedPost['country']) && $flashDataSavedPost['country']) ? $flashDataSavedPost['country'] : 0;
        $clientTypeId = (isset($flashDataSavedPost['client_type']) && $flashDataSavedPost['client_type']) ? $flashDataSavedPost['client_type'] : 0;

        $markupSelectList = $modelOfUsersFront->generateClientTitlesSelectMarkup($clientTitleId)->selectTitles;
        $markupSelectCountries = $modelOfCountriesFront->generateCountriesSelectMarkup($countryId)->selectCountries;
        $markupSelectClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($clientTypeId);

        $configFields = $jshopConfig->getListFieldsRegister()['address'];
        $countFiledDelivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');
                
		if (!empty($configFields['birthday']['display'])) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }

        $view = $this->getView('users', 'html');
        $view->setLayout('editOrAddAddress');         
		$view->set('config', $jshopConfig);
        $view->set('select_countries', $markupSelectCountries);
        $view->set('select_titles', $markupSelectList);
        $view->set('select_client_types', $markupSelectClientTypes);
        $view->set('live_path', JURI::base());
        $view->set('action', SEFLink('/index.php?option=com_jshopping&controller=user&task=newAddressSave', 0, 0, $jshopConfig->use_ssl));
        $view->set('config_fields', $configFields);
        $view->set('count_filed_delivery', $countFiledDelivery);
        $view->set('address', new StdClass());
        $view->set('user_id', $post['user_id']);
        $view->set('flashDataSavedPost', $flashDataSavedPost);
		$view->set('user', $user);

        $view->displayAddNewAddress();
    }

    public function newAddressSave()
    {
        $this->checkToken();

        $post = $this->input->getArray();
        $forUserId = $post['user_id'];
        
        $flashData = JSFactory::getFlashData();
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig(); 
        $userAddressTable = JSFactory::getTable('UserAddress');
        
        $linkToUserEditPage = $linkToAddressPage = '/administrator/index.php?option=com_jshopping&controller=users';

        if (!empty($forUserId)) {
            $linkToAddressPage .= '&task=addNewAddress&user_id=' . $forUserId;
            $linkToUserEditPage .= '&task=edit&user_id='  . $forUserId;
        }

        if (!empty($post['birthday'])) {
            $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        }
        
        $dispatcher->triggerEvent('onBeforeAddNewAddress', [&$post]);

        $countAddressesOfUser = $userAddressTable->countByUserId($forUserId);
        $post['is_default'] = empty($countAddressesOfUser) ? 1 : 0;
        $isStoredUserAddress = $userAddressTable->bindAndSave($post, $forUserId);
        
        if (!$isStoredUserAddress) {
            $flashData->set('postAddressData', $post);
            raiseMsgsWithOneTypeStatus($userAddressTable->getErrors(), 'error');
            return $this->setRedirect($linkToAddressPage);
        }

        $dispatcher->triggerEvent('onAfterAddNewAddress', [&$userAddressTable]);
        return $this->setRedirect($linkToUserEditPage, JText::_('COM_SMARTSHOP_NEW_ADDRESS_WAS_ADDED'));
    }

    public function deleteAddress()
    {
        $this->checkToken('get');

        $deleteStatus = false;
        $post = $this->input->getArray();
        $tableUserAddress = JSFactory::getTable('UserAddress');
        ['deleteId' => $deleteId, 'user_id' => $forUserId] = $post;
        $linkToAddressPage = '/administrator/index.php?option=com_jshopping&controller=users';

        if (!empty($forUserId)) {
            $linkToAddressPage .= '&task=edit&user_id=' . $forUserId;
        }

        if ($tableUserAddress->load($deleteId) && !empty($tableUserAddress->address_id) && empty($tableUserAddress->is_default)) {
            $deleteStatus = $tableUserAddress->delete($deleteId);
        }

        if ($deleteStatus) {
            return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_ADDRESS_DELETED_SUCCESS'));
        }

        return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_ADDRESS_DELETED_FAILED'), 'error');
    }

    public function setDefaultAddress()
    {
        $this->checkToken('get');

        $status = false;
        $post = $this->input->getArray();
        $tableOfUserAddress = JSFactory::getTable('UserAddress');
        ['defaultId' => $defaultId, 'user_id' => $forUserId] = $post;
        $isBill = (isset($post['isBill']) && !empty($post['isBill'])) ? true: false;
        $linkToAddressPage = '/administrator/index.php?option=com_jshopping&controller=users';

        if (!empty($forUserId)) {
            $linkToAddressPage .= '&task=edit&user_id=' . $forUserId;
        }

        if ($tableOfUserAddress->load($defaultId) && !empty($tableOfUserAddress->address_id) && (empty($tableOfUserAddress->is_default) || empty($tableOfUserAddress->is_default_bill))) {
            if ($isBill) {
                if (empty($tableOfUserAddress->is_default_bill)) {
                    $status = $tableOfUserAddress->setAsBillDefault();
                }
            } elseif (empty($tableOfUserAddress->is_default)) {
                $status = $tableOfUserAddress->setAsDefault();
            }
        }
        
        if ($status) {
            return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_SUCCESSFULLY_UPDATED'));
        }

        return $this->setRedirect($linkToAddressPage, JText::_('COM_SMARTSHOP_FAILED_TO_UPDATE'), 'error');
    }
    
    function remove(){
        $mainframe = JFactory::getApplication();
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), '', 'array' );
        $me = JFactory::getUser();
        
        $dispatcher = \JFactory::getApplication();
          
        if (JFactory::getUser()->authorise('core.admin', 'com_jshopping')){ 
            $dispatcher->triggerEvent( 'onBeforeRemoveUser', array(&$cid) );
            JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
            $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');

            foreach($cid as $id){
                $id = (int)$id;

                if ($me->get('id') == $id) {
                    \JFactory::getApplication()->enqueueMessage(JText::_('You cannot delete Yourself!'),'error');
                    continue;
                }
                $user = JUser::getInstance($id);
                $user->delete();
                $mainframe->logout($id);
                $user_shop = JSFactory::getTable('userShop', 'jshop');
                $isUserDeleted = $user_shop->delete($id); 

                if ($isUserDeleted && !empty($id)) {
                    $modelOfUserAddressesFront->deleteByUserId($id);
                }
            }
            $dispatcher->triggerEvent( 'onAfterRemoveUser', array(&$cid) );
        }
        $this->setRedirect("index.php?option=com_jshopping&controller=users");
    }
    
    function publish(){
        $this->blockUser(0);
        $this->setRedirect('index.php?option=com_jshopping&controller=users');
    }
    
    function unpublish(){
        $this->blockUser(1);
        $this->setRedirect('index.php?option=com_jshopping&controller=users');
    }
    
    function blockUser($flag) {                
        $_users = JSFactory::getModel("users");
        $dispatcher = \JFactory::getApplication();
        $cid = JFactory::getApplication()->input->getVar("cid");
        $dispatcher->triggerEvent( 'onBeforePublishUser', array(&$cid, &$flag) );
        foreach ($cid as $key => $value) {
			$_users->setUserBlockById($value,$flag);            
        }                
        $dispatcher->triggerEvent( 'onAfterPublishUser', array(&$cid, &$flag) );
    }
    
    function get_userinfo() {        
		$_users = JSFactory::getModel("users");
        $id = JFactory::getApplication()->input->getInt('user_id');
        if(!$id){
            print '{}';
            die;
        }
        $user=$_users->getUserById($id);        
        echo json_encode((array)$user);
        die();
    }    
    
}
