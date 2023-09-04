<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProduction_calendar extends JControllerLegacy
{
    protected $access;
    protected $model;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->model = JModelLegacy::getInstance("production_calendar", 'JshoppingModel');

        $this->registerTask('apply', 'save');
        checkAccessController("production_calendar");
        $this->access = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other", $this->access);

    }

    public function display($cachable = false, $urlparams = false)
    {
        $jshopConfig = JSFactory::getConfig();
        $doc = JFactory::getDocument();

        JHtml::stylesheet($jshopConfig->live_admin_path . 'css/calendar/main.min.css');
        JHtml::script($jshopConfig->live_admin_path . 'js/src/calendar/core/main.js');
        JHtml::script($jshopConfig->live_admin_path . 'js/src/calendar/core/locales-all.min.js');
        JHtml::script($jshopConfig->live_admin_path . 'js/src/calendar/plugins/daygrid/main.min.js');
        JHtml::script($jshopConfig->live_admin_path . 'js/src/calendar/index.js');
        JHtml::script($jshopConfig->live_admin_path . 'js/src/calendar/index.js', [], ['defer' => 'defer']);

        // Adding tag for calendar
        $tag = explode('-', JFactory::getLanguage()->getTag())[0];
        $doc->addScriptOptions('lang-tag', $tag);
        
        JText::script('COM_SMARTSHOP_SET_WORK_TIME');
        JText::script('COM_SMARTSHOP_DELETE_EVENT');
        JText::script('COM_SMARTSHOP_ADD_EVENT');
        JHtmlBootstrap::modal('a.modal');
        
        // Params
        $params = $this->model->getParams();

        // Adding working days
        $working_days = $params->working_days;
        
        if (is_null($working_days) || $working_days == 'null' ) {
            $working_days = "[]";
        }
        
        // creating weekend for new events
        $weekend = [];
        foreach ([0, 1, 2, 3, 4 ,5 ,6] as $day) {
            $days = json_decode($working_days);
            if (!in_array($day, $days)) {
                array_push($weekend, $day);
            }
        }
        
        $extra_weekend_days = $params->extra_weekend_days;
        if (is_null($extra_weekend_days) || $extra_weekend_days == 'null') {
            $extra_weekend_days = "[]";
        }
        
        $extra_working_days = $params->extra_working_days;
        if (is_null($extra_working_days) || $extra_working_days == 'null') {
            $extra_working_days = "[]";
        }

        $doc->addScriptOptions('weekends', json_encode($weekend));
        $doc->addScriptOptions('working-days', $working_days);
        $doc->addScriptOptions('extra_weekend_days', $extra_weekend_days);
        $doc->addScriptOptions('extra_working_days', $extra_working_days);

        $view = $this->getView('production_calendar', 'html');
        $view->set('access', $this->access);
        $view->display();
    }

    public function save()
    {
        $input = JFactory::getApplication()->input;

        $this->model->saveParams([
            'working_days' => $input->getVar('working_days'),
            'extra_weekend_days' => $input->getVar('extra_weekend_days'),
            'extra_working_days' => $input->getVar('extra_working_days'),
        ]);
        
        if ($this->getTask() == 'apply') {
            $this->setRedirect("index.php?option=com_jshopping&controller=production_calendar");
        } else {
            $this->setRedirect("index.php?option=com_jshopping&controller=other"); 
        }
    }
	
	public function save_days_ajax()
    {
        $input = JFactory::getApplication()->input;

        $this->model->savedays($input->getVar('working_days'));
        die();
    }

    public function cancel()
    {
		$this->setRedirect('index.php?option=com_jshopping&controller=other');
	}

    public function modal()
    {
        JHtml::stylesheet(JUri::root() . '/media/jui/css/bootstrap.min.css');
        $fisrt_day = JFactory::getLanguage()->getFirstDay();

        $view = $this->getView('production_calendar', 'html');
        $view->setLayout('modal');

        $working_days = $this->model->getParams()->working_days;
        if (is_null($working_days) || $working_days == 'null' ) {
            $working_days = "[]";
        }

        $view->set('first_day', $fisrt_day);
        $view->set('working_days', json_decode($working_days));
        $view->set('days', [
            JText::_('MON'),
            JText::_('TUE'),
            JText::_('WED'),
            JText::_('THU'),
            JText::_('FRI'),
            JText::_('SAT'),
            JText::_('SUN')
        ]);
        $view->displayModal();
    }

    public function custom_options($cachable = false, $urlparams = false)
    {
        $params = $this->model->getParams();
		$view = $this->getView("production_calendar", 'html');
        $view->setLayout("configurations");
        $view->set("show_in_product", $params->show_in_product);
        $view->set("show_in_product_list", $params->show_in_product_list);
        $view->set("show_in_cart_checkout", $params->show_in_cart_checkout);
        $view->set("production_time", $params->production_time);
        $view->displayConfigurations();
    }
    
    public function configurations_apply($cachable = false, $urlparams = false)
    {
        $input = JFactory::getApplication()->input;

        $this->model->saveParams([
            'show_in_product' => $input->getVar('show_in_product'),
            'show_in_product_list' => $input->getVar('show_in_product_list'),
            'show_in_cart_checkout' => $input->getVar('show_in_cart_checkout'),
            'production_time' => $input->getVar('production_time')
        ]);

		$this->setRedirect('index.php?option=com_jshopping&controller=production_calendar', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
 
}