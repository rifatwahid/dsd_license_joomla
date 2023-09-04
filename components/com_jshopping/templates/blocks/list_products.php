<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

foreach ($this->rows as $k => $product) {
    include templateOverride('list_products', 'product.php');
}
?>
