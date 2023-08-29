<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

?>

<?php if (!empty($this->images)) : ?>
	<?php foreach ($this->images as $k => $image) : ?>
		<div class="col-3 col-lg-2 mb-2">
			<img class="img-fluid w-100" src="<?php echo $this->image_product_path . '/' . $image->image_thumb; ?>" alt="<?php echo htmlspecialchars($image->_title); ?>" onclick="shopProductCommon.showImage(<?php echo $image->image_id ?>);" />
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($this->videos)) : ?>
	<?php foreach ($this->videos as $k => $video) : 
			$urlToVideoPreview = $this->video_image_preview_path . '/' . (($video->video_preview) ? $video->video_preview : 'video.gif');
		?>
			<div class="col-3 col-lg-2 mb-2">
				<a href="<?php echo $this->video_product_path . '/' . $video->video_name; ?>" id="thumb_video_<?php echo $video->video_id; ?>" onclick="shopProductCommon.showVideo(<?php echo $video->video_id; ?>); return false;">
					<img class="img-fluid w-100" src="<?php echo $urlToVideoPreview; ?>" alt="video"/>
				</a>
			</div>
	<?php endforeach; ?>
<?php endif; ?>