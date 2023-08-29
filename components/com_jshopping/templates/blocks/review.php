<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->allow_review || !empty($this->reviews)) { ?>
	<section class="my-4 product-review">
		<h3 class="product-review__page-title"><?php echo JText::_('COM_SMARTSHOP_REVIEWS'); ?></h3>

		<ul class="list-group list-group-flush">
			<?php foreach($this->reviews as $curr) : 
				$curr->reviewfile = explode('|', $curr->reviewfile);
			?>
				<li class="list-group-item px-0">

					<?php if ($curr->mark) { 
						echo showMarkStar($curr->mark); 
					} ?>

					<span class="d-block my-1">
						<?php echo $curr->user_name; ?>
					</span>

					<p class="my-1">
						<?php echo nl2br($curr->review); ?>
					</p>

					<small class=" text-muted">
						<?php echo formatdate($curr->time);?>
					</small>

					<?php if (!empty($curr->reviewfile)): ?>
						<div class="row">
							<?php foreach ($curr->reviewfile as $reviewfile) : ?>
								<div class="col-sm-4 col-md-3 col-lg-2 col-6 mb-3 card-group">
									<?php if (!empty($reviewfile) && file_exists($this->config->files_product_review_path . '/' . $reviewfile)): 
										$urlToImageReview =  $this->config->files_product_review_live_path . '/' . $reviewfile;
										?>						
										<a href="<?php echo $urlToImageReview; ?>" target="_blank">
											<img src="<?php echo $urlToImageReview; ?>">
										</a>
									<?php endif;?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ($this->display_pagination) : ?>
		  <div class="my-4"><?php echo $this->pagination; ?></div>
		<?php endif; ?>

		<?php if ($this->allow_review > 0) : ?>

			<?php JHTML::_('behavior.formvalidation'); ?>
			<h5 class="mb-4 mt-2"><?php echo JText::_('COM_SMARTSHOP_WRITE_REVIEW'); ?></h5>

			<div id="product-review-alerts">
			</div>

			<form action="<?php print SEFLink('index.php?option=com_jshopping&controller=product&task=reviewsave');?>" id="productReviewForm" name="add_review" method="post" onsubmit="return shopProductForm.validate(this.name);" enctype="multipart/form-data" >

				<input type="hidden" name="product_id" value="<?php echo $this->product->product_id; ?>" />
				<input type="hidden" name="back_link" value="<?php echo jsFilterUrl($_SERVER['REQUEST_URI']); ?>" />
				<?php echo JHtml::_('form.token');?>

				<?php include __DIR__ . '/review_rating.php'; ?>
				
				<div class="form-group row">
					<label for="review_user_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_NAME'); ?>
					</label>

					<div class="col-sm-7 col-md-8 col-lg-9">
						<input type="text" name="user_name" id="review_user_name" class="input" placeholder="<?php echo JText::_('COM_SMARTSHOP_NAME'); ?>" value="<?php echo $this->user->username; ?>"/>
					</div>
				</div>

				<div class="form-group row">
					<label for="review_user_email" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>
					</label>

					<div class="col-sm-7 col-md-8 col-lg-9">
						<input type="text" name="user_email" id="review_user_email" class="input" placeholder="<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>" value="<?php echo $this->user->email; ?>"/>
					</div>
				</div>

				<div class="form-group row">
					<label for="review_review" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_COMMENT'); ?>
					</label>

					<div class="col-sm-7 col-md-8 col-lg-9">
						<textarea name="review" id="review_review" placeholder="<?php echo JText::_('COM_SMARTSHOP_COMMENT'); ?>" class="form-control w-100"></textarea>
					</div>
				</div>
				
				<div class="row" id="uploadfileimage">				
				</div>
				
				<?php include templateOverrideBlock('blocks', 'review_upload.php'); ?>
				
			
			
				<div class="form-group row">
					<div class="col-sm-7 col-md-8 col-lg-9 offset-sm-5 offset-md-4 offset-lg-3">
						<button type="submit"  onclick="" class="btn btn-outline-primary d-grid col-md-6 float-end"><?php echo JText::_('COM_SMARTSHOP_SUBMIT_REVIEW'); ?></button>
					</div>
				</div>
			</form>
		<?php elseif ($this->allow_review == -2) : ?>
			<p class="mt-2"><?php echo JText::_('COM_SMARTSHOP_BUY_FIRST_FOR_WRITE_REVIEW'); ?></p>
		<?php else : ?>
			<p class="mt-2"><?php echo JText::_('COM_SMARTSHOP_LOGIN_TO_WRITE_REVIEW'); ?></p>
		<?php endif; ?>
	</section>
<?php } ?>