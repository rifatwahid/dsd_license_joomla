<?php if (!empty($this->extra_fields)) : ?>
    <div class="list_extra_field">
        <?php foreach($this->extra_fields as $f) : ?>
            <p class="jshop_cart_extra_field">
                <span class="name"><?php echo $f['name'];?></span>: <span class="value"><?php echo $f['value'];?></span>
            </p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>