<?php if (is_array($this->product->extra_field)) : ?>
    <div class="extra_fields">
        <?php foreach($this->product->extra_field as $extra_field) : ?>
            <?php if ($extra_field['grshow']) : ?>
                <div class='block_efg'>
                    <div class='extra_fields_group'>
                        <?php echo $extra_field['groupname']; ?>
                    </div>
            <?php endif; ?>
            
                    <div class="extra_fields_el">
                        <?php echo separateExtraFieldsWithUseHideImageCharactParams([$extra_field], 'product'); ?>

                        <?php if (!empty($extra_field['description'])) : ?> 
                            <span class="extra_fields_description">
                                <?php echo $extra_field['description']; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                            
            <?php if (isset($extra_field['grshowclose']) && $extra_field['grshowclose']) : ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>