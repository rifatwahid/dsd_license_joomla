<?php 

require_once __DIR__ . '/InstallerHelper.php';

class DbHelper
{
    public static function updateCheckoutMenuItem()
    {
        $db = \JFactory::getDBO();	
        $sqlUpdate = 'UPDATE `#__menu` SET `alias` = "qcheckout", `path` = "qcheckout", `link` = "index.php?option=com_jshopping&view=qcheckout" WHERE `alias` = "checkout";';
        $db->setQuery($sqlUpdate);

        return $db->execute();
    }

	public static function createTableIfNotExsists($tableName, $query)
	{		
		$db = \JFactory::getDBO();		
		try{
			$db->transactionStart();		
			$db->setQuery($query);
			$result=$db->execute();
			$db->transactionCommit();
			return $result;
		}
		catch (Exception $e){
			$db->transactionRollback();
			JErrorPage::render($e);
		}		  	
	}

	public static function addColumnToTableIfNotExist($tableName, $nameOfColumn, $type)
	{
		$isColumnExistsInTable = static::isTableColumnExists($tableName, $nameOfColumn);

		if ( !$isColumnExistsInTable ) {
			$db = \JFactory::getDBO();
			try{
				$db->transactionStart();
				$query = 'ALTER TABLE ' . $db->qn($tableName) . ' ADD COLUMN ' . $db->qn($nameOfColumn) . ' ' . strtolower($type);
				$db->setQuery($query);
				$result=$db->execute();
				$db->transactionCommit();
				return $result;
			}
			catch (Exception $e){
				$db->transactionRollback();
				JErrorPage::render($e);
			}
    	}

    	return false;
	}

	public static function renameTable($tableName, $tableNameNew)
	{
		$isColumnExistsInTable = static::isTableExists($tableNameNew);

		if (empty($isColumnExistsInTable)) {
			try{
				$db = \JFactory::getDBO();
				$db->transactionStart();
				$query = 'ALTER TABLE ' . $db->qn($tableName) . ' RENAME ' . $db->qn($isColumnExistsInTable) . ';';

				$db->setQuery($query);
				$result=$db->execute();
				$db->transactionCommit();
				
				return $result;
			}
			catch (Exception $e){
				$db->transactionRollback();
				JErrorPage::render($e);
			}
    	}

    	return false;
	}

	public static function isTableExists($tableName) 
	{
	    $db = \JFactory::getDBO();
		try{
			$db->transactionStart();
			$sql = 'SHOW TABLES LIKE "%' . $tableName . '"';
			$db->setQuery($sql);
			$result = $db->loadResult();
			$db->transactionCommit();
			return $result;	    
		}
		catch (Exception $e){
			$db->transactionRollback();
			JErrorPage::render($e);
		}
	}
	
	public static function isTableColumnExists($tableName, $nameOfColumn) 
	{
	    $db = \JFactory::getDBO();
		
		try{
			$db->transactionStart();
			$sql = 'SHOW COLUMNS FROM ' . $db->qn($tableName) . ' LIKE ' . $db->q($nameOfColumn);
			$db->setQuery($sql);
			
			$result=$db->loadResult();
			$db->transactionCommit();

			if ( empty($result) ) {
				return false;
			}
			
			return true;
		}
		catch (Exception $e){
			$db->transactionRollback();
			JErrorPage::render($e);
		}
	}

	public static function getAdminInfo($column)
	{
		$db = \JFactory::getDBO();
		try{
			$db->transactionStart();
			$query = 'SELECT ' . $column . ' FROM #__users AS U LEFT JOIN #__user_usergroup_map AS UM ON UM.user_id = U.id WHERE UM.group_id = "8" ORDER BY U.id';
			$db->setQuery($query);
			$result=$db->loadObject();
			$db->transactionCommit();
			return $result;
		}
		catch (Exception $e){
			$db->transactionRollback();
			JErrorPage::render($e);
		}
	}

	public static function executeSqlFile(string $fileName, $pathtoFile =  __DIR__ . "/../Sql/", $tableName = '', $columnName = '')
	{
		$ifExist = false;
		if($tableName && $columnName){			
			$dbPrefix = JFactory::getDbo()->getPrefix();
			$tableName = str_replace('#__', $dbPrefix, $tableName);				
			$ifExist = static::isTableColumnExists($tableName, $columnName);		
		}
		
		$pathtoFile =  $pathtoFile . "{$fileName}.sql";

		if (!$ifExist && file_exists($pathtoFile)) {
			$fileContent = InstallerHelper::parseFileContent($pathtoFile);

			if (!empty($fileContent)) {	
                $dbPrefix = JFactory::getDbo()->getPrefix();
                $fileContent = str_replace('#__', $dbPrefix, $fileContent);	
				return static::executeQuery($fileContent);
			}
		}

		return false;
		
    }
    
    public static function executeQuery($query, array $pdoOptions = [], string $dbType = 'mysql')
    {
        if (!empty($query) && !empty($dbType)) {
            $config = new JConfig();

            if (empty($pdoOptions)) {
                $pdoOptions = [
                    PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
                ];
            }

            $pdoOptions[PDO::ATTR_EMULATE_PREPARES] = true;

            $dsn = "{$dbType}:host={$config->host};dbname={$config->db}";
            $pdo = new PDO($dsn, $config->user, $config->password, $pdoOptions);

            try {
                return $pdo->prepare($query)->execute();
            } catch (PDOException $e) {
                throw new JDatabaseExceptionExecuting($query, $e->getMessage(), 1, $e);
            }
        }

        return false;
    }

	public static function setMissedColumnsToDb()
	{
        $db = \JFactory::getDBO();
        $prefixOfTables = $db->getPrefix();
        
		$listOfColumnsToAdd = static::getColumnsDataToAdd($prefixOfTables);

		if (!empty($listOfColumnsToAdd)) {			
			foreach($listOfColumnsToAdd as $item) {
				$tableName = $item['0'];
				$columnName = $item['1'];
                $type = $item['2'];
                
				$sql = "CALL addFieldIfNotExists('{$tableName}', '{$columnName}', '{$type}');";
	
				$db->setQuery($sql)->execute();
			}
			
			return true;
		}

		return false;
    }
    
    public static function getSchemaDataByExtId($extensionId = 0): object
    {
        $result = new StdClass();

        if (!empty($extensionId)) {
            $db = \JFactory::getDBO();
            $selectSql = 'SELECT * FROM `#__schemas` WHERE `extension_id` = ' . $extensionId;
            $db->setQuery($selectSql);

            $result = $db->loadObject() ?: $result;
        }

        return $result;
    }

    public static function setSchemaVersionByExtId($extensionId, $newVersion): bool
    {
        $result = false;
		if (empty($extensionId)) $extensionId=0;
        if (!empty($extensionId) && !empty($newVersion)) {
            $db = \JFactory::getDBO();
            $updateSql = 'UPDATE `#__schemas` SET `version_id` = ' . $db->quote($db->escape($newVersion)) . ' WHERE `extension_id` = ' . $db->escape($extensionId);
            $db->setQuery($updateSql);

            $result = $db->execute();
        }

        return $result;
    }

    public static function getDataFromTable(string $tableName, array $columnsName = ['*'], ?array $id = []): array
    {
        $result = [];

        if (!empty($tableName) && !empty($columnsName)) {
            $db = \JFactory::getDBO();
            $sqlSelect = 'SELECT ' . implode(', ', $columnsName) . " FROM {$db->qn($tableName)}";

            if (!empty($id['name']) && !empty($id['value'])) {
                $sqlSelect .= " WHERE {$db->qn($id['name'])} = {$db->escape($id['value'])}";
            }

            $db->setQuery($sqlSelect);
            $result = $db->loadObjectList() ?: $result;
        }

        return $result;
    }

    public static function updateDataTable(string $tableName, array $columnsWithData, string $where = '')
    {
        if (!empty($tableName) && !empty($columnsWithData)) {
            $db = \JFactory::getDBO();
            $setData = [];

            foreach ($columnsWithData as $columnName => $columnValue) {
                $setData[] = "{$db->qn($columnName)} = {$db->q($columnValue)}";
            }

            if (!empty($setData)) {
                $sqlUpdate = "UPDATE {$db->qn($tableName)} SET " . implode(', ', $setData);

                if (!empty($where)) {
                    $sqlUpdate .= " WHERE {$where}";
                }

                $db->setQuery($sqlUpdate);
                return $db->execute();
            }
        }
    }

	protected static function getColumnsDataToAdd(string $prefixOfTables)
	{
		return [
            [
                $prefixOfTables . 'jshopping_free_attr_default_values',
                'min_value',
                'text'
            ],
            [
                $prefixOfTables . 'jshopping_free_attr_default_values',
                'max_value',
                'text'
            ],
            [
                $prefixOfTables . 'jshopping_free_attr_default_values',
                'showFreeAttrInput',
                'BOOLEAN DEFAULT 0'
            ],
            [
                $prefixOfTables . 'users',
                'hide_pd5_password',
                'VARCHAR(100) NOT NULL DEFAULT ""'
            ],
            [
                $prefixOfTables . 'jshopping_orders',
                'projectname',
                'VARCHAR(100) DEFAULT " "'
            ],
            [
                $prefixOfTables . 'jshopping_order_item',
                'product_id_for_order',
                'INT NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'product_linear_price',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'product_mindestpreis',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'product_price_type',
                'int(11) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'product_price_for_qty_type',
                'int(11) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'qtydiscount',
                'int(1) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'min_result',
                'decimal(11,2) NULL'
            ],
            [
                $prefixOfTables . 'jshopping_attr_values',
                'product_linear_price',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products_attr2',
                'price_type',
                'int(11) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_attr_values',
                'product_one_time_cost',
                'BOOLEAN DEFAULT 0'
            ],
            [
                $prefixOfTables . 'jshopping_free_attribute_calcule_price',
                'id',
                'SERIAL PRIMARY KEY'
            ],
            [
                $prefixOfTables . 'jshopping_attr_values',
                'exclude_attribute_for_attribute',
                'TEXT NOT NULL DEFAULT ""'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices',
                'usergroup_prices',
                'INT NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'is_allow_uploads',
                'INT NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'max_allow_uploads',
                'INT NOT NULL DEFAULT "1"'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'is_unlimited_uploads',
                'INT NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_offer_and_order_item',
                'uploaded_files',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'one_time_cost',
                'DOUBLE DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_order_item',
                'total_price',
                'DOUBLE DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_payment_method',
                'usergroup_id',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_shipping_method',
                'usergroup_id',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices_group',
                'price_netto',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices_group',
                'old_price',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices_group',
                'product_is_add_price',
                'tinyint(1) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices_group',
                'add_price_unit_id',
                'int(3) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices',
                'usergroup',
                'int(10) NOT NULL DEFAULT "0"'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices',
                'start_discount',
                'decimal(16,6) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_products_prices',
                'price',
                'decimal(12,2) NOT NULL'
            ],
            [
                $prefixOfTables . 'jshopping_config',
                'order_suffix',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_config',
                'delivery_note_suffix',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_config',
                'offer_and_order_suffix',
                'TEXT'
            ],
            [
                $prefixOfTables . 'jshopping_product_labels',
                'image_es-ES',
                'varchar(255)'
            ],
            [
                $prefixOfTables . 'jshopping_config',
                'delivery_times_on_product_page',
                'INT DEFAULT 0'
            ],
            [
                $prefixOfTables . 'jshopping_config',
                'delivery_times_on_product_listing',
                'INT DEFAULT 0'
            ],
            [
                $prefixOfTables . 'jshopping_products',
                'is_upload_independ_from_qty',
                'TINYINT(1) DEFAULT 0'
            ],

            [
                $prefixOfTables . 'jshopping_updates_info',
                'is_installed_new_media_video_for_product',
                'tinyint(1) NOT NULL DEFAULT 0'
            ],
            [
                $prefixOfTables . 'jshopping_updates_info',
                'is_installed_new_media_img_for_product',
                'tinyint(1) NOT NULL DEFAULT 0'
            ],
			[
                $prefixOfTables . 'jshopping_products',
                'publish_editor_pdf',
                'int(1) NOT NULL DEFAULT "0"'
            ],
        ];
	}
	
	public static function updateRootMenu(){
		
		$db = \JFactory::getDBO();	
		
		$query = 'SELECT `id` FROM `#__menu` WHERE `id` = 1';
        $db->setQuery($query);
		$id = $db->loadResult();
		
		if(!$id){
			$query = 'INSERT INTO #__menu (`id` ,`menutype` ,`title` ,`alias` ,`note` ,`path` ,`link` ,`type` ,`published` ,`parent_id` ,`level` ,`component_id` ,`checked_out` ,`checked_out_time` ,`browserNav` ,`access` ,`img` ,`template_style_id` ,`params` ,`lft` ,`rgt` ,`home` ,`language` ,`client_id` ) 
				VALUES ( "1", "", "Menu_Item_Root", "root", "", "", "", "", "1", "0", "0", "0", "0", "0000-00-00 00:00:00", "0", "0", "", "0", "", "0", "0", "0", "*", "0")';
			$db->setQuery($query);
			$db->execute();
		}
		
		$componentElement = 'com_jshopping';
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__menu'))
			->where($db->quoteName('client_id') . ' = 1 ')
			->where($db->quoteName('type') . ' = ' . $db->quote('component'))
			->where($db->quoteName('component_id') . ' = (SELECT ' . $db->quoteName('extension_id') . ' FROM ' . $db->quoteName('#__extensions') . ' WHERE ' . $db->quoteName('element') . ' = ' . $db->quote($componentElement) . ')');

		$db->setQuery($query);
		$db->execute();
		
	}
}