<?php

require_once __DIR__ . '/ExtensionHelper.php';
require_once __DIR__ . '/DbHelper.php';

class InstallerHelper
{
	/**
	 * Use only for preflight - update!!!
	 */
	public static function fixWrongVersionForNotExistsRelease568()
	{
		$db = \JFactory::getDBO();
		$shComponentInfo = JComponentHelper::getComponent('com_jshopping');
		if (!empty($shComponentInfo->id)) {
			$schemaInfo = DbHelper::getSchemaDataByExtId($shComponentInfo->id);
			
			$isInstalledVersionIsWrong = 0;
			$pathtoFile = __DIR__ . "/../../admin/sql/updates/mysql/";
	
			if(!empty($schemaInfo->version_id)){
				if($schemaInfo->version_id == '5.6.8' || $schemaInfo->version_id == '5.6.6'){
					$isInstalledVersionIsWrong = 1;
					$newSchemaVersion = '5.5.37';
				}elseif($schemaInfo->version_id == '5.8.1.1'){
					$isInstalledVersionIsWrong = 1;
					$newSchemaVersion = '5.9.0.1';					
				}elseif($schemaInfo->version_id == '5.8.1.2'){
					$isInstalledVersionIsWrong = 1;
					$newSchemaVersion = '5.9.0.2';					
				}elseif($schemaInfo->version_id == '5.8.1.3'){
					$isInstalledVersionIsWrong = 1;
					$newSchemaVersion = '5.9.0.3';						
				}elseif($schemaInfo->version_id == '5.8.2'){
					$isInstalledVersionIsWrong = 1;
					$newSchemaVersion = '5.9.0.4';			
				}elseif($schemaInfo->version_id == '5.8.2.1'){
					$isInstalledVersionIsWrong = 1;
	
	DbHelper::executeSqlFile('5.9.0.5', $pathtoFile, '#__jshopping_products_extra_field_values', 'image');
	DbHelper::executeSqlFile('5.9.0.6', $pathtoFile, '#__jshopping_products_files', 'demo_descr_de-DE');
	DbHelper::executeSqlFile('5.9.0.7', $pathtoFile, '#__jshopping_config', 'admin_show_product_sale_files');
	DbHelper::executeSqlFile('5.9.0.8', $pathtoFile, '#__jshopping_products', 'equal_steps');
					$newSchemaVersion = '5.9.0.8';				
				}elseif($schemaInfo->version_id == '5.8.2.2'){
					$isInstalledVersionIsWrong = 1;
	DbHelper::executeSqlFile('5.9.0.5', $pathtoFile, '#__jshopping_products_extra_field_values', 'image');
	DbHelper::executeSqlFile('5.9.0.6', $pathtoFile, '#__jshopping_products_files', 'demo_descr_de-DE');
	DbHelper::executeSqlFile('5.9.0.7', $pathtoFile, '#__jshopping_config', 'admin_show_product_sale_files');
	DbHelper::executeSqlFile('5.9.0.8', $pathtoFile, '#__jshopping_products', 'equal_steps');
	DbHelper::executeSqlFile('5.9.0.9', $pathtoFile, '#__jshopping_config', 'display_checkout_button');
					$newSchemaVersion = '5.9.0.10';					
				}elseif($schemaInfo->version_id == '5.8.4.1'){
					$isInstalledVersionIsWrong = 1;
	DbHelper::executeSqlFile('5.9.0.5', $pathtoFile, '#__jshopping_products_extra_field_values', 'image');
	DbHelper::executeSqlFile('5.9.0.6', $pathtoFile, '#__jshopping_products_files', 'demo_descr_de-DE');
	DbHelper::executeSqlFile('5.9.0.7', $pathtoFile, '#__jshopping_config', 'admin_show_product_sale_files');
	DbHelper::executeSqlFile('5.9.0.8', $pathtoFile, '#__jshopping_products', 'equal_steps');
	DbHelper::executeSqlFile('5.9.0.9', $pathtoFile, '#__jshopping_config', 'display_checkout_button');
	DbHelper::executeSqlFile('5.9.0.11', $pathtoFile, '#__jshopping_products', 'one_click_buy');
					$newSchemaVersion = '5.9.0.12';					
				}elseif($schemaInfo->version_id == '5.8.4.2'){
					$isInstalledVersionIsWrong = 1;
	DbHelper::executeSqlFile('5.9.0.5', $pathtoFile, '#__jshopping_products_extra_field_values', 'image');
	DbHelper::executeSqlFile('5.9.0.6', $pathtoFile, '#__jshopping_products_files', 'demo_descr_de-DE');
	DbHelper::executeSqlFile('5.9.0.7', $pathtoFile, '#__jshopping_config', 'admin_show_product_sale_files');
	DbHelper::executeSqlFile('5.9.0.8', $pathtoFile, '#__jshopping_products', 'equal_steps');
	DbHelper::executeSqlFile('5.9.0.9', $pathtoFile, '#__jshopping_config', 'display_checkout_button');
	DbHelper::executeSqlFile('5.9.0.11', $pathtoFile, '#__jshopping_products', 'one_click_buy');
	DbHelper::executeSqlFile('5.9.0.13', $pathtoFile, '#__jshopping_upload', 'order_status_for_upload');
					$newSchemaVersion = '5.9.0.14';					
				}elseif($schemaInfo->version_id == '5.8.4.3' || $schemaInfo->version_id == '5.8.5'){
					$isInstalledVersionIsWrong = 1;
	DbHelper::executeSqlFile('5.9.0.5', $pathtoFile, '#__jshopping_products_extra_field_values', 'image');
	DbHelper::executeSqlFile('5.9.0.6', $pathtoFile, '#__jshopping_products_files', 'demo_descr_de-DE');
	DbHelper::executeSqlFile('5.9.0.7', $pathtoFile, '#__jshopping_config', 'admin_show_product_sale_files');
	DbHelper::executeSqlFile('5.9.0.8', $pathtoFile, '#__jshopping_products', 'equal_steps');
	DbHelper::executeSqlFile('5.9.0.9', $pathtoFile, '#__jshopping_config', 'display_checkout_button');
	DbHelper::executeSqlFile('5.9.0.11', $pathtoFile, '#__jshopping_products', 'one_click_buy');
	DbHelper::executeSqlFile('5.9.0.13', $pathtoFile, '#__jshopping_upload', 'order_status_for_upload');
					$newSchemaVersion = '5.9.0.15';					
				}elseif($schemaInfo->version_id == '5.9.0.23'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					$newSchemaVersion = '5.9.0.25';
				}elseif($schemaInfo->version_id == '5.9.0.24'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					$newSchemaVersion = '5.9.0.26';
				}elseif($schemaInfo->version_id == '5.9.0.25'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					$newSchemaVersion = '5.9.0.27';
				}elseif($schemaInfo->version_id == '5.9.0.36'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					DbHelper::executeSqlFile('5.9.0.28', $pathtoFile, '#__jshopping_users_addresses', 'is_default_bill');
					DbHelper::executeSqlFile('5.9.0.29', $pathtoFile, '#__jshopping_return_status', 'status_id');
					DbHelper::executeSqlFile('5.9.0.30', $pathtoFile, '#__jshopping_return_packages', 'package_status');
					DbHelper::executeSqlFile('5.9.0.31', $pathtoFile, '#__jshopping_return_packages_products', 'package_id');
					DbHelper::executeSqlFile('5.9.0.32', $pathtoFile, '#__jshopping_coupons', 'count_use');
					DbHelper::executeSqlFile('5.9.0.33', $pathtoFile, '#__jshopping_refunds', 'refund_id');
					DbHelper::executeSqlFile('5.9.0.34', $pathtoFile, '#__jshopping_config', 'next_invoice_number');
					DbHelper::executeSqlFile('5.9.0.35', $pathtoFile, '#__jshopping_order_status', 'is_send_refund_note_to_admin');
					$newSchemaVersion = '5.9.0.36';					
				}elseif($schemaInfo->version_id == '6.0.5'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					DbHelper::executeSqlFile('5.9.0.28', $pathtoFile, '#__jshopping_users_addresses', 'is_default_bill');
					DbHelper::executeSqlFile('5.9.0.29', $pathtoFile, '#__jshopping_return_status', 'status_id');
					DbHelper::executeSqlFile('5.9.0.30', $pathtoFile, '#__jshopping_return_packages', 'package_status');
					DbHelper::executeSqlFile('5.9.0.31', $pathtoFile, '#__jshopping_return_packages_products', 'package_id');
					DbHelper::executeSqlFile('5.9.0.32', $pathtoFile, '#__jshopping_coupons', 'count_use');
					DbHelper::executeSqlFile('5.9.0.33', $pathtoFile, '#__jshopping_refunds', 'refund_id');
					DbHelper::executeSqlFile('5.9.0.34', $pathtoFile, '#__jshopping_config', 'next_invoice_number');
					DbHelper::executeSqlFile('5.9.0.35', $pathtoFile, '#__jshopping_order_status', 'is_send_refund_note_to_admin');
					DbHelper::executeSqlFile('5.9.0.37', $pathtoFile, '#__jshopping_products', 'is_activated_price_per_consignment_upload_disable_quantity');
					$newSchemaVersion = '5.9.0.38';	
				}elseif($schemaInfo->version_id == '6.0.5.1'){
					$isInstalledVersionIsWrong = 1;
					DbHelper::executeSqlFile('5.9.0.23', $pathtoFile, '#__jshopping_order_packages', 'package_provider');
					DbHelper::executeSqlFile('5.9.0.24', $pathtoFile, '#__jshopping_config_fields', 'id');
					DbHelper::executeSqlFile('5.9.0.28', $pathtoFile, '#__jshopping_users_addresses', 'is_default_bill');
					DbHelper::executeSqlFile('5.9.0.29', $pathtoFile, '#__jshopping_return_status', 'status_id');
					DbHelper::executeSqlFile('5.9.0.30', $pathtoFile, '#__jshopping_return_packages', 'package_status');
					DbHelper::executeSqlFile('5.9.0.31', $pathtoFile, '#__jshopping_return_packages_products', 'package_id');
					DbHelper::executeSqlFile('5.9.0.32', $pathtoFile, '#__jshopping_coupons', 'count_use');
					DbHelper::executeSqlFile('5.9.0.33', $pathtoFile, '#__jshopping_refunds', 'refund_id');
					DbHelper::executeSqlFile('5.9.0.34', $pathtoFile, '#__jshopping_config', 'next_invoice_number');
					DbHelper::executeSqlFile('5.9.0.35', $pathtoFile, '#__jshopping_order_status', 'is_send_refund_note_to_admin');
					DbHelper::executeSqlFile('5.9.0.37', $pathtoFile, '#__jshopping_products', 'is_activated_price_per_consignment_upload_disable_quantity');
					$newSchemaVersion = '5.9.0.39';	
				}				
			}
						
			if ($isInstalledVersionIsWrong) {
				$isVersionChangedSuccess = DbHelper::setSchemaVersionByExtId($shComponentInfo->id, $newSchemaVersion);

				if ($isVersionChangedSuccess) {
					$isChangeExtCacheManifest = ExtensionHelper::changeExtensionVersion($shComponentInfo->id, $newSchemaVersion);

				}
			}			
			
		}
	}

	public static function deleteNonExistIndependAttrs()
	{
		$db = \JFactory::getDBO();
		$sql = 'DELETE FROM `#__jshopping_products_attr2` WHERE `attr_id` NOT IN (
			SELECT `attr_id` FROM `#__jshopping_attr`
		);';

		$db->setQuery($sql);
		return $db->execute();
	}

	public static function prepareShopConfig()
	{
		$db = \JFactory::getDBO();
		$config = new jshopConfig($db);

        $config->id = 1;
        $config->adminLanguage = JFactory::getLanguage()->getTag();
		$config->defaultLanguage = JComponentHelper::getParams('com_languages')->get('site','en-GB');
		$email_admin = DbHelper::getAdminInfo('email')->email;

        if ($email_admin) {
            $config->contact_email = $email_admin;
		}
		
        $config->securitykey = md5($email_admin . time() . JPATH_SITE);
        return $config->store();
	}

    public static function prepareFolders()
    {
        @chmod(JPATH_SITE . '/components/com_jshopping/files', 0755);

        @mkdir(JPATH_SITE . '/components/com_jshopping/files/img_manufs', 0755);
        @mkdir(JPATH_SITE . '/components/com_jshopping/files/demo_products', 0755);
        @mkdir(JPATH_SITE . '/components/com_jshopping/files/img_attributes', 0755);    
        @mkdir(JPATH_SITE . '/components/com_jshopping/files/pdf_orders', 0755);    

        @chmod(JPATH_SITE . '/components/com_jshopping/files/img_manufs', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/img_categories', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/img_products', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/img_shop_products', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/img_labels', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/video_products', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/files_products', 0755);
        @chmod(JPATH_SITE . '/components/com_jshopping/files/importexport', 0755);        
	}
	
    public static function addFieldForProdsPrices(){
		$db = \JFactory::getDBO();
		
		$sqlToGetAllAttrColumnsFromProdAttr = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
				AND `TABLE_NAME` = '" . $db->getPrefix() . "jshopping_products_prices' ";

		$db->setQuery($sqlToGetAllAttrColumnsFromProdAttr);

		$columns = $db->loadAssocList('', 'COLUMN_NAME') ?: [];
		
		if(is_array($columns) && !empty($columns)) {
			if(!in_array('price', $columns)) {
				$query = "ALTER TABLE `#__jshopping_products_prices`
					ADD COLUMN `price` decimal(12,2)";
				$db->setQuery($query);
				$db->execute(); 
			}

			if(!in_array('start_discount', $columns)) {
				$query = "ALTER TABLE `#__jshopping_products_prices`
					ADD COLUMN `start_discount` decimal(16,6)";
				$db->setQuery($query);
				$db->execute(); 
			}

			$query = "SELECT * FROM `#__jshopping_products_prices` WHERE 1";
			$db->setQuery($query);
			$list = $db->loadObjectList();

			if(!empty($list)) {
				foreach($list as $key => $val) {
					if(!empty($val->discount)) {
						$query = "UPDATE `#__jshopping_products_prices` SET `start_discount` = {$db->quote($val->discount)} WHERE `price_id` = {$val->price_id}";
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
	}

	public static function prepareUpdateFreeAttrCalcPrice()
    {
        $db = \JFactory::getDBO();   
        $query = "SELECT `params` FROM `#__jshopping_addons` WHERE `alias` = 'addon_free_attribute_calcule_price'";
        $db->setQuery($query);
		$addonOldParams = $db->loadResult();
		
		$query = "SELECT `params` FROM `#__jshopping_free_attribute_calcule_price` ORDER BY `id` ASC LIMIT 1";
		$db->setQuery($query);
		$addonNewParams = $db->loadResult();
		
        if (empty($addonNewParams) && !empty($addonOldParams)) {
            $addonOldUnserializedParams = unserialize($addonOldParams);
            $addonOldUnserializedParams['pricetypes_formula']['100500'] = 1;
            $addonOldUnserializedParams['pricetypes_formula_name']['100500'] = 'One-time cost';
            $addonOldParams = serialize($addonOldUnserializedParams);

            $query = "INSERT INTO `#__jshopping_free_attribute_calcule_price` SET `name` = 'free_attribute', `params` = " . $db->quote($addonOldParams);
			$db->setQuery($query);
			$db->execute();  
		}
	}
	
	public static function copySmartshopLangsToJoomla()
	{
		jimport('joomla.filesystem.folder');				

		$smartShopLangs = scandir('../components/com_jshopping/language/');
		foreach($smartShopLangs as $key => $langName) {
			if ($langName != '.' && $langName != '..' && $langName != 'index.html') {	
                $joomlaLangFolder = "../language/{$langName}/";

                if (!file_exists($joomlaLangFolder)) {
                    mkdir($joomlaLangFolder);
                }
                
				JFolder::copy("../components/com_jshopping/language/{$langName}/", $joomlaLangFolder, '', true);		
			}
		}
	}

	public static function parseFileContent(string $pathOfFile)
	{
		$result = '';

		if (file_exists($pathOfFile)) {
			$fileData = file_get_contents($pathOfFile);
			$fileData = trim($fileData);

			if (!empty($fileData)) {
				$result = $fileData;
			}
		}

		return $result;
	}

	public static function InstallPluginsData()
	{
		static::prepareExtensionsFolders();
		$result = [];

		$result['smartshop_system'] = ExtensionHelper::installToJoomla([
            'name' => 'SmartShop System',
            'type' => 'plugin',
            'element' => 'smartshop_system',
            'folder' => 'system',
            'client_id' => '0',
            'enabled' => '1',
            'access' => '1',
            'protected' => '0',
			'manifest_cache' => '',
			'params' => ''
        ], 1);

		$result['offer_and_order'] = ExtensionHelper::installToJoomla([
            'name' => 'Offer and Order',
            'type' => 'plugin',
            'element' => 'offer_and_order',
            'folder' => 'authentication',
            'client_id' => '0',
            'enabled' => '1',
            'access' => '1',
            'protected' => '0',
			'manifest_cache' => '',
			'params' => ''
        ], 1);

		return $result;
	}

	public static function prepareExtensionsFolders()
	{
		\JFolder::copy( __DIR__ . '/../Extensions/Plugins/authentication/offer_and_order/', JPATH_SITE . '/plugins/authentication/offer_and_order', '', true);
		\JFolder::copy( __DIR__ . '/../Extensions/Plugins/system/smartshop_system/', JPATH_SITE . '/plugins/system/smartshop_system', '', true);
	}

	public static function includeShopCoreFiles(?string $alternativePathToSourceFolder = null): void
	{
		$pathToSource = $alternativePathToSourceFolder ?: (JPATH_SITE . '/components/com_jshopping');

		JModelLegacy::addIncludePath($pathToSource . '/models');
		JTable::addIncludePath($pathToSource . '/tables');

		if (file_exists(JPATH_SITE . '/components/com_jshopping/lib/factory.php')) require_once JPATH_SITE . '/components/com_jshopping/lib/factory.php';
		if (file_exists($pathToSource . '/lib/functions.php')) require_once $pathToSource . '/lib/functions.php';
	}	

	public static function copyUsersAddressesDataToUserAddressesTable(): bool
	{
		static::includeShopCoreFiles();
		$modelOfUsersFront = JSFactory::getModel('UsersFront');
		$allShopUsers = $modelOfUsersFront->getAll();

		if (!empty($allShopUsers)) {
			foreach ($allShopUsers as $user) {
				$tableOfUserAddress = JSFactory::getTable('UserAddress');
				$user->is_default = true;
				$tableOfUserAddress->bind($user);
				$tableOfUserAddress->store();

				unset($tableOfUserAddress);
			}
		}

		return true;
	}

	public static function copyOrdersUsersAddressesDataToOrdersAddressesTable(): bool
	{
		static::includeShopCoreFiles();
		$modelOfOrdersFront = JSFactory::getModel('OrdersFront');
		$allOrders = $modelOfOrdersFront->getAll();

		if (!empty($allOrders)) {
			foreach ($allOrders as $order) {
				$tableOfUserOrderAddresses = JSFactory::getTable('OrderAddress');
				$isBinded = $tableOfUserOrderAddresses->bind($order);
				$isStored = $tableOfUserOrderAddresses->store();

				if (!$isBinded || !$isStored) {
					throw new \Exception('Failed to move order data to new table. Order id - ' . $order->order_id);
				}

				$modelOfOrdersFront->setOrderAddressIdById($order->order_id, $tableOfUserOrderAddresses->id);
				unset($tableOfUserOrderAddresses);
			}
		}

		return true;
	}

	public static function deleteDeprecatedFunctional()
	{
		JFile::delete(JPATH_ROOT . '/components/com_jshopping/views/vendor');
		JFile::delete(JPATH_ROOT . '/components/com_jshopping/tables/checkout.php');
	}

	public static function addressEmailAlwaysEnabled() 
	{
		$configColumns = DbHelper::getDataFromTable('#__jshopping_config', ['id', 'fields_register']);

		if (!empty($configColumns)) {
			foreach ($configColumns as $columns) {
				if (!empty($columns->id) && !empty($columns->fields_register)) {
					$fieldsRegister = unserialize($columns->fields_register);

					$fieldsRegister['address']['email']['require'] = 1;
					$fieldsRegister['address']['email']['display'] = 1;

					$fieldsRegister = serialize($fieldsRegister);

					DbHelper::updateDataTable('#__jshopping_config', [
						'fields_register' => $fieldsRegister
					], "`id` = {$columns->id}");
				}
			}
		}
	}
	
	public static function removeBlocksFiles(){
		$pathBase = JPATH_SITE . '/components/com_jshopping/templates/base/';
		$pathVernissage = JPATH_SITE . '/components/com_jshopping/templates/vernissage/';
		$files = array('atributes/sprint_atribute.php','atributes/sprint_free_atribute.php','atributes/sprint_free_extra_fields.php', 'cart/cart_upload.php',
			'category/smarteditorlink.php','category/smarteditorlink.php','content/print_content.php','elements/cart_product.php','elements/currency_code.php',
			'elements/native_upload_js_template.php','elements/native_uploads_previews.php','elements/price_per_consigments_prices_list.php','elements/show_mark_star.php','elements/sprint_checkbox_list.php',
			'elements/sprint_hidden_list.php','elements/sprint_radio_list.php','list_products/block_pagination.php','list_products/list_products.php','list_products/no_products.php',
			'list_products/prices_product.php','list_products/wishlist_btn.php','offer_and_order/fast_admin_links.php','offer_and_order/form_create_offer.php',
			'product/attributes.php','product/bulk_prices.php','product/code.php','product/default_prod_tablist.php','product/default_prod_upload.php','product/demofiles.php',
			'product/default_prod_tablist_stone.php','product/demofiles_stone.php','product/extra_fields.php','product/free_attribute.php','product/media_product_block.php',
			'product/prices.php','product/ratingandhits.php','product/related.php','product/review.php','product/review_rating.php','product/review_upload.php','product/stone_attributes.php','product/stone_free_attribute.php',
			'product/wishlist_btn.php','quick_checkout/address.php','quick_checkout/address_fields.php','quick_checkout/address_handling.php','quick_checkout/payments.php',
			'quick_checkout/previewfinish.php','quick_checkout/shippings.php','quick_checkout_cart/cartproduct.php','search/characteristics.php');
		
		foreach($files as $file){
			if(file_exists($pathBase.$file)){
				unlink($pathBase.$file);
			}
			if(file_exists($pathVernissage.$file)){
				unlink($pathVernissage.$file);
			}
		}
		
		$folders = array('atributes', 'elements');
		
		foreach($folders as $folder){
			if (is_dir($pathBase.$folder)) {
				rmdir($pathBase.$folder);
			}
			if (is_dir($pathVernissage.$folder)) {
				rmdir($pathVernissage.$folder);
			}
		}
	}
	
	public static function removeVernissageFiles(){
		$path = JPATH_SITE . '/components/com_jshopping/templates/vernissage/';
		$folders = array('cart', 'content', 'emails', 'list_products', 'manufacturer', 'offer_and_order', 'order', 'pdf', 'products', 'quick_checkout',	'quick_checkout_cart', 'search', 'user');
		foreach($folders as $dir){
			if(is_dir($path.$dir)){
				InstallerHelper::dirDel($path.$dir);
			}
		}
	}
	
	public static function dirDel($dir){		
		$includes = new FilesystemIterator($dir);

		foreach ($includes as $include) {

			if(is_dir($include) && !is_link($include)) {

				InstallerHelper::dirDel($include);
			}

			else {

				unlink($include);
			}
		}

		rmdir($dir);    
	}
}