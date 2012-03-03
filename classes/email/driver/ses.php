<?php

/**
 * Amazon Email Delivery library for FuelPHP
 *
 * @package		Amazon
 * @version		1.1
 * @author		Rob McCann (rob@robmccann.co.uk)
 * @link		http://github.com/unforeseen/fuel-amazon-ses
 * 
 */

class Email_Driver_SES extends \Email_Driver {

	public $region = null;
	protected $debug = true;
	
	public function __construct($config) 
	{
		parent::__construct($config);
		\Config::load('ses', true);
		
		$this->region = \Config::get('ses.region','us-east-1');
	}

	/**
	 * Sends the email using the Amazon SES email delivery system
	 * 
	 * @return boolean	True if successful, false if not.
	 */	
	protected function _send()
	{
		$params = array(
			'Action' => 'SendEmail',
			'Source' => static::format_addresses(array($this->config['from'])),
			'Message.Subject.Data' => $this->subject,
			'Message.Body.Text.Data' => $this->alt_body,
			'Message.Body.Html.Data' => $this->body
		);
		
		$i = 0;
		foreach($this->to as $value)
		{
			$params['Destination.ToAddresses.member.'.($i+1)] = static::format_addresses(array($value));
			++$i;
		}
		
		$i = 0;
		foreach($this->cc as $value)
		{
			$params['Destination.CcAddresses.member.'.($i+1)] = static::format_addresses(array($value));
			++$i;
		}
		
		$i = 0;
		foreach($this->bcc as $value)
		{
			$params['Destination.BccAddresses.member.'.($i+1)] = static::format_addresses(array($value));
			++$i;
		}
		
		$i = 0;
		foreach($this->reply_to as $value)
		{
			$params['ReplyToAddresses.member.'.($i+1)] = static::format_addresses(array($value));
			++$i;
		}	

		$date = date(DATE_RSS);
		$signature = $this->_sign_signature($date);
		
		$curl = \Request::forge('https://email.' . $this->region . '.amazonaws.com/', array(
			'driver' => 'curl',
			'method' => 'post'
		))->set_header('Content-Type','application/x-www-form-urlencoded')
			->set_header('X-Amzn-Authorization','AWS3-HTTPS AWSAccessKeyId='.\Config::get('ses.access_key').', Algorithm=HmacSHA256, Signature=' . $signature)
			->set_header('Date', $date);
		
		$response = $curl->execute($params);		
		
		if (intval($response-> response()->status / 100) != 2) 
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Calculate signature
	 * @param	string	date used in the header
	 * @return	string 	RFC 2104-compliant HMAC hash
	 */
	private function _sign_signature($date)
	{
		$hash = hash_hmac('sha256', $date, \Config::get('ses.secret_key'), TRUE);
		return base64_encode($hash);
	}

}
