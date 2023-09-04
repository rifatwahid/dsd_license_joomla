<?php 
if (!empty($this->atribute)) : ?>
    <div class="list_attribute">
        <?php foreach($this->atribute as $attr) : 
            if ($attr->attr_type != 3 && !is_array($attr) ){ ?>
                <p class="jshop_cart_attribute list_attribute__item">
                    <span class="name"><?php echo $attr->attr; ?></span>: <span class="value"><?php echo $attr->value; ?></span>
                </p>
			<?php }elseif(is_array($attr)){
				$values = '';
				foreach($attr as $v){
					if(strlen($v->value) > 0){  
						if(strlen($values) > 0) $values .= '; ';
						$values .= $v->value;  }
				}?>
									
							<p class="jshop_cart_attribute list_attribute__item">
								<span class="name"><?php echo $v->attr; ?></span>: <span class="value"><?php echo $values; ?></span>
							</p>
			<?php } endforeach;?>
    </div>
<?php endif; ?>