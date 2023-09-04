<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelCouponsUsersRestFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_coupons_users_rest';

    public function getDataByUserAndCouponIds(int $userId, int $couponId)
    {
        return $this->select(['*'], [
            '`user_id` = ' . $userId,
            '`coupon_id` = ' . $couponId
        ], '', false);
    }

    public function countCouponsByCouponId(int $couponId)
    {
        return $this->select(['COUNT(`coupon_id`) as count'], [
            "`coupon_id` = '{$couponId}'"
        ], '', false)->count ?: 0;
    }
}
