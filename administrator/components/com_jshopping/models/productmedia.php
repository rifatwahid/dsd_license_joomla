<?php 

class JshoppingModelProductMedia extends JModelLegacy
{
    const TABLE_NAME = '#__jshopping_products_media';

    public function getMediaByProductId($productId): array
    {
        $result = [];

        if (!empty($productId)) {
            $db = \JFactory::getDBO();
            $sqlSelect = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE `product_id` = ' . $productId;
            $db->setQuery($sqlSelect);

            $result = $db->loadObjectList() ?: [];
        }

        return $result;
    }

    public function duplicateMediaFiles($fromProductId, $toProductId)
    {
        $duplicatedMedia = [];

        if (!empty($fromProductId) && !empty($toProductId)) {
            $media = $this->getMediaByProductId($fromProductId);

            if (!empty($media)) {
                $separetedMedia = deletePropertiesFromObjectList($media, ['id', 'product_id']);

                if (!empty($separetedMedia)) {
                    $config = JSFactory::getConfig();
                    $videoTypeCode = 2;
                    $linkTypeCode = 4;
                
                    foreach($separetedMedia as $loopMedia) {
                        if (!empty($loopMedia) && is_object($loopMedia)) {
                            $isSrcSuccessCopied = true;
                            $isPreviewSuccessCopied = true;
                            $newPreviewName = '';
                            $pathToSrcFolder = ($loopMedia->media_abstract_type == $videoTypeCode) ? $config->video_product_path : $config->image_product_path;
                            $pathToPreviewFolder = $config->image_product_path;

                            // Copy file of `media_src`
                            if (!empty($loopMedia->media_src) && $loopMedia->media_src_abstract_type != $linkTypeCode) {
                                $newSrcName = "{$fromProductId}_{$toProductId}_{$loopMedia->media_src}";

                                $pathToSrcFile = "{$pathToSrcFolder}/{$loopMedia->media_src}";
                                $newPathToSrcFile = "{$pathToSrcFolder}/{$newSrcName}";
                                
                                if (JFile::exists($pathToSrcFile)) {
                                    $isSrcSuccessCopied = JFile::copy($pathToSrcFile, $newPathToSrcFile);
                                }

                                if (!$isSrcSuccessCopied || !JFile::exists($pathToSrcFile)) {
                                    continue;
                                }

                                $loopMedia->media_src = $newSrcName;
                            }

                            // Copy file of `media_preview`
                            if (!empty($loopMedia->media_preview) && $loopMedia->media_preview_abstract_type != $linkTypeCode) {
                                $newPreviewName = "{$fromProductId}_{$toProductId}_{$loopMedia->media_preview}";

                                $pathToPreviewFile = "{$pathToPreviewFolder}/{$loopMedia->media_preview}";
                                $newPathToPreviewFile = "{$pathToPreviewFolder}/{$newPreviewName}";

                                if (JFile::exists($pathToPreviewFile)) {
                                    $isPreviewSuccessCopied = JFile::copy($pathToPreviewFile, $newPathToPreviewFile);
                                }

                                if (!$isPreviewSuccessCopied || !JFile::exists($pathToPreviewFile)) {
                                    $newPreviewName = 'noimage.gif';
                                }

                                $loopMedia->media_preview = $newPreviewName;
                            }

                            if ($isSrcSuccessCopied) {
                                $duplicatedMedia[] = $loopMedia;
                            }
                        }
                    }
                }
            }
        }

        return $duplicatedMedia;
    }

    public function saveMediaWithFiles($fromProductId, $toProductId)
    {
        $result = true;
        $duplicatedMedia = $this->duplicateMediaFiles($fromProductId, $toProductId);

        if (!empty($duplicatedMedia)) {
            $isSuccessSaved = $this->saveMediaInStorage($toProductId, $duplicatedMedia);

            if (!$isSuccessSaved) {
                $this->deleteByProductId($toProductId);
                $result = false;
            }

            $this->setMain($toProductId);
        }

        return $result;
    }

    public function copyMediaInStorage(int $fromProductId, int $toProductId): bool
    {
        $isSuccessSaved = true;
        $mediaToCopy = $this->getMediaByProductId($fromProductId);

        if (!empty($mediaToCopy)) {
            $mediaToCopy = deletePropertiesFromObjectList($mediaToCopy, ['id', 'product_id']);
            $isSuccessSaved = $this->saveMediaInStorage($toProductId, $mediaToCopy);
            $this->setMain($toProductId);
        }

        return $isSuccessSaved;
    }

    public function deleteByProductId(int $productId, bool $isDeleteFiles = true): bool
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modeOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');
        $isSuccessDeleted = $modeOfProductsMediaFront->deleteByProductId($productId, $isDeleteFiles);

        return $isSuccessDeleted;
    }

    public function saveMediaInStorage($toProductId, array $media)
    {
        $result = true;

        if (!empty($toProductId) && !empty($media)) {
            $isFindedMainMedia = false;
            $successSavedMedia = [];

            foreach ($media as $mediaObj) {
                if (!empty($mediaObj->media_src)) {
                    try {
                        $tableOfProductMedia = JSFactory::getTable('ProductsMedia');
                        $isSuccessBinded = $tableOfProductMedia->bind((array)$mediaObj);
                        $tableOfProductMedia->product_id = $toProductId;
                        
                        if (!empty($mediaObj->is_main)) {
                            $isFindedMainMedia = true;
                        }

                        if ($isSuccessBinded) {
                            $isSuccessStore = $tableOfProductMedia->store();

                            if (!$isSuccessStore) {
                                continue;
                            }

                            $successSavedMedia[] = $tableOfProductMedia->id;
                        }
                    } catch (\Exception $e) {
                        $result = false;
                        return;
                    }
                }
            }

            if (!$isFindedMainMedia && !empty($tableOfProductMedia->id)) {
                $tableOfProductMedia->is_main = true;
                $tableOfProductMedia->store();
            }
        }

        return $result;
    }

    public function setMain(?int $productId, ?int $mediaId = 0, bool $changeAndProductImg = true): bool
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modelOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');

        return $modelOfProductsMediaFront->setMain($productId, $mediaId, $changeAndProductImg);
    }
}