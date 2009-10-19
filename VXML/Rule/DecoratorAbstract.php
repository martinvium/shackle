<?php
namespace VXML\Rule;

require_once 'VXML/RuleAbstract.php';

abstract class DecoratorAbstract extends RuleAbstract
{
	/**
	 * @var VXML\Rule\RuleAbstract
	 */
	protected $rule;
	
	/**
	 * @param VXML\Rule\RuleAbstract $rule
	 */
	public function __construct($rule, $options = array())
	{
		$this->rule = $rule;
		
		parent::__construct('.', $options);
	}
	
	public function add($rule)
	{
		return $this->rule->add($rule);
	}
	
	public function addListener($type, $rule)
	{
		return $this->rule->addListener($type, $rule);
	}
	
	public function getMessage()
	{
		return $this->rule->getMessage();
	}
}