<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

class UpdateHelper 
{
	public static function deleteOrderExport()
	{
		$path = JPATH_ADMINISTRATOR . '/components/com_jshopping/importexport/orderexport';

		if (\JFolder::exists($path)) {
			\JFolder::delete($path);
		}
	}

	public static function copyComMediaTmpl()
	{
		$pathes = Folder::folders(JPATH_ADMINISTRATOR . '/templates', '.', false, true);
		if (!empty($pathes)) {
			foreach ($pathes as $path) {
				$to = $path . '/html/com_media/';
				$from = __DIR__ . '/../Extensions/Templates/Administrator/com_media/';

				Folder::copy($from, $to, '', true);
			}
		}
	}

	public static function fixPathForImgs()
	{
		$db = Factory::getDbo();
		$queryes = [
			'UPDATE `#__jshopping_products` 
				SET `image` = CONCAT("components/com_jshopping/files/img_products/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""',
			'UPDATE `#__jshopping_categories` 
				SET `category_image` = CONCAT("components/com_jshopping/files/img_categories/", `category_image`) 
				WHERE `category_image` NOT LIKE "components%" AND `category_image` NOT LIKE "http%" AND `category_image` != ""',
			'UPDATE `#__jshopping_products_media` 
				SET `media_src` = CONCAT("components/com_jshopping/files/img_products/", `media_src`) 
				WHERE (`media_src` NOT LIKE "components%" AND `media_src` NOT LIKE "http%" AND `media_src` != "") AND `media_src_abstract_type` = 5',
			'UPDATE `#__jshopping_products_media` 
				SET `media_preview` = CONCAT("components/com_jshopping/files/img_products/", `media_preview`) 
				WHERE (`media_preview` NOT LIKE "components%" AND `media_preview` NOT LIKE "http%" AND `media_preview` != "") AND `media_preview_abstract_type` = 5',
			'UPDATE `#__jshopping_products_files` 
				SET 
					`demo` = CONCAT("components/com_jshopping/files/demo_products/", `demo`),
					`file` = CONCAT("components/com_jshopping/files/demo_products/", `file`) 
				WHERE 
					(`demo` NOT LIKE "components%" AND `demo` NOT LIKE "http%" AND `demo` != "") 
					AND
					(`file` NOT LIKE "components%" AND `file` NOT LIKE "http%" AND `file` != "")',
			'UPDATE `#__jshopping_manufacturers` 
				SET `manufacturer_logo` = CONCAT("components/com_jshopping/files/img_manufs/", `manufacturer_logo`) 
				WHERE `manufacturer_logo` NOT LIKE "components%" AND `manufacturer_logo` NOT LIKE "http%" AND `manufacturer_logo` != ""',
			'UPDATE `#__jshopping_payment_method` 
				SET `image` = CONCAT("components/com_jshopping/files/img_payments/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""',
			'UPDATE `#__jshopping_shipping_method_price` 
				SET `image` = CONCAT("components/com_jshopping/files/img_payments/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""',
			'UPDATE `#__jshopping_attr_values` 
				SET `image` = CONCAT("components/com_jshopping/files/img_attributes/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""',
			'UPDATE `#__jshopping_product_labels` 
				SET `image` = CONCAT("components/com_jshopping/files/img_labels/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""',
			'UPDATE `#__jshopping_products_extra_field_values` 
				SET `image` = CONCAT("components/com_jshopping/files/img_characteristics/", `image`) 
				WHERE `image` NOT LIKE "components%" AND `image` NOT LIKE "http%" AND `image` != ""'
		];

		foreach ($queryes as $query) {
			$db->setQuery($query);
			$db->execute();
		}
	}

    public static function deleteViewsTmpls()
    {
        $paths = [
            JPATH_ROOT . '/components/com_jshopping/views/offer_and_order/tmpl',
            JPATH_ROOT . '/components/com_jshopping/views/wishlist/tmpl'
        ];

        foreach ($paths as $path) {
            if (\JFolder::exists($path)) {
                \JFolder::delete($path);
            }
        }
    }

    public static function deleteTemplateAddons()
    {
        $path = JPATH_ROOT . '/components/com_jshopping/templates/addons';
		
        if (\JFolder::exists($path)) {
			if (\JFolder::exists($path.'/smarteditortmpl')) {
				\JFolder::delete($path.'/smarteditortmpl');
			}
			$scanned_addons = array_diff(scandir($path), array('..', '.'));
			if(empty($scanned_addons)){
				\JFolder::delete($path);
			}
		}		
    }

    public static function deleteTemplateVernissage()
    {
        $path = JPATH_ROOT . '/components/com_jshopping/templates/vernissage';
		
        if (\JFolder::exists($path)) {
			if (\JFolder::exists($path)) {
				\JFolder::delete($path);
			}
		}		
    }

    public static function deleteSqlFolder()
    {
        $paths = [
            JPATH_ADMINISTRATOR . '/components/com_jshopping/sql'
        ];

        foreach ($paths as $path) {
            if (\JFolder::exists($path)) {
                \JFolder::delete($path);
            }
        }
    }

    public static function addMissingColumns()
	{
		JTable::addIncludePath(JPATH_SITE . '/components/com_jshopping/tables');

		$addShowCommentBox = function () {
			$config = JSFactory::getTable('Config');
			$fields = $config->getFields();

			if (!isset($fields['show_comment_box'])) {
				$db = \JFactory::getDBO();
				$query = 'ALTER TABLE `#__jshopping_config` ADD COLUMN `show_comment_box` TINYINT(1) NOT NULL DEFAULT 0;';
				$db->setQuery($query);
				$db->execute();
			}
		};

		$addExpirationDate = function () {
			$db = \JFactory::getDBO();
			$productTableFields = JSFactory::getTable('Product')->getFields();
			$productAttrFields = JSFactory::getTable('ProductAttribut')->getFields();
			$productAttr2Fields = JSFactory::getTable('ProductAttribut2')->getFields();
			$template = 'ALTER TABLE `%s` ADD `expiration_date` DATE NULL DEFAULT NULL;';

			if (!isset($productTableFields['expiration_date'])) {
				$db->setQuery(sprintf($template, '#__jshopping_products'));
				$db->execute();
			}

			if (!isset($productAttrFields['expiration_date'])) {
				$db->setQuery(sprintf($template, '#__jshopping_products_attr'));
				$db->execute();
			}

			if (!isset($productAttr2Fields['expiration_date'])) {
				$db->setQuery(sprintf($template, '#__jshopping_products_attr2'));
				$db->execute();
			}
		};

		$addShowCommentBox();
		$addExpirationDate();
	}
}