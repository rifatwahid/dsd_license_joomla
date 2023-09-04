<?php if (!empty($this->freeatribute)) : ?>
    <div class="list_free_attribute">
        <?php foreach($this->freeatribute as $attr) : ?>
            <p class="jshop_cart_attribute list_free_attribute__item">
                <span class="name"><?php echo $attr->attr; ?></span>: 
                <span class="value">
                    <?php 
                        if (is_numeric($attr->value)) {
                            echo printFreeAttrQtyByUnit($attr->value, $attr->attr_id, $this->product_id) . ' ' . printFreeAttrUnit($attr->attr_id, $this->product_id);
                        } else {
                            echo $attr->value;
                        }
                    ?>
                </span>
            </p>
        <?php endforeach; ?> 
    </div>
<?php endif; ?>