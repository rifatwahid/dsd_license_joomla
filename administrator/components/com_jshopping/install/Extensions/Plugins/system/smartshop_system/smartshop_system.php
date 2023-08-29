<?php

defined('_JEXEC') or die;

class plgSystemSmartshop_system extends JPlugin
{   
    public function __construct(&$subject, $config)
	{
        parent::__construct($subject, $config);
        $this->app = JFactory::getApplication();
    } 

    public function onAfterRoute()
    {   
        $this->redirectToShopAccountIfUrlMath();
        $this->changePathOfMediaManager();
    }

    protected function changePathOfMediaManager()
    {
        if ($this->app->isClient('administrator')) {
            $input = $this->app->input;
            $isGetImgsVideo = $input->get('author') == 'smartshopimgsvideo';
            $isGetAllFiles = $input->get('author') == 'smartshopallfiles';
            $isGetOnlyImg = $input->get('author') == 'smartshopimgs';
            
            if ($input->get('option') == 'com_media' && ($isGetAllFiles ||  $isGetOnlyImg || $isGetImgsVideo)) {
                $this->includeShopCoreFile();

                if ($input->get('view') == 'imagesList' && ($isGetAllFiles || $isGetImgsVideo)) {
                    $input->set('layout', 'smartshop');
                }

                $shopConfig = JSFactory::getConfig();
                $mediaComponent = JComponentHelper::getComponent('com_media');
                $mediaParams = $mediaComponent->getParams();
                $mediaParams->set('file_path', $shopConfig->path_to_files);
                $mediaParams->set('image_path', $shopConfig->path_to_files);
                $mediaComponent->setParams($mediaParams);
            }
        }
    }

    protected function includeShopCoreFile()
    {
        if (!class_exists('JSFactory')) {
            require_once(JPATH_ROOT . '/components/com_jshopping/lib/jtableauto.php');
            JTable::addIncludePath(JPATH_ROOT . '/components/com_jshopping/tables');
            require_once(JPATH_ROOT . '/components/com_jshopping/lib/factory.php');
        }
    }

    protected function redirectToShopAccountIfUrlMath()
    {
        if ($this->app->isClient('site')) {
            $this->redirectToIfOptionsEquals([
                'option' => 'com_users',
                'view' => 'login',
            ], '/index.php?option=com_jshopping&view=user&task=login');
    
            $this->redirectToIfOptionsEquals([
                'option' => 'com_users',
                'view' => 'registration',
            ], '/index.php?option=com_jshopping&view=user&task=register');
    
            $this->redirectToIfOptionsEquals([
                'option' => 'com_users',
                'view' => 'profile',
                'user_id' => true
            ], '/index.php?option=com_jshopping&view=user');
    
            $this->redirectToIfOptionsEquals([
                'option' => 'com_users',
                'view' => 'profile',
                'layout' => false
            ], '/index.php?option=com_jshopping&view=user');
        }
    }

    protected function redirectToIfOptionsEquals(array $options, string $to): void
    {
        $input = $this->app->input;
        $numberOfMatchConditions = 0;
        foreach ($options as $optionKey => $optionValue) {
            $isTextEqual = $input->get($optionKey) == $optionValue;
            $isBoolTrueAndIsExistOption = (is_bool($optionValue) && $optionValue === true && !empty($input->get($optionKey)));
            $isBoolFalseAndIsNotExistOption = (is_bool($optionValue) && $optionValue === false && empty($input->get($optionKey)));

            if ($isBoolTrueAndIsExistOption || $isTextEqual || $isBoolFalseAndIsNotExistOption) {
                ++$numberOfMatchConditions;
            }
        }

        $isAllOptionsEquals = (count($options) == $numberOfMatchConditions);

        if ($isAllOptionsEquals && !empty($to)) {
            $this->app->redirect(JRoute::_($to, false));
        }
    }
}