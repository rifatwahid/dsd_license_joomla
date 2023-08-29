<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsRelationsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_relations';

    public function getRelatedProducts(int $productId): array
    {
        $db = \JFactory::getDBO();
        $table = JSFactory::getTable('Product');
        $productRelated = [];
        $filters = [];
        $advQuery = ''; 
        $advFrom = ''; 
        $orderQuery = 'ORDER BY relation.id';

        $select = $table->getBuildQueryListProductDefaultResult();
        $select = empty($select) ? 'prod.product_id' : 'prod.product_id,' . $select;
        $table->getBuildQueryListProductSimpleList('related', null, $filters, $advQuery, $advFrom, $select);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['related_products', &$select, &$advFrom, &$advQuery, &$orderQuery, &$filters]);

        $query = "SELECT {$select} FROM `#__jshopping_products_relations` AS relation
                INNER JOIN `#__jshopping_products` AS prod ON relation.product_related_id = prod.product_id
                LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = relation.product_related_id
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                {$advFrom}
                WHERE relation.product_id = '{$db->escape($productId)}' AND cat.category_publish='1' AND prod.product_publish = '1' {$advQuery} GROUP BY prod.product_id {$orderQuery}";
        $db->setQuery($query);

        $productRelated = $db->loadObjectList();

        if (!empty($productRelated)) {
            $modelOfProductsFront = JSFactory::getModel('ProductsFront');
            $productRelated = $modelOfProductsFront->buildProductDataOnFly($productRelated, true, true, false);

            foreach($productRelated as $relatedProd) {
                $relatedProd->product_link = SEFLink("index.php?option=com_jshopping&controller=product&task=view&category_id={$relatedProd->category_id}&product_id={$relatedProd->product_id}", 1);
                $relatedProd->product_link = '';
            }

            $productRelated = listProductUpdateData($productRelated, 1);
        }

        return $productRelated;
    }
}