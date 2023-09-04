<?
	$pathToNoImage = $this->image_product_path . '/' . $this->noimage;
?>

<!-- Label -->
<?php if ($this->product->label_id) : ?>
	<div class="product_label">
		<?php if (!empty($this->product->_label_image)) : ?>
			<img src="<?php echo getPatchProductImage($this->product->_label_image, '', 1); ?>" alt="<?php echo htmlspecialchars($this->product->_label_name ?? $this->product->name); ?>" />
		<?php else : ?>
			<span class="label_name">
				<?php echo $this->product->_label_name; ?>
			</span>
		<?php endif; ?>
	</div>
<?php endif; ?>
<!-- Label END -->

<!-- Middle Images -->
<div id='list_product_image_middle' class="mb-5">

	<?php if (empty($this->images)) : ?>
		<img id="main_image" src="<?php echo $pathToNoImage; ?>" alt="<?php echo htmlspecialchars($this->product->name); ?>" class="img-fluid w-100 lightbox"/>
	<?php else : 
	
	$mediaIteration = 0;
	foreach ($this->images as $k => $media) :
		$classForNonFirstKey = ($mediaIteration != 0) ? 'display--none' : '';
		$isVideo = ($media->media_abstract_type == 'video');
		$videoAttrs = $isVideo ? ('width="' . $this->config->video_product_width . '" height="' . $this->config->video_product_height . '"') : '';
		$alt = htmlspecialchars($media->media_title ?? $this->product->name);
	?>
		<a class="lightbox <?php echo $classForNonFirstKey; ?>" <?php if($this->config->video_autoplay != 1){ ?> onclick="shopProductAttributes.playLightBox()" <?php } ?> id="main_image_full_<?php echo $media->id; ?>" href="<?php echo $media->preparedLinkToMedia; ?>" <?php echo $videoAttrs; ?> autoplay="autoplay" rel="sliderElement">
			<img id="main_image_<?php echo $media->id; ?>" src="<?php echo $media->preparedLinkToPreviewMedia; ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100"/>
		</a>
	<?php $mediaIteration++; endforeach; endif; ?>

</div>
<!-- Middle Images END -->

<!-- Thumbnails -->
<?php if (!empty($this->images)) : ?>
	<div class="row mt-2" id="list_product_image_thumb">
		<?php foreach ($this->images as $k => $media) : ?>
			<div class="col-3 col-lg-3 mb-2">						
				<img class="img-fluid w-100" src="<?php echo $media->preparedLinkToPreviewMedia; ?>" alt="<?php echo htmlspecialchars($media->media_title ?? $this->product->name); ?>" onclick="shopProductCommon.showImage(<?php echo $media->id; ?>);" />
			</div>			
		<?php endforeach; ?>
	</div> <!-- /row mt-2 -->
<?php endif; ?>
<!-- Thumbnails END -->