<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;

defined('_JEXEC') or die('Restricted access');

class JshoppingModelProductsMediaFront extends jshopBase
{
    const TABLE_NAME = '#__jshopping_products_media';
    const SUPPORT_ABSTRACT_MEDIA = [
        'video', 
        'image'
    ];

    public function bindAndStoreMedia(int $productId, array $medias): array
    {
        $successBindedMedia = [];

        if (!empty($medias)) {
            $app = JFactory::getApplication();
            if (!empty($medias['media_src']) || !empty($medias['media_preview'])) {
                $medias = [$medias];
            }

            foreach($medias as $media) {
                if (!empty($media)) {
                    
                    try {
                        $tableOfProductMedia = JSFactory::getTable('ProductsMedia');
                        $tableOfProductMedia->bind($media);
                        $tableOfProductMedia->product_id = $productId;
                        $isSuccess = $tableOfProductMedia->store();

                        if (!$isSuccess) {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        $name = $tableOfProductMedia->title ?: $tableOfProductMedia->media_src;
                        $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_FAILED_TO_SAVE_MEDIA_NAME'), $name), 'error');
                    }

                    $media['id'] = $tableOfProductMedia->id;
                    $media['product_id'] = $tableOfProductMedia->product_id;
                    $successBindedMedia[] = $media;

                    unset($tableOfProductMedia);
                }
            }
        }

        return $successBindedMedia;
    }

    public function handleUploadMedia(array $media): array
    {
        $result = [];
        $media = array_filter($media, function ($item) {
            return (!empty($item['link']) || !empty($item['file']));
        });

        if (!empty($media)) {
            $rootPath = JPATH_ROOT . '/';
            $app = JFactory::getApplication();
            $shopConfig = JSFactory::getConfig();
            $allowedFormats = [
                'image' => explode(',', $shopConfig->allowed_images_formats) ?: [],
                'video' => explode(',', $shopConfig->video_allowed) ?: []
            ];
            $allowFormats = array_merge($allowedFormats['image'], $allowedFormats['video']);

            foreach ($media as $item) {
                $tempThumb = [];
                $item['preview'] = clearPathOfImage($item['preview']);
                $item['file'] = clearPathOfImage($item['file']);
                $fullPathToPreview = $rootPath . $item['preview'];
                $fullPathToFile = $rootPath . $item['file'];
                $isSetFile = (!empty($item['file']) && file_exists($fullPathToFile));
                $isSetPreview = (!empty($item['preview']) && file_exists($fullPathToPreview));
                $isSetLink = (!empty($item['link']));
                
                if ($isSetFile || $isSetLink) {
                    $uploadResult = null;
                    $thumbResult = null;
                    $source = ($isSetFile) ? $fullPathToFile: $item['link'];
                    $sourceInfo = getInfoAboutSource($source);
                    $uploadInfo = [
                        'additionalData' => [
                            'title' => $item['title']
                        ]
                    ];

                    if (in_array($sourceInfo['abstrName'], self::SUPPORT_ABSTRACT_MEDIA)) {
                        if ($isSetFile) {
                            $uploadInfo['tmp_name'] = $sourceInfo['name'];
                            $name = $sourceInfo['name'] . '.' . $sourceInfo['format'];
                            $tempThumb = $thumbResult = $uploadResult = [
                                'name' => $name,
                                'path' => $fullPathToFile,
                                'short_path' => $item['file']
                            ];
                        } elseif($isSetLink) {
                            $tempThumb = $thumbResult = $uploadResult = $this->uploadUrl($source, $allowFormats);
                        }

                        if ($isSetPreview) {
                            $sourcePreviewInfo = getInfoAboutSource($fullPathToPreview);
                            $tempThumb = [
                                'name' => $sourcePreviewInfo['name'] . '.' . $sourcePreviewInfo['format'],
                                'path' => $fullPathToPreview,
                                'short_path' => $item['preview']
                            ];
                        }
                    }

                    if (empty($uploadResult['path']) && !$isSetLink) {
                        continue;
                    }

                    if ($isSetPreview || $isSetLink || !empty($uploadResult)) {   
                        $thumbResult = $this->generatePreview([], $item['link'], $tempThumb, $allowedFormats);
                    }

                    $tempResult = $this->prepareUploadAnswer($uploadResult, $thumbResult, $uploadInfo, $sourceInfo);

                    if (!empty($tempResult)) {
                        $result[] = $tempResult;
                    }
                } else {
                    $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_THE_FILE_NAME_HAS_NOT_UPLOADED_WRONG_FORMAT'), $item['file'] ?: $item['link']), 'error');
                    continue;
                }
            }
        }

        return $result;
    }

    /**
     * Don`t use anymore!!
     * @deprecated
     */
    public function upload(array $dataToUpload): array
    {
        $result = [];

        if (!empty($dataToUpload)) {
            $app = JFactory::getApplication();
            $shopConfig = JSFactory::getConfig();
            $allowedFormats = [
                'image' => explode(',', $shopConfig->allowed_images_formats) ?: [],
                'video' => explode(',', $shopConfig->video_allowed) ?: []
            ];
            $allowFormats = array_merge($allowedFormats['image'], $allowedFormats['video']);

            foreach ($dataToUpload as $uploadInfo) {
                $additionalData = $uploadInfo['additionalData'] ?: [];
                $isUserSetUrl = !empty($additionalData['url']);
                $isUserUploadFile = !empty($uploadInfo['tmp_name']);
                $isUserUploadPreview = !empty($additionalData['preview']['tmp_name']);
                
                if ($isUserSetUrl || $isUserUploadFile) {
                    $fileName = $isUserUploadFile ? $uploadInfo['name'] : $additionalData['url'];
                    $uploadedSourceFileInfo = getInfoAboutSource($fileName);

                    if (in_array($uploadedSourceFileInfo['abstrName'], self::SUPPORT_ABSTRACT_MEDIA)) {
                        $uploadResult = null;
                        $thumbResult = null;

                        // Upload user files
                        if ($isUserUploadFile) {
                            $outputPath = ($uploadedSourceFileInfo['abstrName'] == 'video' ? $shopConfig->video_product_path : $shopConfig->image_product_path) . '/';
                            $uploadResult = $this->uploadFile($uploadInfo, $allowFormats, $outputPath);
                        } elseif ($isUserSetUrl) {
                            $uploadResult = $this->uploadUrl($additionalData['url'], $allowFormats);
                        }
                        
                        if (empty($uploadResult['path'])) {
                            continue;
                        }
                        
                        // Upload and generate preview
                        if ($isUserUploadPreview || $isUserSetUrl || !empty($uploadResult)) {                    
                            $thumbResult = $this->generatePreview($additionalData['preview'], $additionalData['url'], $uploadResult, $allowedFormats);
                        }
                        
                        // Answer
                        $tempResult = $this->prepareUploadAnswer($uploadResult, $thumbResult, $uploadInfo, $uploadedSourceFileInfo);

                        if (!empty($tempResult)) {
                            $result[] = $tempResult;
                        }
                    } else {
                        $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_THE_FILE_NAME_HAS_NOT_UPLOADED_WRONG_FORMAT'), $uploadInfo['name'] ?: $additionalData['url']), 'error');
                        continue;
                    }

                }   
            }
        }

        return $result;
    }

    public function getById(int $id): ?object
    {
        $db = \JFactory::getDBO();

        $query = "SELECT PM.*, JAT1.`type_name` as `media_src_abstract_type_text`, JAT2.`type_name` as `media_preview_abstract_type_text`, JAT3.`type_name` as `media_abstract_type`
        FROM {$db->qn(self::TABLE_NAME)} AS PM
        LEFT JOIN `#__jshopping_abstracts_types` AS JAT1 ON PM.`media_src_abstract_type` = JAT1.`id`
        LEFT JOIN `#__jshopping_abstracts_types` AS JAT2 ON PM.`media_preview_abstract_type` = JAT2.`id`
        LEFT JOIN `#__jshopping_abstracts_types` AS JAT3 ON PM.`media_abstract_type` = JAT3.`id`
        WHERE PM.`id` = {$db->escape($id)}";

        $db->setQuery($query);
        $result = $db->loadObject() ?: [];
        $result = $this->prepareDBResultToComfy($result) ?: null;

        return $result;
    }

    public function getByProductId(?int $productId, bool $getMain = false): array
    {
        $result = [];

        if ($productId) {
            $db = \JFactory::getDBO();
            $mainMedia = $getMain ? ' AND `is_main` = 1 ' : '';

            $query = "SELECT PM.*, JAT1.`type_name` as `media_src_abstract_type_text`, JAT2.`type_name` as `media_preview_abstract_type_text`, JAT3.`type_name` as `media_abstract_type`
            FROM {$db->qn(self::TABLE_NAME)} AS PM
            LEFT JOIN `#__jshopping_abstracts_types` AS JAT1 ON PM.`media_src_abstract_type` = JAT1.`id`
            LEFT JOIN `#__jshopping_abstracts_types` AS JAT2 ON PM.`media_preview_abstract_type` = JAT2.`id`
            LEFT JOIN `#__jshopping_abstracts_types` AS JAT3 ON PM.`media_abstract_type` = JAT3.`id`
            WHERE PM.`product_id` = {$db->escape($productId)} {$mainMedia}  ORDER BY `is_main` DESC, `ordering` ASC";

            $db->setQuery($query);
            $result = $db->loadObjectList('id') ?: [];
            $result = $this->prepareDBResultToComfy($result);
        }

        return $result;
    }

    public function setMain(?int $productId, ?int $mediaId = 0, bool $changeAndProductImg = true): bool
    {
        $db = \JFactory::getDBO();
        $isSuccessfullSetMain = false;

        if (!empty($mediaId)) {
            $query = "SELECT `product_id` FROM {$db->qn(self::TABLE_NAME)} WHERE `id` = {$mediaId}";
            $db->setQuery($query);
            $tempProductId = $db->loadResult();

            // reset column - `is_main` for product
            if (!empty($tempProductId)) {
                $query = "UPDATE {$db->qn(self::TABLE_NAME)} SET `is_main` = 0 WHERE `product_id` = {$tempProductId}";
                $db->setQuery($query);
                $isUpdate = $db->execute();

                if ($isUpdate && $changeAndProductImg) {
                    $productId = $tempProductId;
                }
            }

            // set new `is_main`
            $query = "UPDATE {$db->qn(self::TABLE_NAME)} SET `is_main` = 1, `ordering` = 0 WHERE `id` = {$db->escape($mediaId)}";
            $db->setQuery($query);
            $isSuccessfullSetMain = $db->execute();
        } elseif(!empty($productId)) {
            // Checks if at least one product record has a `is_main`. if not, it marks the very first record.
            $query = "UPDATE {$db->qn(self::TABLE_NAME)} AS t1, 
            (SELECT * FROM {$db->qn(self::TABLE_NAME)} WHERE `product_id` = {$db->escape($productId)} ORDER BY `is_main` DESC LIMIT 1) AS t2 
            SET t1.`is_main` = 1, t1.`ordering` = 0 WHERE t1.`id` = t2.id";
            $db->setQuery($query);
            $isSuccessfullSetMain = $db->execute();
        }  

        // Update product image
        if (!empty($productId) && $isSuccessfullSetMain && $changeAndProductImg) {
            $mainMedia = reset($this->getByProductId($productId, true));
            $preview = $mainMedia->media_preview ?: '';

            $query = "UPDATE `#__jshopping_products` SET `image` = {$db->quote($preview)} WHERE `product_id` = {$db->escape($productId)}";
            $db->setQuery($query);
            $db->execute();
        }
		if (!is_bool($isSuccessfullSetMain)) return false;
        return $isSuccessfullSetMain;
    }

    public function setTitles(?array $newTitles = [])
    {
        if (!empty($newTitles)) {
            $db = \JFactory::getDBO();

            foreach($newTitles as $mediaId => $newTitle) {
                if (!empty($mediaId)) {
                    $sql = "UPDATE {$db->qn(self::TABLE_NAME)} SET `media_title` = {$db->q($newTitle)} WHERE `id` = {$db->escape($mediaId)}";
                    $db->setQuery($sql);
                    $db->execute();
                }
            }
        }
    }

    public function setOrdering(?array $ordering = [])
    {
        if (!empty($ordering)) {
            $db = \JFactory::getDBO();

            foreach($ordering as $mediaId => $orderNumb) {
                if (!empty($mediaId)) {
                    $sql = "UPDATE {$db->qn(self::TABLE_NAME)} SET `ordering` = {$db->q($orderNumb)} WHERE `id` = {$db->escape($mediaId)} AND `is_main` != 1";
                    $db->setQuery($sql);
                    $db->execute();
                }
            }
        }
    }

    public function generatePreview(?array $previewData, ?string $orUrl, ?array $orAlreadyUploaded, array $allowedFormats)
    {
        $result = [];
        $shopConfig = JSFactory::getConfig();
		if(!$orUrl) $orUrl = '';
        $isUserUploadPreview = (!empty($previewData) && !empty($previewData['tmp_name']));
        $allowFormats = array_merge($allowedFormats['image'], $allowedFormats['video']);

        // Create preview from user preview($_FILE)
        if ($isUserUploadPreview) {
            $previewSourceDataInfo = getInfoAboutSource($previewData['name']);
            $isAllowPreviewFileFormat = in_array($previewSourceDataInfo['format'], $allowedFormats['image']);

            if ($isAllowPreviewFileFormat) {
                $outputPath = $shopConfig->image_product_path . '/';
                return $this->uploadFile($previewData, $allowedFormats['image'], $outputPath) ?: [];
            }
        }

        //Or create for already uploaded video/image file.
        if (!empty($orAlreadyUploaded)) {
            $previewSourceDataInfo = getInfoAboutSource($orAlreadyUploaded['name']) ?: [];
            if (in_array($previewSourceDataInfo['abstrName'], self::SUPPORT_ABSTRACT_MEDIA)) {
                if ($previewSourceDataInfo['abstrName'] == 'image') {
                    $result = $orAlreadyUploaded;
                } else {
                    $result = $this->generateThumb($orAlreadyUploaded['path'], [
                        'imageThumbPath' => $shopConfig->image_product_path,
                        'videoThumbPath' => $shopConfig->image_product_path
                    ], $allowFormats) ?: [];
                }
            }
            $orUrl = '';
        }

        // Or create preview from URL
        if (!empty($orUrl)) {
            $result = $this->generateThumb($orUrl, [
                'videoThumbPath' => $shopConfig->image_product_path,
                'imageThumbPath' => $shopConfig->image_product_path
            ], $allowFormats) ?: [];
            $orAlreadyUploaded = '';
        }

        if (!empty($result) && !empty(strstr($result['path'], 'components'))) {
            $result['short_path'] = strstr($result['path'], 'components');
        }

        return $result;
    }

    protected function uploadFile($uploadInfo, $allowFormats, $outputPath): ?array
    {
        $uploadResult = [];

        if (!empty($uploadInfo)) {
            $app = JFactory::getApplication();
            $uploader = JSFactory::getUploader();

            try {
                $uploadResult = $uploader->store($uploadInfo, $allowFormats, $outputPath);
            } catch(\Exception $e) {
                $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_THE_FILE_NAME_HAS_NOT_UPLOADED_WRONG_FORMAT'), $uploadInfo['name']), 'error');
                return null;
            }
        }

        return $uploadResult;
    }

    protected function uploadUrl(string $url, $allowedFormats = []): ?array
    {
        $uploadResult = [];

        if (!empty($url)) {
            $sourceDataInfo = getInfoAboutSource($url);
            $isSupportFormatOfLink = (!empty($sourceDataInfo['hostingName']) || (!empty($allowedFormats) && in_array($sourceDataInfo['format'], $allowedFormats)));
                    
            if (!$isSupportFormatOfLink) {
                $app = JFactory::getApplication();
                $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_FORMAT_OF_LINK_DONT_SUPPORT_WITH_NAME'), $url), 'error');
                return null;
            }

            if (!empty($sourceDataInfo['hostingName'])) {
                $instanceOfVideoHostingProvider = JSFactory::getVideoHostings()->getProviderInstanceByUrl($url);
                $url = $instanceOfVideoHostingProvider->getEmbedUrl($url) ?: $url;
            }

            $uploadResult = [
                'name' => $sourceDataInfo['name'] ?: time(),
                'path' => $url
            ];
        }

        return $uploadResult;
    }

    protected function generateThumb(string $urlOrPath, $params, array $allowedFormats): ?array
    {
        $previewData = [];

        if (!empty($urlOrPath)) {
            $sourceDataInfo = getInfoAboutSource($urlOrPath);
            $format = $sourceDataInfo['format']; // mp4, jpg, videohosting and etc

            if ($format == 'videohosting' || (!empty($allowedFormats) && in_array($format, $allowedFormats))) {
                $ffmpegCli = JSFactory::getFfmpegCli();

                $previewData = $ffmpegCli->generateImgFromVideoOrImg($urlOrPath, [
                    'pathToVideoFolder' => $params['videoThumbPath'],
                    'pathToImageFolder' => $params['imageThumbPath'],
                    'prefix' => $params['prefix'] ?: ''
                ]) ?: [];
            } else {
                $app = JFactory::getApplication();
                $app->enqueueMessage(sprintf(Text::_('COM_SMARTSHOP_THE_FILE_NAME_HAS_NOT_UPLOADED_WRONG_FORMAT'), $sourceDataInfo['name']), 'error');
                return null;
            }
        }

        return $previewData;
    }

    /**
     * Don`t use anymore!
     * 
     * @param array $uploadedDataOfFiles - $_FILES
     * @deprecated
     */
    public function prepareUploadToComfyFormat(array $uploadedDataOfFiles, array $additionalMedia): array
    {
        $uploadedFilesNames = $uploadedDataOfFiles['name'] ?: [];
        $result = [];

        if (!empty($uploadedFilesNames)) {
            foreach ($uploadedFilesNames as $key => ['file' => $fileName]) {
                $temp = [];
                $isUserUploadFile = !empty($uploadedDataOfFiles['name'][$key]['file']) && !empty($uploadedDataOfFiles['tmp_name'][$key]['file']);
                $isSetPreview = !empty($uploadedDataOfFiles['name'][$key]['preview']) && !empty($uploadedDataOfFiles['tmp_name'][$key]['preview']);
                $isSetUrl = !empty(trim($additionalMedia[$key]['link']));

                // Set info about main file.
                if ($isUserUploadFile) {
                    $temp = [
                        'name' => $uploadedDataOfFiles['name'][$key]['file'] ?: '',
                        'tmp_name' => $uploadedDataOfFiles['tmp_name'][$key]['file'] ?: '',
                        'type' => $uploadedDataOfFiles['type'][$key]['file'] ?: '',
                        'size' => $uploadedDataOfFiles['size'][$key]['file'] ?: 0,
                        'error' => $uploadedDataOfFiles['error'][$key]['file'] ?: 0,
                    ];
                }

                // Set image title
                if (!empty(trim($additionalMedia[$key]['title']))) {
                    $temp['additionalData']['title'] = trim($additionalMedia[$key]['title']);
                }

                // Set user url.
                if ($isSetUrl) {
                    $temp['additionalData']['url'] = trim($additionalMedia[$key]['link']);
                }

                // Set info about preview
                if ($isSetPreview) {
                    $temp['additionalData']['preview'] = [
                        'name' => $uploadedDataOfFiles['name'][$key]['preview'] ?: '',
                        'tmp_name' => $uploadedDataOfFiles['tmp_name'][$key]['preview'] ?: '',
                        'type' => $uploadedDataOfFiles['type'][$key]['preview'] ?: '',
                        'size' => $uploadedDataOfFiles['size'][$key]['preview'] ?: 0,
                        'error' => $uploadedDataOfFiles['error'][$key]['preview'] ?: 0,
                    ];
                }

                if (!empty($temp)) {
                    $result[] = $temp;
                }
            }
        }

        return $result;
    }

    public function prepareUploadAnswer($uploadResult, $thumbResult, $uploadInfo, $sourceDataInfo)
    {
        $isUserUploadFile = !empty($uploadInfo['tmp_name']);
                
        $JSUri = JSFactory::getJSUri();
        $allAbstractsTypes = JSFactory::getModel('AbstractsTypes')->getAll('type_name');
        $mediaSrc = ($isUserUploadFile) ? $uploadResult['name'] : $uploadResult['path'];
        $mediaSrc = $uploadResult['short_path'] ?: $mediaSrc;
        $mediaSrcAbstractType = $JSUri::isUrl($uploadResult['path']) ? $allAbstractsTypes['link']->id : $allAbstractsTypes['name']->id;

        $tempResult = [
            'media_title' => $uploadInfo['additionalData']['title'] ?: '',
            'media_src' => $mediaSrc,
            'media_src_abstract_type' => $mediaSrcAbstractType,
            'media_abstract_type' => $allAbstractsTypes[$sourceDataInfo['abstrName']]->id
        ];

        if (!empty($thumbResult['name'])) {
            $mediaPreviewAbstractType = $JSUri::isUrl($thumbResult['name']) ? $allAbstractsTypes['link']->id : $allAbstractsTypes['name']->id;
            $preview = $thumbResult['short_path'] ?: $thumbResult['name'];

            $tempResult['media_preview'] = $preview;
            $tempResult['media_preview_abstract_type'] = $mediaPreviewAbstractType;
        }

        return $tempResult;
    }

    protected function prepareDBResultToComfy($originalMedia, bool $getPathes = false)
    {
        if (!empty($originalMedia)) {
            $config = JSFactory::getConfig();
            $multimedia = is_array($originalMedia) ? $originalMedia : [$originalMedia];

            $pathToImage = $getPathes ? $config->image_product_path : $config->image_product_live_path;
            $pathToVideo = $getPathes ? $config->video_product_path : $config->video_product_live_path;

            foreach ($multimedia as &$media) {
                $linkToMedia = '';
                $linkToPreviewMedia = '';

                if ($media->media_abstract_type == 'image') {

                    if (!$getPathes) {
                        $linkToMedia = $pathToImage . '/noimage.gif';
                        $linkToPreviewMedia = $pathToImage . '/noimage.gif';
                    }

                    if ($media->media_src_abstract_type_text == 'link') {
                        $linkToMedia = $media->media_src;
                    } elseif ($media->media_src_abstract_type_text == 'name') {
                        $linkToMedia = '/' . $media->media_src;
                    }

                    if ($media->media_preview_abstract_type_text == 'link') {
                        $linkToPreviewMedia = $media->media_preview;
                    } elseif ($media->media_preview_abstract_type_text == 'name') {
                        $linkToPreviewMedia = '/' . $media->media_preview;
                    }
                } elseif ($media->media_abstract_type == 'video') {
                    $linkToPreviewMedia = $pathToVideo . '/video.gif';

                    if (!$getPathes) {
                        $linkToPreviewMedia = $pathToVideo . '/video.gif';
                    }

                    if ($media->media_src_abstract_type_text == 'link') {
                        $linkToMedia = $media->media_src;
                    } elseif ($media->media_src_abstract_type_text == 'name') {
                        $linkToMedia = JURI::root() . '/' . $media->media_src;
                    }

                    if ($media->media_preview_abstract_type_text == 'link') {
                        $linkToPreviewMedia = $media->media_preview;
                    } elseif ($media->media_preview_abstract_type_text == 'name') {
                        $linkToPreviewMedia = '/' . $media->media_preview;
                    }
                }

                if (!empty($linkToMedia)) {
                    $media->preparedLinkToMedia = $linkToMedia;
                }

                if (!empty($linkToPreviewMedia)) {
                    $media->preparedLinkToPreviewMedia = $linkToPreviewMedia;
                }
            }

            $originalMedia = is_array($originalMedia) ? $multimedia : reset($multimedia);
        }

        return $originalMedia;
    }

    public function deleteById(int $mediaId, bool $isDeleteFiles = true): bool
    {
        $result = false;

        if (!empty($mediaId)) {
            $mediaData = $this->getById($mediaId);
            
            if (!empty($mediaData->id)) {
                $result = $this->deleteMediaWithFiles($mediaData->id, [$mediaData], true, $isDeleteFiles);

                // Set new main row if we deleted main foto.
                if (!empty($mediaData->is_main)) {
                    $this->setMain($mediaData->product_id);
                }
            }
        
        }

        return $result;
    }

    public function deleteByProductId(int $productId, bool $isDeleteFiles = true): bool
    {
        $result = false;
		$mediaData = [];

        if (!empty($productId)) {
            if ($isDeleteFiles) {
                $mediaData = $this->getByProductId($productId) ?: [];
            }

            $result = $this->deleteMediaWithFiles($productId, $mediaData, false, $isDeleteFiles);
        }

        return $result;
    }

    protected function deleteMediaWithFiles(int $id, array $mediaData, bool $isMediaRowId = true, bool $isDeleteFiles = true)
    {
        $result = false;

        if (!empty($id)) {
            if ($isDeleteFiles && !empty($mediaData)) {
                foreach ($mediaData as $media) {
                    $mediaRow = $this->prepareDBResultToComfy($media, true);

                    if (!$this->isExistsMediaWithSameSrcById($mediaRow->id)) {
                        File::delete([
                            $mediaRow->preparedLinkToMedia
                        ]);
                    }

                    if (!$this->isExistsMediaWithSameSrcById($mediaRow->id, 'media_preview')) {
                        File::delete([
                            $mediaRow->preparedLinkToPreviewMedia
                        ]);
                    }
                }
            }

            $columnName = $isMediaRowId ? 'id' : 'product_id';
            $result = $this->deleteMediaWithoutFilesByColumnName($id, $columnName);
        }

        return $result;
    }

    public function deleteMediaWithoutFilesByColumnName(int $id, string $byColumn = 'id'): bool
    {
        $mediaData = $this->getById($id);
        $db = Factory::getDBO();
        $sql = "DELETE FROM {$db->escape(static::TABLE_NAME)} WHERE `{$byColumn}` = {$db->escape($id)}";
        $db->setQuery($sql);
        $isDeletedSuccess = $db->execute();

        if ($isDeletedSuccess && !empty($mediaData->is_main)) {
            $this->setMain($mediaData->product_id);
        }

        return $db->execute();
    }

    public function isExistsMediaWithSameSrcById(int $id, string $media = 'media_src'): bool
    {   
        $db = Factory::getDbo();
        $sql = 'SELECT COUNT(`id`) FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE ' . $db->qn($media) . ' LIKE (
            SELECT ' . $db->qn($media) . ' FROM ' . $db->qn(static::TABLE_NAME) . ' AS `product_media` WHERE `product_media`.`id` = ' . $db->escape($id) . '
        ) AND `id` != ' . $db->escape($id);
        $db->setQuery($sql);
        $count = $db->loadResult() ?: 0;
        $isExists = ($count >= 1);

        return $isExists;
    }
}