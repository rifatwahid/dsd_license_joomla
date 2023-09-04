<?php 

include_once JPATH_ADMINISTRATOR . '/components/com_jshopping/views/panel/view.html.php';

class ViewInstaller
{
    public static function showJsPanelWithLayout($layoutName)
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JSFactory::getConfig()->live_admin_path . 'css/style.css');

        $view = new JshoppingViewPanel([
            'base_path' => JPATH_ADMINISTRATOR . '/components/com_jshopping/',
            'template_path' => JPATH_ADMINISTRATOR . '/components/com_jshopping/views/panel/tmpl/'
        ]);

        $view->setLayout($layoutName);
        $view->displayInfo();
    }

    public static function showSuccessInstalledTemplate()
    {
        $view = new JViewLegacy();
        $view->addTemplatePath(__DIR__ . '/tmpl');
        $view->setLayout('success_installed');

        echo $view->loadTemplate();
    }
}