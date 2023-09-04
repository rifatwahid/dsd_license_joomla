<?php
/**
* @version     4.0.0 20.12.2011
* @author       
* @package     smartSHOP
* @copyright   Copyright (C) 2010. All rights reserved.
* @license     GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/Helpers/DbHelper.php';
require_once __DIR__ . '/Helpers/ProductAttrs.php';
require_once __DIR__ . '/Helpers/InstallerHelper.php';
require_once __DIR__ . '/Helpers/ExtensionHelper.php';
require_once __DIR__ . '/Helpers/ShippingsHelper.php';
require_once __DIR__ . '/Helpers/MediaHelper.php';
require_once __DIR__ . '/Helpers/UpdateHelper.php';

class com_jshoppingInstallerScript
{    
    const UPDATE_TYPE_NAME = 'update';
    const INSTALL_TYPE_NAME = 'install';

    /**
     * Runs just BEFORE any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($route, $adapter)
    {	
		error_reporting(E_ALL ^ E_WARNING); 
		$db = \JFactory::getDBO();
        $db->setQuery("set @@sql_mode = ''");
        $db->execute();
		/*$query=DbHelper::getSqlFile('install');
		try{
			$db->transactionStart();		
			$db->setQuery($query);
			$result=$db->execute();
			$db->transactionCommit();
		}
		catch (Exception $e){
			$db->transactionRollback();
			JErrorPage::render($e);
		}
		*/
        DbHelper::updateRootMenu();	
        DbHelper::executeSqlFile('addFieldIfNotExists');	
        UpdateHelper::copyComMediaTmpl();

        if ($route == self::UPDATE_TYPE_NAME) {
            InstallerHelper::deleteDeprecatedFunctional();
            InstallerHelper::deleteNonExistIndependAttrs();
            UpdateHelper::deleteOrderExport();
            UpdateHelper::deleteSqlFolder();
            UpdateHelper::fixPathForImgs();
            /* Use only for update!! */
            InstallerHelper::fixWrongVersionForNotExistsRelease568();
        }
    }

    /**
     * Runs right AFTER any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function postflight($route, $adapter=null)
    {
        $alternativePathToSourceFolder = $this->getPathToSourceFolder($route, $adapter);
        InstallerHelper::includeShopCoreFiles($alternativePathToSourceFolder);

        switch($route) {
            case self::UPDATE_TYPE_NAME:
                $this->afterUpdate();
            break;

            case self::INSTALL_TYPE_NAME:
                $this->afterInstall();
            break;
        }

        DbHelper::updateCheckoutMenuItem();
        return true;
    }

    protected function afterInstall()
    {
        try {		
            InstallerHelper::prepareShopConfig();

            $session = JFactory::getSession();
            $session->set('jshop_checked_language', []);

            installNewLanguages('en-GB', 0);
            
            InstallerHelper::copySmartshopLangsToJoomla();
            InstallerHelper::prepareFolders();
            InstallerHelper::InstallPluginsData();
            
            ProductAttrs::addSortValForProdsAttrValues();
        } catch (\Exception $e) {
            echo "\n" . $e->getMessage() . ' Method - ' . __METHOD__;
            die;
        }
    }

    protected function afterUpdate()
    {
        try {
            InstallerHelper::addressEmailAlwaysEnabled();

            $tableOfUpdatesInfo = JSFactory::getTable('UpdatesInfo');
            $tableOfUpdatesInfo->load(1);

            InstallerHelper::copySmartshopLangsToJoomla();
            ProductAttrs::clearSortValAttrsTable();
            ProductAttrs::addSortValForProdsAttrValues();
            InstallerHelper::addFieldForProdsPrices();
            InstallerHelper::InstallPluginsData();		
            InstallerHelper::removeBlocksFiles();		
            ShippingsHelper::addTableField();
            ShippingsHelper::addShippingPayments();
            InstallerHelper::removeVernissageFiles();
            //UpdateHelper::deleteViewsTmpls();
            UpdateHelper::addMissingColumns();
            UpdateHelper::deleteTemplateAddons();
            UpdateHelper::deleteTemplateVernissage();

            DbHelper::createTableIfNotExsists('#__jshopping_free_attribute_calcule_price', "
                CREATE TABLE IF NOT EXISTS `#__jshopping_free_attribute_calcule_price` (
                `id` SERIAL PRIMARY KEY, 
                `name` varchar(100) NOT NULL, 
                `params` longtext NOT NULL
            ) AUTO_INCREMENT=2;");
            DbHelper::setMissedColumnsToDb();

            if (empty($tableOfUpdatesInfo->is_moved_free_attr_calc_price)) {
                DbHelper::renameTable('#__free_attribute_calcule_price', '_jshopping_free_attribute_calcule_price');
                InstallerHelper::prepareUpdateFreeAttrCalcPrice();

                $tableOfUpdatesInfo->is_moved_free_attr_calc_price = 1;
                $tableOfUpdatesInfo->store();
            }
            
            if (empty($tableOfUpdatesInfo->is_updated_product_price_preview)) {
                JSFactory::getModel('ProductsFront')->calcPreviewDataForAllProds();
                $tableOfUpdatesInfo->is_updated_product_price_preview = 1;
                $tableOfUpdatesInfo->store();
            }
            
            if (empty($tableOfUpdatesInfo->is_updated_product_price_group)) {
                JSFactory::getModel('ProductsPricesGroupFront')->recalcAllProductsPricesGroup();
                $tableOfUpdatesInfo->is_updated_product_price_group = 1;
                $tableOfUpdatesInfo->store();
            }
            
            if (empty($tableOfUpdatesInfo->is_installed_new_media_img_for_product)) {
                $isSuccessPreparedAndMoved = (new MediaHelper())->prepareImgsAndMoveToNewTable();

                if ($isSuccessPreparedAndMoved) {
                    $tableOfUpdatesInfo->is_installed_new_media_img_for_product = 1;
                    $tableOfUpdatesInfo->store();
                }
            }

            if (empty($tableOfUpdatesInfo->is_installed_new_media_video_for_product)) {
                $isSuccessPreparedAndMoved = (new MediaHelper())->prepareVideosAndMoveToNewTable();

                if ($isSuccessPreparedAndMoved) {
                    $tableOfUpdatesInfo->is_installed_new_media_video_for_product = 1;
                    $tableOfUpdatesInfo->store();
                }
            }
            
            if (empty($tableOfUpdatesInfo->is_copied_user_addresses_to_new_table)) {
                $isSuccessCopiedAddresses = InstallerHelper::copyUsersAddressesDataToUserAddressesTable();
                
                if ($isSuccessCopiedAddresses) {
                    DbHelper::executeSqlFile('delAddressColumnsFromUsersTable');
                    $tableOfUpdatesInfo->is_copied_user_addresses_to_new_table = 1;
                    $tableOfUpdatesInfo->store();
                }
            }
            
            if (empty($tableOfUpdatesInfo->is_copied_orders_addresses_to_new_table)) {
                $isSuccessCopiedOrderAddresses = InstallerHelper::copyOrdersUsersAddressesDataToOrdersAddressesTable();

                if ($isSuccessCopiedOrderAddresses) {
                    DbHelper::executeSqlFile('delAddressColumnsFromOrdersTable');
                    $tableOfUpdatesInfo->is_copied_orders_addresses_to_new_table = 1;
                    $tableOfUpdatesInfo->store();
                }
            }
            DbHelper::updateRegistrationFields();

        } catch(\Exception $e) {
            echo "\n" . $e->getMessage() . ' Method - ' . __METHOD__;
            die;
        }
    }

    protected function getPathToSourceFolder($route, $adapter)
    {
        $pathToSourceFolder = null;

        if (!empty($adapter->getParent()->getPath('source')) && $route == self::UPDATE_TYPE_NAME) {
            $pathToSourceFolder = $adapter->getParent()->getPath('source') . '/site';
        }
		/*
		if (!empty($adapter->get('parent')->getPath('source')) && $route == self::UPDATE_TYPE_NAME) {
            $pathToSourceFolder = $adapter->get('parent')->getPath('source') . '/site';
        }
		*/

        return $pathToSourceFolder;
    }
}