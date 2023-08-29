<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsVideosFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_videos';

    public function getVideosByProductId(int $productId): array
    {
        return $this->select(['*'], ['product_id = \'' . $productId . '\'']);
    }
}
