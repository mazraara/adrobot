<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Mail extends Component
{
	public $emailTemplatePath = '@app/views/email-template/notificationTemplate';
	public $fromEmail;
	public $fromName;
	
	public function __construct()
	{
		$this->fromEmail = Yii::$app->params['adminEmail'];
		$this->fromName = Yii::$app->params['productName'];
	}
	
	/**
     * Send email
     * @param string $toEmail Recipiant email
	 * @param string $subject Email subject
	 * @param string $content Email body
	 * @param string $fromEmail Sender email
	 * @param string $fromName Sender name
     * @return boolean true/false
     */
	private function send($toEmail, $subject, $content, $fromEmail = null, $fromName = null) 	
	{
		if (null == $fromEmail) {
			$fromEmail = $this->fromEmail;
		}
		
		if (null == $fromName) {
			$fromName = $this->fromName;
		}
	
		$response = Yii::$app->mailer
			->compose($this->emailTemplatePath, ['content' => $content])
			->setFrom([$fromEmail => $fromName])
			->setTo($toEmail)
			->setSubject($subject)
			->send();
			
		if ($response) {
			return true;
		}
		
		return false;
	}
}