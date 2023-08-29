<?php 

defined('JPATH_PLATFORM') or die;

abstract class JHtmlSmartShopModal
{
    const PATH_TO_MARKUP_FOLDER = __DIR__ . '/markups/smartshopmodal/';

    public static function renderModalWindow(array $params = []): string
    {
        $modalId = $params['modalId'] ?: '';
        $modalTitle = $params['modalTitle'] ?: '';
        $modalBody = $params['modalBody'] ?: '';

        return static::renderWindow($modalId, $modalTitle, $modalBody, $params);
    }

    /**
     * @deprecated
     */
    public static function renderWindow(string $modalId, string $modalTitle, string $modalBody, array $params = []): string
    {
        $pathToModal = static::PATH_TO_MARKUP_FOLDER . 'modal.php';
        $contentParams = [
            'modalId' => $modalId,
            'modalTitle' => $modalTitle,
            'modalBody' => $modalBody, 
        ];
        $contentParams = array_merge($contentParams, $params);
        $content = getContentOfFile($pathToModal, $contentParams);

        return $content;
    }

    public static function renderButton(string $btnId, string $dataTarget, string $btnAttr, string $btnText): string
    {
        $pathToModal = static::PATH_TO_MARKUP_FOLDER . 'button.php';

        $content = getContentOfFile($pathToModal, [
            'btnId' => $btnId,
            'btnAttr' => $btnAttr,
            'btnText' => $btnText,
            'dataTarget' => $dataTarget
        ]);

        return $content;
    }
}