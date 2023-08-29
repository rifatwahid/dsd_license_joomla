<?php 

class shMailer 
{
	protected $mailer;

	public function __construct()
	{
		$this->mailer = new JMail();
		$this->config = JFactory::getConfig();
	}

	public function sendMailToAdmin($title, $body)
	{

		if ( !empty($title) && !empty($body) ) {

			return $this->mailer->sendMail(
	            $this->config->get('mailfrom'),
	            $this->config->get('fromname'),
	            $this->config->get('mailfrom'),
	            $title,
	            $body,
	            1
	        ); 			

		}

		return false; 		
	}

	public function sendMsg($sender, $recipient, string $subject, string $msg, array $triggerInfo): bool
	{
		$dispatcher = \JFactory::getApplication();

		$mailer = JFactory::getMailer();
		$mailer->setSender($sender ?: []);
		$mailer->addRecipient($recipient ?: '');
		$mailer->setSubject($subject ?: '');
		$mailer->setBody($msg ?: '');
		$mailer->isHTML(true);

		if (!empty($triggerInfo['name'])) {
			if (empty($triggerInfo['data'])) {
				$triggerInfo['data'] = [];
			}
			
			$triggerInfo['data'][] = &$mailer;
			$dispatcher->triggerEvent('onBeforeSendMailChangeOrderStatusClient', $triggerInfo['data'] ?? []);
		}
		
		return $mailer->Send();
	}

}