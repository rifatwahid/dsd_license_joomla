<?php if (!empty($this->attributes)) : ?>
	<div class="form-light-bg ep-attributes ">					
		<?php $count_attributes_ep = 0; 
		foreach ($this->attributes as $attribut) : 
			$count_attributes_ep++;
			if ($attribut->attr_type == 3) : ?>											
				<div class="display--none">
					<?php echo $attribut->selects; ?>
				</div>												
			<?php else : ?>
				<div class="form-group jshop_prod_attributes <?php if( !$attribut->selects ){ print 'display--none'; } ?>">
					<label class="d-block">
						<span class="ep-attributes-name">
							<?php echo $attribut->attr_name; ?>
							
							<?php if (!empty($attribut->attr_description)) : ?>
							
									<!-- Button trigger modal -->
									 <a href="#" data-toggle="modal" data-bs-toggle="modal" class="ep-attributes-description" data-target="#attributmodal-<?php echo $count_attributes_ep;?>" data-bs-target="#attributmodal-<?php echo $count_attributes_ep;?>"><i class="fa fa-info" aria-hidden="true"></i></a>
									<!-- Modal -->
								
									<div class="modal fade" id="attributmodal-<?php echo $count_attributes_ep;?>" tabindex="-1" role="dialog"  aria-hidden="true">
										  <div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
											  <div class="modal-header">
												<h3 class="modal-title h5" id="exampleModalLabel"><?php echo $attribut->attr_name; ?></h3>
												<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
												  <span aria-hidden="true">&times;</span>
												</button>
											  </div>
											  <div class="modal-body">											  
												 <?php 											  
												  $placeholder_tags_ep = array("[p]", "[/p]", "[h]", "[/h]","[hr]");
												  $html_tags_ep = array("<p>", "</p>", "<h4 class='h5'>", "</h4>", "<hr>");											  
												 ?>
												  
												<?php echo str_replace($placeholder_tags_ep, $html_tags_ep, $attribut->attr_description); ?>
												  
											  </div>
											</div>
										  </div>
									</div>
									
							<?php endif; ?>
						</span>

						<span id='block_attr_sel_<?php echo $attribut->attr_id; ?>' class="ep-block_attr">
							<?php echo $attribut->selects; ?>
						</span>
					</label>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>