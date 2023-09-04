<?php

defined('_JEXEC') or die('Restricted access');

?>


<?php if (!empty($this->product->freeattributes)) : ?>
<div class="form-group form-light-bg">
	<?php  
	$productId = ($product->isUseAdditionalFreeAttrs() && !empty($product->getAdditionalProductId())) ? $product->getAdditionalProductId() : $product->product_id; 
	$modelFreeAttrsDefaultValues = JSFactory::getModel('FreeAttrsDefaultValues');
	$modelFreeAttrsDefaultValues = $modelFreeAttrsDefaultValues->getDataByProductId($productId);
	$count_freeattributes_ep = 0;
	?>
    <?php foreach ($this->product->freeattributes as $k=>$freeattribut) :   $count_freeattributes_ep++;?>		
		<div class="form-group">
            <label class="d-block form-label-ep">
				<div class="container-attr-ep">
					<span class="ep-attributes-name pr-2">
						<?php print $freeattribut->name;  ?>
						<?php if (!empty($freeattribut->description)) : ?>
							
									<!-- Button trigger modal -->
									 <a href="#" data-toggle="modal" data-bs-toggle="modal" class="ep-freeattributes-description float-right pr-1" data-bs-target="#freeattributmodal-<?php echo $count_freeattributes_ep;?>"data-target="#freeattributmodal-<?php echo $count_freeattributes_ep;?>"><i class="fa fa-info" aria-hidden="true"></i></a>
									<!-- Modal -->
								
									<div class="modal fade" id="freeattributmodal-<?php echo $count_freeattributes_ep;?>" tabindex="-1" role="dialog"  aria-hidden="true">
										  <div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
											  <div class="modal-header">
												<h3 class="modal-title h5" id="exampleModalLabel"><?php echo $freeattribut->name; ?></h3>
												<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
												  <span aria-hidden="true">&times;</span>
												</button>
											  </div>
											  <div class="modal-body">											  
												 <?php 											  
												  $placeholder_tags_ep = array("[p]", "[/p]", "[h]", "[/h]","[hr]");
												  $html_tags_ep = array("<p>", "</p>", "<h4 class='h5'>", "</h4>", "<hr>");											  
												 ?>
												  
												<?php echo str_replace($placeholder_tags_ep, $html_tags_ep, $freeattribut->description); ?>
												  
											  </div>
											</div>
										  </div>
									</div>
									
							<?php endif; ?>
					</span>
					<span class="ep-block_attr">
									
						<div class="container-wh-ep">
							
							<div class="input-group">
								<?php 
								$disabled = $freeattribut->is_fixed ? 'disabled="disabled"' : '';
								$value = $freeattribut->defaultValue ?? $freeattribut->defaultValue ?? '';?>
								<input type="text" class="inputbox" size="40" id="freeattribut_<?php print $freeattribut->id; ?>" name="freeattribut[<?php print $freeattribut->id; ?>]" value="<?php print $value; ?>" <?php print $disabled; ?>/>
							  	<?php if($freeattribut->show_unit && $freeattribut->units_measure): ?>
								<div class="input-group-append">
										 <span class="input-group-text"><?php print $freeattribut->units_measure; ?></span>
								 </div>
								 <?php endif; ?>
							</div>
							
							<div class="text-muted small mt-1 min-max-values-ep">
								<?php 
									if(!empty($modelFreeAttrsDefaultValues[$freeattribut->id]["min_value"])){ echo 'min. '. $modelFreeAttrsDefaultValues[$freeattribut->id]["min_value"].' '.$freeattribut->units_measure;}; 
									if(!empty($modelFreeAttrsDefaultValues[$freeattribut->id]["min_value"])&&!empty($modelFreeAttrsDefaultValues[$freeattribut->id]["max_value"])){ echo ', ';}; 
									if(!empty($modelFreeAttrsDefaultValues[$freeattribut->id]["max_value"])){ echo 'max. '. $modelFreeAttrsDefaultValues[$freeattribut->id]["max_value"].' '.$freeattribut->units_measure;}
								
								?>
							</div>
						</div>
					</span>
				</div>
			</label>
		</div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
