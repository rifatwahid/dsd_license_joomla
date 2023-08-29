<form action="/administrator/index.php?option=com_jshopping&controller=products&task=saveDependAttrEditList" method="post" name="adminForm" id="adminForm" class="displayDependAttrEditList">
    <?php 
        $is_use_additional_details = true;
        $parent_id = -1;
        $jshopConfig = $this->jshopConfig;
        $lists = $this->lists;

        require __DIR__ . '/price.php';
        require __DIR__ . '/info.php';
        require __DIR__ . '/media.php';
    ?>

    <input type="hidden" name="task" value="saveDependAttrEditList">
</form>

<script>
    var product_price_precision = <?php echo intval($jshopConfig->product_price_precision); ?>;
</script>