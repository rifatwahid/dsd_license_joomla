<tr>
    <td><?php echo JText::_('_TYPE_CALCULE_PRICE')?></td>
    <td>
        <label><input type="radio" name="params[type_calc_price]" value="0" <?php if ($config['type_calc_price'] == 0) echo "checked"?> /> 
        <?php echo JText::_('_MAXIMUM_PRICE')?></label>
        &nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="params[type_calc_price]" value="1" <?php if ($config['type_calc_price'] == 1) echo "checked"?> /> 
        <?php echo JText::_('_SUM_PRICE')?></label>
        <label><input type="radio" name="params[type_calc_price]" value="2" <?php if ($config['type_calc_price'] == 2) echo "checked"?> /> 
        <?php echo JText::_('_SURCHARGE')?></label>
    </td>
</tr>
<tr>
    <td><?php echo JText::_('_TYPE_CALCULE_PRICE_PACKAGE')?></td>
    <td>
        <label><input type="radio" name="params[type_calc_price_package]" value="0" <?php if ($config['type_calc_price_package'] == 0) echo "checked"?> /> 
        <?php echo JText::_('_MAXIMUM_PRICE')?></label>
        &nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="params[type_calc_price_package]" value="1" <?php if ($config['type_calc_price_package'] == 1) echo "checked"?> /> 
        <?php echo JText::_('_SUM_PRICE')?></label>
        <label><input type="radio" name="params[type_calc_price_package]" value="2" <?php if ($config['type_calc_price_package'] == 2) echo "checked"?> /> 
        <?php echo JText::_('_SURCHARGE')?></label>
    </td>
</tr>
<tr>
    <td><?php echo JText::_('_PRIORITY_SHIPPING')?></td>
    <td><?php echo $select_priority_shipping?></td>
</tr>
<tr>
    <td><?php echo JText::_('_PAYMENT_FILTER')?></td>
    <td>
        <input type="hidden" name="params[payment_filter]" value="0">
        <input type="checkbox" name="params[payment_filter]" value="1" <?php if ($config['payment_filter'] == 1) echo "checked"?> />
    </td>
</tr>
<tr>
    <td><?php echo JText::_('_MULTIPLYING_QTY')?></td>
    <td>
        <input type="hidden" name="params[multiplying_qty]" value="0">
        <input type="checkbox" name="params[multiplying_qty]" value="1" <?php if ($config['multiplying_qty'] == 1) echo "checked"?> />
    </td>
</tr>
