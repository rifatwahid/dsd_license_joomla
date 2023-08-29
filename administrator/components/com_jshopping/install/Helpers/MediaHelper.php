<?php 

use Joomla\CMS\Filesystem\File;

class MediaHelper
{
	const TABLE_NAME = '#__jshopping_updates_info';
	
	public function prepareImgsAndMoveToNewTable()
	{
		$result = true;
		$imgFromOldTable = $this->getImgsFromOldTable();

		if (!empty($imgFromOldTable)) {
			$modelOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');
			$shopConfig = JSFactory::getConfig();
			$allowedFormats = [
                'image' => explode(',', $shopConfig->allowed_images_formats) ?: [],
                'video' => explode(',', $shopConfig->video_allowed) ?: []
			];
			$successedProductsIds = [];

			foreach ($imgFromOldTable as $imgData) {

				if (!empty($imgData->product_id) && !empty($imgData->image_name)) {
					$sourceInfo = getInfoAboutSource($imgData->image_name);
					$mediaPreview = '';
					$mediaSrc = $imgData->image_name;

					// Get thumb if already exists.
					if (!empty($imgData->image_thumb) || ($sourceInfo['sourceType'] == 'name' && $sourceInfo['abstrName'] == 'image')) {
						$imgName = !empty($imgData->image_thumb) ? $imgData->image_thumb : $imgData->image_name;
						$tempPreview = (substr($imgName, 0, 6) == 'thumb_') ?  substr($imgName, 6, 0): $imgName;

						if (!empty($tempPreview)) {
							$fullTempPreview = 'full_' . $tempPreview;
							$mediaPreview = $tempPreview;
							
							if (file_exists("{$shopConfig->image_product_path}/{$fullTempPreview}")) {
								$mediaPreview = $fullTempPreview;
								$mediaSrc = $fullTempPreview;
							}
						}
					} 
					
					// Generate thumb from url or video or img
					if (empty($mediaPreview))  {
						$url = ($sourceInfo['sourceType'] == 'link') ? $imgData->image_name : '';
						$fileName = ($sourceInfo['sourceType'] == 'name') ? $imgData->image_name : '';
						$path = ($sourceInfo['abstrName'] == 'video') ? "{$shopConfig->video_product_path}/$imgData->image_name" : "{$shopConfig->image_product_path}/{$imgData->image_name}";
						
						if (!empty($url) || !empty($fileName)) {
							$generatedPrev = $modelOfProductsMediaFront->generatePreview(null, $url, [
								'name' => $fileName,
								'path' => $path
							], $allowedFormats);

							if (!empty($generatedPrev['name'])) {
								$mediaPreview = $generatedPrev['name'];
							}
						}
					}

					if (!empty($mediaPreview)) {
						$preparedData = $modelOfProductsMediaFront->prepareUploadAnswer([
								'path' => $mediaSrc
							], [
								'name' => $mediaPreview
							], [
								'additionalData' => [
									'title' => $imgData->name
								]
							], 
							[
								'abstrName' => $sourceInfo['abstrName']
						]);
						
						if (!empty($preparedData)) {
							$successData = $modelOfProductsMediaFront->bindAndStoreMedia($imgData->product_id, $preparedData)['0'] ?: [];

							if (!empty($successData['product_id'])) {
								$successedProductsIds[$successData['product_id']] = $successData['product_id'];
							}
						}
					}
				}
			}

			// update preview columns for products and set `is_main` for media.
			if (!empty($successedProductsIds)) {
				$this->updatePreviewAndIsMainColumns($successedProductsIds);
			}
		}

		return $result;
	}

	public function prepareVideosAndMoveToNewTable()
	{
		$result = true;
		$videosFromOldTable = $this->getVideosFromOldTable();
		
		if (!empty($videosFromOldTable)) {
			$jsUri = JSFactory::getJSUri();
			$modelOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');
			$shopConfig = JSFactory::getConfig();
			$allowedFormats = [
                'image' => explode(',', $shopConfig->allowed_images_formats) ?: [],
                'video' => explode(',', $shopConfig->video_allowed) ?: []
			];
			$successedProductsIds = [];

			foreach ($videosFromOldTable as $videoData) {
				if (!empty($videoData->product_id) && $videoData->video_preview != 'video.gif') {
					$mediaSrc = '';
					$mediaPreview = '';

					// If preview for video is already exist - use, else - create own.			
					if (!empty($videoData->video_preview) && !$jsUri::isUrl($videoData->video_preview)) {
						$src = $shopConfig->video_product_path . '/' . $videoData->video_preview;
						$output = $shopConfig->image_product_path . '/' . $videoData->video_preview;

						if (file_exists($src)) {
							$isMoved = File::move($src, $output);

							if ($isMoved) {
								$mediaSrc = $videoData->video_name;
								$mediaPreview = $videoData->video_preview;
							}
						}
					} elseif(!empty($videoData->video_name)) {
						$sourceInfoByVideoName = getInfoAboutSource($videoData->video_name);

						if ($sourceInfoByVideoName['abstrName'] == 'video') {
							$url = $jsUri::isUrl($videoData->video_name) ? $videoData->video_name : '';
							$path = !empty($url) ? $videoData->video_name : $shopConfig->video_product_path . '/' . $videoData->video_name;
							
							$generatedPrev = $modelOfProductsMediaFront->generatePreview(null, $url, [
								'name' => $videoData->video_name,
								'path' => $path
							], $allowedFormats);

							if (!empty($generatedPrev['name'])) {
								$mediaSrc = $videoData->video_name;
								$mediaPreview = $generatedPrev['name'];
							}
						}
					}

					// Save
					if (!empty($mediaSrc)) {
						$preparedData = $modelOfProductsMediaFront->prepareUploadAnswer([
								'path' => $mediaSrc
							], [
								'name' => $mediaPreview ?: ''
							], [], 
							[
								'abstrName' => 'video'
						]);
						
						if (!empty($preparedData)) {
							$successData = $modelOfProductsMediaFront->bindAndStoreMedia($videoData->product_id, $preparedData)['0'] ?: [];

							if (!empty($successData['product_id'])) {
								$successedProductsIds[$successData['product_id']] = $successData['product_id'];
							}
						}
					}
				}
			}

			// update preview columns for products and set `is_main` for media.
			if (!empty($successedProductsIds)) {
				$this->updatePreviewAndIsMainColumns($successedProductsIds);
			}
		}

		return $result;
	}

	protected function updatePreviewAndIsMainColumns($prodsIds = [])
	{
		$result = false;

		if (!empty($prodsIds)) {
			$modelOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');

			foreach ($prodsIds as $productId) {
				$modelOfProductsMediaFront->setMain($productId);
			}
		}

		return $result;
	}

	protected function getImgsFromOldTable(): array
	{
		$tableName = '#__jshopping_products_images';

		$db = \JFactory::getDBO();
		$sql = "SELECT * FROM {$db->qn($tableName)} ORDER BY `product_id`";
		$db->setQuery($sql);
		
		return $db->loadObjectList() ?: [];
	}

	protected function getVideosFromOldTable()
	{
		$tableName = '#__jshopping_products_videos';

		$db = \JFactory::getDBO();
		$sql = "SELECT * FROM {$db->qn($tableName)} ORDER BY `product_id`";
		$db->setQuery($sql);
		
		return $db->loadObjectList() ?: [];
	}
}