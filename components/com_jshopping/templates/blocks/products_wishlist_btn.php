<?php 
    $wishlistProductId = !empty($product->product_id) ? $product->product_id : $this->product->product_id;
    $wishlistSefLinkToWishlistAdd = (isset($sefLinkToWishlistAdd) && $sefLinkToWishlistAdd) ? $this->sefLinkToWishlistAdd : '';
?>

<button type="submit" class="btn btn-outline-secondary d-grid" onclick="shopHelper.replaceFormActionText('form#productForm-<?php echo $wishlistProductId; ?>', '<?php echo $wishlistSefLinkToWishlistAdd; ?>')">
    <?php echo JText::_('COM_SMARTSHOP_ADD_TO_WISHLIST'); ?>
</button>

<?php 
    unset($wishlistProductId);
    unset($wishlistSefLinkToWishlistAdd);
?>