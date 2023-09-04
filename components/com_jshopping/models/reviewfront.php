<?php 

defined('_JEXEC') or die();

class jshopReviewFront extends jshopBase
{
    public function generateRatingMarkup()
    {
        $jshopConfig = JSFactory::getConfig();

        $arr_marks = [];
        $arr_marks[] = JHTML::_('select.option',  '0', JText::_('COM_SMARTSHOP_NOT'), 'mark_id', 'mark_value');

        for ($i = 1; $i <= $jshopConfig->max_mark; $i++) {
            $arr_marks[] = JHTML::_('select.option', $i, $i, 'mark_id', 'mark_value' );
        }

        return JHTML::_('select.genericlist', $arr_marks, 'mark', 'class="inputbox form-select" size="1"', 'mark_id', 'mark_value');
    }

    /**
     * @var null|string $type   null - product, other - manufacturer
     */
    public function isAllowReview(?string $type = null,$product_id = 0): int
    {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();

        if($type) {
            if(!$jshopConfig->allow_reviews_manuf) {
                return 0;
            }
        } else {
            if(!$jshopConfig->allow_reviews_prod) {
                return 0;
            }
        }

        if($jshopConfig->allow_reviews_only_registered && !$user->id) {			
            return -1;
        }
		
		if ($jshopConfig->allow_reviews_only_buyers && !$this->checkUserBuyProduct($product_id)) {
		    return -2;	
		}

        return 1;
    }
	
	private function checkUserBuyProduct($product_id){
		$user = JFactory::getUser();		
        if ($user->id) {   
			$_ordersitemsfront = JSFactory::getModel('ordersitemsfront');			
			return $_ordersitemsfront->checkProductFromUserOrders((int)$user->id,$product_id);			
		}
		return 0;
	}
}