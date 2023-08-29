<?php if (!empty($attr_all_values)) : ?>
<div class="form-group row align-items-center"  id="eafa_tr" class="eafa_tr">
        <label for="name_en-GB" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
            <?php echo JText::_('COM_SMARTSHOP_EXCLUDE_ATTRIBUTE'); ?>
        </label>

        <div class="col-sm-9 col-md-10 col-xl-10 col-12">            
            <?php echo JHTML::_('select.genericlist', $attrs, 'eafa_attr_ids[]', 'class="inputbox form-select" size= "10" multiple="multiple"  ', 'attr_id', 'name', $attrs_ids); ?>
                <?php foreach ($attr_all_values as $attr_id => $av):
                    $values_ids = isset($attr_values[$attr_id]) ? $attr_values[$attr_id] : [];
                    $class = isset($attr_values[$attr_id]) ? 'show' : 'hide';
                    ?>
                    <div class="attr_values eafa_tr <?php echo $class; ?>" data-id="<?php echo $attr_id; ?>">
                        <div class="name">
                            <b><?php echo $attrs[$attr_id]->name; ?></b>
                        </div>                        
                        <div class="select">
                            <?php echo JHTML::_('select.genericlist', $av, "eafa_attr_values[$attr_id][]",
                                'class="inputbox form-select" size= "'.(count($av)>10 ? 10 : count($av)).'" multiple="multiple" ', 'value_id', 'name',
                                $values_ids); ?>
                        </div>
                    </div>
                <?php endforeach; ?>            
          </div>
</div>
<?php endif; ?>