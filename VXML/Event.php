<?php
namespace VXML;

final class Event
{
	private $rule;
	private $context;
	private $response;
	
	public function __construct(Rule\RuleAbstract $rule, Context $context, Response $response)
	{
		$this->rule = $rule;
		$this->context = $context;
		$this->response = $response;
	}
	
	/**
	 * @return VXML\Rule\RuleAbstract
	 */
	public function getRule()
	{
		return $this->rule;
	}
	
	/**
	 * @return VXML\Context
	 */
	public function getContext()
	{
		return $this->context;
	}
	
	/**
	 * @return VXML\Response
	 */
	public function getResponse()
	{
		return $this->response;
	}
}