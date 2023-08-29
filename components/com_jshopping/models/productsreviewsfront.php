<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsReviewsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_reviews';

    public function getReviewsByProductId(int $productId, ?int $publish = 1, int $limitstart = 0, int $limit = 0) 
    {
        $where = [
            'product_id = ' . $productId
        ];

        if (isset($publish)) {
            $where[] = 'publish = ' . $publish;
        }

        return $this->select(['*'], $where, 'ORDER BY review_id DESC', true, $limit, $limitstart);
    }
    
    public function getReviewsCountByProductId(int $productId, ?int $publish = 1)
    {
        $where = [
            'product_id = ' . $productId
        ];

        if (isset($publish)) {
            $where[] = 'publish = ' . $publish;
        }

        $result = $this->select(['count(review_id) as count'], $where, '', false);
        return $result->count ?? null;
    }

    public function getAverageRatingByProductId(int $productId, ?int $publish = 1) 
    {
        $where = [
            'product_id = ' . $productId,
            'mark > 0'
        ];

        if (isset($publish)) {
            $where[] = 'publish = ' . $publish;
        }

        $result = $this->select(['ROUND(AVG(mark),2)  as avg'], $where, '', false);
        return $result->avg ?? null;
    }
}
