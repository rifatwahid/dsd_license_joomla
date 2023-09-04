<script type="text/javascript">
    var attrib_ids = new Array();
    var attrib_types = new Array();
    var attrib_exist = new Object();

    <?php $i=0; foreach($lists['all_attributes'] as $key => $value) :  $lists['all_attributes'][$key]->block = 1; ?>
        attrib_ids[<?php echo $i;?>]="<?php echo $value->attr_id; ?>";            
        attrib_types[<?php echo $i++;?>]="<?php echo $value->attr_type; ?>";            
    <?php endforeach; ?>

    <?php
        $attr_tmp_row_num=0;

        if (!empty($lists['attribs'])) {
            foreach($lists['attribs'] as $k=>$v) {
                $attr_tmp_row_num++;
                echo "attrib_exist[".$attr_tmp_row_num."]={};\n";

                foreach($lists['all_attributes'] as $key=>$value) {
                    $tmp_field="attr_".$value->attr_id;
                    $tmp_val=$v->$tmp_field;
					if( $tmp_val) $lists['all_attributes'][$key]->block = 0; 
                    echo "attrib_exist[".$attr_tmp_row_num."][".$value->attr_id."]='".$tmp_val."';\n";
                }
            
            }
        }   

        echo "var attr_tmp_row_num=$attr_tmp_row_num;\n";
    ?>       
</script>

<div class="table-responsive product--depend-attrs">
    <table class="table table-striped" id="list_attr_value">
        <thead>
            <tr>
                <th scope="col" width="15"></th>
                <?php 
					foreach($lists['all_attributes'] as $key => $value) : ?>
					<?php $style = $value->block ? 'display:none' : '';?>
                    <th scope="col" class="col_attr_<?php echo $value->attr_id; ?>" width="120" style="<?php echo $style; ?>">
                        <?php echo $value->name; ?>
                    </th>
                <?php endforeach; ?>

                <th scope="col" width="120">
                    <?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
                </th>

                <?php echo $this->dep_attr_td_header ?? ''; ?>

                <?php if ($jshopConfig->stock) : ?>            
                    <th scope="col" width="120">
                        <?php echo JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT'); ?>
                    </th>
                    <th scope="col" width="120">
                        <?php echo JText::_('COM_SMARTSHOP_LOW_STOCK_ATTR_NOTIFY'); ?>
                    </th>
                <?php endif; ?>

                <th scope="col" width="120">
                    <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
                </th>

                <th scope="col" width="120">
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT') . '(' . sprintUnitWeight() . ')'; ?>
                </th>

                <th scope="col" width="120">
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE'); ?>
                </th>

                <th scope="col" width="120">
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCTION_TIME'); ?>
                </th>

                <?php if ($jshopConfig->admin_show_product_basic_price) : ?>
                    <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_WEIGHT_VOLUME_UNITS'); ?></th>
                <?php endif; ?>

                <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_OLD_PRICE'); ?></th>

                <?php if ($jshopConfig->admin_show_product_bay_price) : ?>
                    <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE'); ?></th>
                <?php endif; ?>

                <th scope="col" ></th>

                <th scope="col" width="60">
                    <input type='checkbox' id='ch_attr_delete_all' class="form-check-input" onclick="shopProductAttribute.selectList(this.checked)">
                </th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($lists['attribs'])) : $attr_tmp_row_num = 0; ?>
                <?php foreach($lists['attribs'] as $k => $v) : $attr_tmp_row_num++; ?>
                    <tr id='attr_row_<?php echo $attr_tmp_row_num; ?>'>
                        <td>
                            <span class='icon-menu' aria-hidden='true'></span>
                        </td>

                        <?php foreach ($lists['all_attributes'] as $key => $value) : 
                            $tmp_field = 'attr_' . $value->attr_id;
                            $tmp_val = $v->$tmp_field;
                            $tmp_val_val = $lists['attribs_values'][$tmp_val]->name ?? '';
							$style = $value->block ? 'display: none;' : '';
						?>
                            <td class="col_attr_<?php echo $value->attr_id; ?>" style="<?php echo $style; ?>">
                                <input type='hidden' name='attrib_id[<?php echo $value->attr_id; ?>][]' value='<?php echo $tmp_val; ?>'>
                                <?php if (!empty($lists['attribs_values'][$tmp_val]->image)) : $htmlProdAttrImg = JSFactory::getModel('AttrsFront')->generateHtmlImgOfProdAttr($value->attr_id, $lists['attribs_values'][$tmp_val]->image);?>
								<div class="image">
										<img src="<?php echo getPatchProductImage($lists['attribs_values'][$tmp_val]->image, 'thumb', 1); ?>" width="90" border="0" />
								</div>
                                <?php endif; ?>
                                <?php echo $tmp_val_val; ?>
                            </td>

                        <?php  endforeach; ?>
                            <td>
                                <input type='text' class="form-control" name='attrib_price[]' value='<?php echo floatval($v->price); ?>'>
                            </td>

                            <?php echo $this->dep_attr_td_row[$k] ?? ''; ?>

                            <?php if ($jshopConfig->stock) : ?>
                                <td>
                                    <div id='block_enter_attr_qty_<?php echo $attr_tmp_row_num; ?>' <?php echo $v->unlimited ? 'style="display:none;"' : ''; ?> >
                                        <input type='text' class="form-control" name='attr_count[]' value='<?php echo $v->count; ?>'>
                                    </div>
                                    <div>
                                        <input type='hidden'  name='attr_unlimited[<?php echo $k; ?>]' value='0' />
                                        <input type='checkbox' class="form-check-input" <?php echo $v->unlimited ? 'checked' : ''; ?> name='attr_unlimited[<?php echo $k; ?>]' value='1' onclick='shopProductCommon.toggleAttrQuantityAdd(this.checked, <?php echo $attr_tmp_row_num; ?>)'  /><?php echo JText::_('COM_SMARTSHOP_UNLIMITED'); ?>
                                    </div>
                                </td>

                                <td>
                                    <input type='hidden' name='low_stock_attr_notify_status[<?php echo $k; ?>]' value='0'> 
                                    <input type='checkbox' class="form-check-input" name='low_stock_attr_notify_status[<?php echo $k; ?>]' <?php echo !empty($v->low_stock_attr_notify_status) ? 'checked' : ''; ?> value='1'> 
                                    <input type='number' class="form-control" name='low_stock_attr_notify_number[<?php echo $k; ?>]' value='<?php echo $v->low_stock_attr_notify_number; ?>'>
                                </td>
                            <?php endif; ?>

                            <td>
                                <input type='text' class="form-control" name='attr_ean[]' value='<?php echo $v->ean; ?>'>
                            </td>

                            <td>
                                <input type='text' class="form-control" name='attr_weight[]' value='<?php echo  $v->weight; ?>'>
                            </td>

                            <td>
                                <input type='date' class="form-control" name='attr_expiration_date[]' value='<?php echo  $v->expiration_date; ?>'>
                            </td>

                            <td>
                                <input type='text' class="form-control" name='attr_production_time[]' value='<?php echo $v->production_time; ?>'>
                            </td>

                            <?php if (!empty($jshopConfig->admin_show_product_basic_price)) : ?>
                                <td>
                                    <input type='text' class="form-control" name='attr_weight_volume_units[]' value='<?php echo $v->weight_volume_units; ?>'>
                                </td>
                            <?php endif; ?>

                            <td>
                                <input type='text' class="form-control" name='attrib_old_price[]' value='<?php echo $v->old_price; ?>'>
                            </td>

                            <?php if (!empty($jshopConfig->admin_show_product_bay_price)) : ?>
                                <td>
                                    <input type='text' class="form-control" name='attrib_buy_price[]' value='<?php echo floatval($v->buy_price); ?>'>
                                </td>
                            <?php endif; ?>
                            
                            <td>
                                <?php if ($jshopConfig->use_extend_attribute_data) : ?>
                                    <a class='btn btn-mini' target='_blank' href='index.php?option=com_jshopping&controller=products&task=edit&product_attr_id=<?php echo $v->product_attr_id; ?>' onclick='shopProductAttribute.editExtendParams(<?php echo $v->product_attr_id; ?>); return false;'>
                                        <?php echo JText::_('COM_SMARTSHOP_ATTRIBUTE_EXTEND_PARAMS'); ?>
                                    </a>
                                <?php endif; ?>
                            </td>

                            <td>
                                <input type='checkbox' class='ch_attr_delete form-check-input' value='<?php echo $attr_tmp_row_num; ?>'>
                                <input type='hidden' name='product_attr_sorting[]' value='<?php echo $attr_tmp_row_num; ?>'>
                                <input type='hidden' name='product_attr_id[]' value='<?php echo $v->product_attr_id; ?>'>
                                <input type='hidden' name='ext_attribute_product_id[]' value='<?php echo $v->ext_attribute_product_id; ?>'>

                                <input type="hidden" name="attr_product_price_type[]" value="<?php echo $v->product_price_type; ?>">
                                <input type="hidden" name="attr_qtydiscount[]" value="<?php echo $v->qtydiscount; ?>">
                                <input type="hidden" name="attr_factory[]" value="<?php echo $v->factory; ?>">
                                <input type="hidden" name="attr_storage[]" value="<?php echo $v->storage; ?>">
                                <input type="hidden" name="attr_product_tax_id[]" value="<?php echo $v->product_tax_id; ?>">
                                <input type="hidden" name="attr_product_manufacturer_id[]" value="<?php echo $v->product_manufacturer_id; ?>">
                                <input type="hidden" name="attr_delivery_times_id[]" value="<?php echo $v->delivery_times_id; ?>">
                                <input type="hidden" name="attr_labels[]" value="<?php echo $v->label_id; ?>">
                                <input type="hidden" name="attr_no_return[]" value="">
                                <input type="hidden" name="attr_quantity_select[]" value="<?php echo $v->quantity_select; ?>">
                                <input type="hidden" name="attr_max_count_product[]" value="<?php echo $v->max_count_product; ?>">
                                <input type="hidden" name="attr_min_count_product[]" value="<?php echo $v->min_count_product; ?>">
                                <input type="hidden" name="attr_basic_price_unit_id[]" value="<?php echo $v->basic_price_unit_id; ?>">
                                <input type="hidden" name="attr_add_price_unit_id[]" value="<?php echo $v->add_price_unit_id; ?>">
                                <input type="hidden" name="attr__equal_steps[]" value="<?php echo $v->equal_steps; ?>">

                                <input type="hidden" name="attr__consignment_product_is_add_price[]" value="0">
                                <input type="hidden" name="attr__is_activated_price_per_consignment_upload[]" value="0">
                                <input type="hidden" name="attr__nativeProgressUploads[]">
                            </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        
        <tfoot>
            <tr id='attr_row_end'>
                <?php foreach($lists['all_attributes'] as $key => $value) : ?>
                    <td></td>
                <?php endforeach; ?>

                <?php if ($jshopConfig->stock) : ?>
                    <td></td>
                <?php endif; ?>

                <td colspan="3"></td>

                <?php 
                    echo $this->dep_attr_td_row_empty ?? ''; 
                    if ($jshopConfig->admin_show_product_basic_price) :
                ?>
                    <td></td>
                <?php endif; ?>

                <td></td>

                <?php if ($jshopConfig->admin_show_product_bay_price) : ?>
                    <td></td>
                <?php endif; ?>
                
                <td colspan="3"></td>

                <td colspan="3">
                    <input type='button' class='btn btn-success' value='<?php echo JText::_('COM_SMARTSHOP_BATCH_EDIT'); ?>' onclick='shopProductAttribute.batchEdit("#list_attr_value")'>
                    <input type='button' class='btn btn-danger' value='<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>' onclick='shopProductAttribute.deleteList()'>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
