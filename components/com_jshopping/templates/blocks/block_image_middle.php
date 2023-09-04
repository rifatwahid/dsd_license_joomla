<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<?php if (empty($this->images) && empty($this->videos) ) : ?>
    <img id="main_image" src="<?php echo $this->image_product_path . '/' . $this->noimage; ?>" alt="<?php echo htmlspecialchars($this->product->name); ?>" class="img-fluid w-100 lightbox"/>
<?php endif; ?>

<?php foreach ($this->images as $k => $image) : 
	$pathToImage = $this->image_product_path . '/' . $image->image_full;
	$size = !empty(imageSizes($pathToImage)['sizes']) ? imageSizes($pathToImage)['sizes'] : '1024x768';
?>
    <a class="lightbox <?php if ($k != 0) { ?>display--none<?php } ?>" data-med="<?php echo $pathToImage; ?>" data-med-size="<?php echo $size; ?>" data-size="<?php echo $size; ?>" id="main_image_full_<?php echo $image->image_id; ?>" href="<?php echo $this->image_product_path . '/' . $image->image_full; ?>" rel="sliderElement">
        <img id="main_image_<?php echo $image->image_id; ?>" src="<?php echo $this->image_product_path . '/' . $image->image_name; ?>" alt="<?php echo htmlspecialchars($image->_title); ?>" class="img-fluid w-100"/>
    </a>
<?php endforeach; ?>

<?php if (!empty($this->videos)) : ?>
	<?php foreach ($this->videos as $k => $video) : 
            $urlToVideoPreview = $this->video_image_preview_path . '/' . (($video->video_preview) ? $video->video_preview : 'video.gif');
		?>
            <a class="lightbox <?php if ((empty($this->images) && $k != 0) || !empty($this->images)) { echo 'display--none'; } ?>" id="video_full_<?php echo $video->video_id; ?>" href="<?php echo $this->video_product_path . '/' . $video->video_name; ?>" width="<?php echo $this->config->video_product_width; ?>" height="<?php echo $this->config->video_product_height; ?>" data-med="0" data-med-size="0" data-size="0" rel="sliderElement">
                <img id="video_<?php echo $video->video_id; ?>" src="<?php echo $urlToVideoPreview; ?>" class="img-fluid w-100"/>
            </a>
	<?php endforeach; ?>
<?php endif; ?>