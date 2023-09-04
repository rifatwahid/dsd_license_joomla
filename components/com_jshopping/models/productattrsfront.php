<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductAttrsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_attr';

    public function noticeAdminIfALowAmountOfAttrs(): bool
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
        
        $productsModel = JSFactory::getModel('products');
        $productAttrsWithLowStock = JSFactory::getModel('ProductAttrs')->getLowStock();
        $arrWithProdsIdsForSelect = array_keys($productAttrsWithLowStock);
        $prodsModelsWithLowAttrs = $productsModel->getProductsByIds($arrWithProdsIdsForSelect);

        if ( !empty($productAttrsWithLowStock) && !empty($prodsModelsWithLowAttrs) ) {

            $dataForTemplate = compact('productAttrsWithLowStock', 'prodsModelsWithLowAttrs');
            $templateName = 'mailnoticelowamountofattrs';
            $pathesToTemplate = [
                JPATH_ROOT . '/templates/' . JFactory::getApplication()->getTemplate() . '/html/com_jshopping/quick_checkout',
                JPATH_COMPONENT_SITE . '/templates/base/quick_checkout'
            ];

            $bodyEmailText = renderTemplate($pathesToTemplate, $templateName, $dataForTemplate, 'emails');
			$dataForTemplate = array('emailSubject'=>JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_TITLE'), 'emailBod'=>$bodyEmailText);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
            $shMailer = new shMailer();
            return $shMailer->sendMailToAdmin(JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_TITLE'), $bodyEmailText);                          
        } 
        
        return false;
    }

    public function getByProductId(int $productId, array $where = [])
    {
        $where1 = array_merge(['product_id = ' . $productId], $where);

        return $this->select(['*'], $where1, '', false);
    }

    public function getByProductIdAndOrderBy(int $productId, array $where = [], string $orderBy = 'sorting')
    {
        $where1 = array_merge(['product_id = ' . $productId], $where);

        return $this->select(['*'], $where1, ' ORDER BY ' . $orderBy);
    }

    public function getSqlFunctionsResultByProdId(int $productId)
    {
        return $this->select([
            'count(*) as countattr',
            'SUM(count) AS qty',
            'MIN(price) AS min_price',
            'MAX(price) AS max_price',
        ], ['product_id = \'' . $productId . '\''], '', false);
    }

    public function deleteByProdId(int $productId)
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `" . static::TABLE_NAME . "` WHERE `product_id` = '{$db->escape($productId)}'";
        $db->setQuery($query);

        return $db->execute();    
    }

    public function deleteByProdAttrId(int $proddAttrId)
    {
        return $this->deleteByProdAttrIds([$proddAttrId]);
    }

    public function deleteByProdAttrIds(array $attrsIds)
    {
        $db = \JFactory::getDBO();
        $query = 'DELETE FROM `' . static::TABLE_NAME . '` WHERE `product_attr_id` IN (' . implode(', ', $attrsIds) . ')';
        $db->setQuery($query);

        return $db->execute(); 
    }

    public function getByProdIdAndAttrs(int $prodId, array $attrs)
    {
        $where = [
            'product_id = ' . $prodId
        ];

        foreach($attrs as $key => $attr) {
            $where[] = "attr_{$key} = '{$attr}'";
        }

        return $this->select(['*'], $where, '', false);
    }

    public function deleteForEachProdButNotFirstsAttrs(int $attrId)
    {
        if (!empty($attrId)) {
            $db = \JFactory::getDBO();

            $sqlSelect = 'SELECT `product_attr_id`, `product_id` FROM `' . static::TABLE_NAME . '` WHERE `product_attr_id` IN (
                SELECT `product_attr_id` FROM `' . static::TABLE_NAME . '` WHERE `attr_' . $attrId . '` >= 1 GROUP BY `product_id`
            );';

            $items = $db->setQuery($sqlSelect)->loadAssocList();

            if (!empty($items)) {
                $groupByItems = groupByArrayKeyVal($items, 'product_id');
                $idsOfRecords = getSubElementsButNotFirst($groupByItems, 'product_attr_id');
            }

            if (!empty($idsOfRecords)) {
                return $this->deleteByProdAttrIds($idsOfRecords);
            }
        }

        return false;
    }
}
