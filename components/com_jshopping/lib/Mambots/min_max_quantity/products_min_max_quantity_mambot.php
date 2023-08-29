<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';

class ProductsMinMaxQuantityMambot extends FrontMambot 
{
    protected static $instance;
    
    public function onBeforeDisplayProductListView(&$view)
    {
		foreach ($view->rows as $key=>$produt){
			$min_count = $product->min_count_product ?? 0;
 
			if ($min_count > 0) {
				$view->rows[$key]->default_count_product = $min_count;
			}
		}
    }
	
	public function onBeforeDisplayProductView(&$view)
    {
        $min_count = $view->product->min_count_product;

        if ($min_count > 0) {
            $view->default_count_product = $min_count;
        }
    }
}