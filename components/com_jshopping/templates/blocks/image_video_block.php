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

<!-- Main Image -->
<?php if (!empty($this->images)) {
	foreach ($this->images as $k => $video) {
		if ($video->type == 1) { ?>
			<a class="video_full w-100 display--none" id="hide_video_<?php echo $video->image_id; ?>" href=""></a>
		<?php }
	}
} ?>
<!-- Main Image END -->

<!-- Middle Images -->
<div id='list_product_image_middle' class="mb-5">

	<!-- No image -->
	<?php if (empty($this->images) && empty($this->videos) ) : ?>
		<img id="main_image" src="<?php echo $pathToNoImage; ?>" alt="<?php echo htmlspecialchars($this->product->name); ?>" class="img-fluid w-100 lightbox"/>
	<?php endif; ?>
	<!-- No image END-->

	<?php foreach ($this->images as $k => $image) :
		$classForNonFirstKey = ($k != 0) ? 'display--none' : '';
		$isImage = $image->type == 0;
		$alt = htmlspecialchars($image->_title ?? $this->product->name);
		$idOfFullMainImage = "main_image_full_{$image->image_id}";
		
		if ($isImage) { 
			$url_image = $image->image_url;
			$pathToImage = $url_image;

			if (empty($image->image_url) && !empty($image->image_name)) {
				$pathToImage = $this->image_product_path . '/' . $image->image_full;
				$url_image = $this->image_product_path . '/' . $image->image_name;
			}?>

			<a class="lightbox <?php echo $classForNonFirstKey; ?>"  id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $pathToImage; ?>" rel="sliderElement">
				<img id="main_image_<?php echo $image->image_id; ?>" src="<?php echo $url_image ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100"/>
			</a>
		<?php } else { 		
			$fullPathToVideo = $image->path_video_to_link ?: ($this->video_product_path . '/' . $image->image_name);
			$pathToImage = !empty($image->image_full) ? $this->image_product_path . '/' . $image->image_full : $image->path_video_to_img ?: $pathToNoImage;

			if ($image->image_thumb != 'noimage.gif') {
				$pathToImage = $this->image_product_path . '/' . $image->image_full;			
			?>
				<a class="lightbox <?php echo $classForNonFirstKey; ?>"  id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $fullPathToVideo; ?>" width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
					<img id="main_image_<?php echo $image->image_id; ?>" src="<?php echo $this->image_product_path . '/' . $image->image_thumb; ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100"/>
				</a>
			<?php } else {
				$ext = strtoupper(pathinfo($fullPathToVideo, PATHINFO_EXTENSION));
				
				if (in_array($ext, array('MOV','AVI','MP4'))) {
				?>
					<a class="lightbox <?php echo $classForNonFirstKey; ?>" id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $fullPathToVideo; ?>" width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
						<video  onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" class="img-fluid">
							<source src="<?php echo $fullPathToVideo; ?>#t=1.7">
						</video>
					</a>				
				<?php } elseif (in_array($ext,array('GIF','PNG','JPG','JPEG'))) { ?>
					<a class="lightbox <?php echo $classForNonFirstKey; ?>" id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $fullPathToVideo; ?>" width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
						<img id="main_image_<?php echo $image->image_id; ?>" src="<?php echo $fullPathToVideo; ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100"/>
					</a>
				<?php } else if (is_int(strpos($image->image_name, 'http')))  { ?>
					<a class="lightbox <?php echo $classForNonFirstKey; ?>" id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $image->path_video_to_link; ?>"width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
						<img src="<?php print $image->path_video_to_img; ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100" />
					</a>
				<?php } else { ?>
					<a class="lightbox <?php echo $classForNonFirstKey; ?>" id="<?php echo $idOfFullMainImage; ?>" href="<?php echo $fullPathToVideo; ?>" width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
						<img id="main_image_<?php echo $image->image_id; ?>" src="<?php echo $pathToNoImage; ?>" alt="<?php echo $alt; ?>" class="img-fluid w-100"/>
					</a>
				<?php }
			}

		} ?>
	<?php endforeach;?>

</div>
<!-- Middle Images END -->

<!-- Thumbnails -->
<?php if (!empty($this->images) || !empty($this->videos)  ) : ?>
	<div class="row mt-2" id="list_product_image_thumb">

		<?php if (!empty($this->images)) : ?>
			<?php foreach ($this->images as $k => $image) : ?>
				<?php if (($image->type==0)OR($image->image_thumb!='noimage.gif')) { ?>
					<div class="col-3 col-lg-3 mb-2" style="display:flex; align-items:center">
						<?php 
							$url_image = is_int(strpos($image->image_name, 'http')) ? $image->image_url : $this->image_product_path . '/' . $image->image_thumb;
						?>
						<img class="img-fluid w-100" src="<?php echo $url_image ?>" alt="<?php echo htmlspecialchars($image->_title ?? $this->product->name); ?>" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" />
					</div>
				<?php } else { ?>
					
					<?php
					$fullPathToVideo = $image->path_video_to_link ?: ($this->video_product_path . '/' . $image->image_name);
					$ext =strtoupper(pathinfo($fullPathToVideo, PATHINFO_EXTENSION));
					if (in_array($ext,array('MOV','AVI','MP4'))){
					?>
						<video class="col-3 col-lg-3 mb-2" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" class="img-fluid">
							<source src="<?php echo $fullPathToVideo; ?>#t=1.7">
						</video>				
					<?php } elseif (in_array($ext,array('GIF','PNG','JPG','JPEG'))){ ?>
						<div class="col-3 col-lg-3 mb-2">						
							<img class="img-fluid w-100" src="<?php echo $fullPathToVideo; ?>" alt="<?php echo htmlspecialchars($image->_title ?? $this->product->name); ?>" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" />
						</div>
					<?php } else if (is_int(strpos($image->image_name, 'http'))) { ?>
						<div class="col-3 col-lg-3 mb-2" style="display:flex; align-items:center">
							<img class="img-fluid w-100" src="<?php print $image->path_video_to_img; ?>" alt="<?php echo htmlspecialchars($image->_title ?? $this->product->name); ?>" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>)" />
						</div>
					<?php } else { ?>
						<div class="col-3 col-lg-3 mb-2">						
							<img class="img-fluid w-100" src="<?php echo $pathToNoImage; ?>" alt="<?php echo htmlspecialchars($image->_title ?? $this->product->name); ?>" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" />
						</div>
					<?php }?>			
					
				<?php } ?>				
			<?php endforeach; ?>
		<?php endif; ?>
	</div> <!-- /row mt-2 -->
<?php endif; ?>
<!-- Thumbnails END -->