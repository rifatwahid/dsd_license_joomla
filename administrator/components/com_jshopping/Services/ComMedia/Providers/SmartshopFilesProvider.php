<?php 

use Joomla\Component\Media\Administrator\Provider\ProviderInterface;
use Joomla\Plugin\Filesystem\Local\Adapter\LocalAdapter;

class SmartshopFilesProvider implements ProviderInterface
{
    public function getID()
    {
        return 'smartshoplocal';
    }

    public function getDisplayName()
    {
        return 'Smartshop';
    }

    public function getAdapters()
    {
        $directory = 'components/com_jshopping/files/';
        $directoryPath = JPATH_ROOT . '/components/com_jshopping/files/';

        $adapter = new LocalAdapter(
            $directoryPath, $directory
        );

        return [
            'components/com_jshopping/files/' => $adapter
        ];
    }
}