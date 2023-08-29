<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$urlToPlayImg = $this->config->live_path . 'images/play.gif';

if (!empty($this->demofiles)) : ?>
	<div class="list_product_demo ep-downloads mt-4"  id="ep-mail-sample-order-con">
		<h2 class="h4">Download-Dateien</h2>
		<div>
			<?php foreach($this->demofiles as $demo) : ?>
				<div>
					<a target="_blank" href="<?php echo getPatchProductImage($demo->demo, '', 5); ?>" class="btn btn-outline-primary d-grid mb-3"><?php echo $demo->demo_descr ?: JText::_('COM_SMARTSHOP_DOWNLOAD'); ?></a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
