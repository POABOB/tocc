<?php
namespace core\lib;
if ( ! defined('PPP')) exit('非法入口');
use core\lib\config;
//未完成
class Mailer
{
    private $transport;
    private $mailer;
    private $message;
	public function __construct()
	{
		$this->transport = (new Swift_SmtpTransport(config::get('host', 'mailer'), config::get('port', 'mailer'), config::get('host', 'encryption')))
            ->setUsername(config::get('username', 'mailer'))
            ->setPassword(config::get('password', 'mailer'));
        $this->mailer = new Swift_Mailer($transport);
    }
    
    public function setMessage($header = '測試信件', $from = array('poaMailer@gmail.com'), $to = array('zxc752166@gmail.com'), $body = '測試信件內容哦', $attach = null)
	{
        $this->message = (new Swift_Message($header))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body);
        if($attach != null) {
            $this->message->attach($attach);
        }
    }
    public function send()
	{
        try{
            $this->mailer->send($message);
        }catch(Swift_TransportException $e){
            echo $e->getMessage();
        }
	}
}