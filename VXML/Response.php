<?php
namespace VXML;
use VXML\Translator;

require_once 'VXML/Translator/None.php';

final class Response
{
	const MSG_FAILURE = 1;
	const MSG_SUCCESS = 2;
	const MSG_DEBUG   = 3;
	
	private $messages = array();
	
	private $resolved_target = null;
	
	private $translator = null;
	
	public function __construct()
	{
		$this->translator = new Translator\None();
	}
	
	/**
	 * @param VXML\Response $response
	 */
	public function merge($response)
	{
		$this->messages = array_merge($this->messages, $response->getAllMessages());
	}
	
	public function convertFailuresToDebug()
	{
		foreach($this->messages as $key => $message)
		{
			if($message['type'] == self::MSG_FAILURE)
			{
				$this->messages[$key]['type'] = self::MSG_DEBUG;
			}
		}
	}
	
	/**
	 * Add a failure message to the response 
	 * 
	 * @param VXML\Rule\RuleAbstract $rule
	 * @param string $debug_msg OPTIONAL
	 */
	public function addFailure($rule, $debug_msg = null)
	{
		$this->addMessage(self::MSG_FAILURE, $rule, $rule->getMessage(), $debug_msg);
	}
	
	/**
	 * Add a success message to the response
	 * 
	 * @param VXML\Rule\RuleAbstract $rule
	 * @param string $debug_msg OPTIONAL
	 */
	public function addSuccess($rule, $debug_msg = null)
	{
		$this->addMessage(self::MSG_SUCCESS, $rule, null, $debug_msg);
	}
	
	/**
	 * Add a debug message to the response
	 * 
	 * @param VXML\Rule\RuleAbstract $rule
	 * @param string $debug_msg
	 */
	public function addDebug($rule, $debug_msg)
	{
		$this->addMessage(self::MSG_DEBUG, $rule, null, $debug_msg);
	}
	
	public function removeByRule($rule)
	{
		foreach($this->messages as $key => $message)
		{
			if($message['rule'] === $rule)
			{
				unset($this->messages[$key]);
				return $message;
			}
		}
		
		return false;
	}
	
	/**
	 * Get all messages
	 * 
	 * @return array
	 */
	public function getAllMessages()
	{
		return $this->messages;
	}
	
	/**
	 * Get failures
	 * 
	 * @return array
	 */
	public function getFailures()
	{
		$errors = array();
		foreach($this->messages as $msg)
		{
			if($msg['error'])
			{
				$errors[] = $msg;
			}
		}
		return $errors;
	}
	
	/**
	 * Add a message
	 * 
	 * @param integer $type
	 * @param VXML\Rule\RuleAbstract $rule
	 * @param string $msg
	 * @param string $debug_msg
	 */
	private function addMessage($type, $rule, $msg, $debug_msg)
	{
		$this->messages[] = array(
			'type'	  => $type,
			'rule'    => $rule,
			'target'  => $rule->getResolvedTarget(),
			'message' => $msg,
			'debug'   => $debug_msg
		);
	}
}