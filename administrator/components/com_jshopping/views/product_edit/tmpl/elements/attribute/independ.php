<?php foreach($lists['all_independent_attributes'] as $ind_attr) : ?>
    <div class="table-responsive pt-3">
        <table class="table table-striped" id="list_attr_value_ind_<?php echo $ind_attr->attr_id; ?>">
            <thead>
                <tr>
                    <th scope="col" width="15"></th>
                    <th scope="col" width="150"><?php echo $ind_attr->name; ?></th>
                    <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_PRICE_MODIFICATION'); ?></th>
                    <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_PRICE'); ?></th>
                    <?php echo $this->ind_attr_td_header; ?>
                    <th scope="col" width="120"><?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE'); ?></th>
                    <th scope="col" ><?php echo JText::_('COM_SMARTSHOP_DELETE')?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (isset($lists['ind_attribs_gr'][$ind_attr->attr_id]) && is_array($lists['ind_attribs_gr'][$ind_attr->attr_id])) :
                $indep_attr_tmp_row_num = 0;
                foreach($lists['ind_attribs_gr'][$ind_attr->attr_id] as $ind_attr_val) : 
                    $indep_attr_tmp_row_num++; ?>
                    <tr id='attr_ind_row_<?php echo $ind_attr_val->attr_id . '_' . $ind_attr_val->attr_value_id; ?>'>
                        <td>
                            <span class='icon-menu' aria-hidden='true'></span>
                        </td>

                        <td>
                            <?php if (!empty($lists['attribs_values'][$ind_attr_val->attr_value_id]->image)) : ?>
                                <img src='<?php echo $jshopConfig->image_attributes_live_path . '/' . $lists['attribs_values'][$ind_attr_val->attr_value_id]->image; ?>' align='left' hspace='5' width='16' height='16' style='margin-right:5px;' class='img_attrib'>
                            <?php endif; ?>
                            <input type='hidden' id='attr_ind_<?php echo $ind_attr_val->attr_id . '_' . $ind_attr_val->attr_value_id; ?>' name='attrib_ind_id[]' value='<?php echo $ind_attr_val->attr_id; ?>'>
                            <input type='hidden' name="attrib_ind_value_id[]" value='<?php echo $ind_attr_val->attr_value_id; ?>'>
                            <?php echo $lists['attribs_values'][$ind_attr_val->attr_value_id]->name; ?>
                        </td>

                        <td>
    <!--                        <input type='text' class='small3' name='attrib_ind_price_mod[]' value='--><?//= $ind_attr_val->price_mod; ?><!--'>-->
                            <?php echo $ind_attr_val->price_mod_select; ?>
                        </td>

                        <td>
                            <input type='text' class='small3' name='attrib_ind_price[]' value='<?php echo floatval($ind_attr_val->addprice); ?>'>
                        </td>
                        <?php echo $this->ind_attr_td_row[$ind_attr_val->attr_value_id]; ?>

                        <td>
                            <input type='date' class='small3' name='attrib_ind_expiration_date[]' value='<?php echo $ind_attr_val->expiration_date; ?>'>
                        </td>

                        <td>
                            <a class="btn btn-micro" href='#' onclick="document.querySelector('#attr_ind_row_<?php echo $ind_attr_val->attr_id . '_' . $ind_attr_val->attr_value_id; ?>').remove(); return false;">
                                <i class="icon-delete"></i>
                            </a>
                            <input type="hidden" name="product_independ_attr_sorting[]" value="<?php echo $indep_attr_tmp_row_num; ?>">
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    
    <div style="padding-top:5px;" class="input-inline col width-100">
        <table cellpadding="4" class="admintable">
            <tr>
                <td width="150"><?php echo $ind_attr->values_select; ?></td>
                <td width="120"><?php echo $ind_attr->price_modification_select; ?></td>
                <td width="120"><input type="text" class='small3' id="attr_ind_price_tmp_<?php echo $ind_attr->attr_id; ?>" value="0"></td>
                <?php echo $this->ind_attr_td_footer[$ind_attr->attr_id]; ?>
                <td width="150"><input type="date" class='small3' id="attr_ind_expiration_date_tmp_<?php echo  $ind_attr->attr_id; ?>" value=""></td>
                <td><?php echo $ind_attr->submit_button; ?></td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>