<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$urlToPlayImg = $this->config->live_path . 'images/play.gif';
 ?>
	<div class="col-md ep-start-col" id="ep-mail-sample-order-con">
	
<?php if (!empty($this->demofiles)) : ?>
		<div class="list_product_demo">
			<?php foreach($this->demofiles as $demo) :
				$description = $demo->demo_descr ?: JText::_('COM_SMARTSHOP_DOWNLOAD');
			?>
				<div class="download">
					<a target="_blank" href="<?php echo getPatchProductImage($demo->demo, '', 5); ?>">
						<?php echo $description; ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
<?php endif; ?>
	</div>
