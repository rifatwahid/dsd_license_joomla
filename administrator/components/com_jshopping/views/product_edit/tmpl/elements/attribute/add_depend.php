<div class="col width-75">
    <legend>
        <?php echo JText::_('COM_SMARTSHOP_ADD_ATTRIBUT'); ?>
    </legend>

    <div class="jshops_edit add_dependant_attributes">
        <?php foreach($lists['all_attributes'] as $key => $value) : ?>                
            <div class="form-group row align-items-center">
                <label for="value_id<?php echo $value->attr_id; ?>" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
                    <?php echo $value->name; ?>
                </label>

                <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
                    <?php if (isset($value->hidden) && $value->hidden) : ?>
                        <input type="hidden" id="only_one_hidden_<?php echo $value->attr_id; ?>" value="1" />
                    <?php endif; echo $value->values_select; ?>
                </div>
            </div>    
        <?php endforeach; ?>
        
        <?php 
            require_once __DIR__ . '/add_depend_price.php';
            echo '<div style="margin-bottom: 25px;"></div>';
            require_once __DIR__ . '/add_depend_details.php';
            require_once __DIR__ . '/add_depend_image.php';
            $this->dep_attr_td_footer ?? '';
        ?>
    
        <!-- Btn `add` -->
        <div class="form-group row align-items-center">
            <label  class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label"></label>
            <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
                <div style="width:100px;text-align:right;">
                    <?php echo $lists['dep_attr_button_add']; ?>
                </div>
            </div>
        </div>  
    </div>  
</div>