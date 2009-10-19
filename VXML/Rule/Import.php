<?php
namespace VXML\Rule;

use VXML;

require_once 'CompositeAbstract.php';

final class Import extends CompositeAbstract
{
// STATIC
	/**
	 * @var VXML\Rule\RuleAbstract
	 */
	static private $rule;
	
	static private $stack = array();
	
	/**
	 * @return VXML\Rule\RuleAbstract
	 */
	static public function getInstance()
	{
		return self::$rule;
	}
	
	static private function set($rule)
	{
		self::$rule = $rule;
	}
	
	static private function save()
	{
		array_push(self::$stack, self::$rule);
	}
	
	static private function restore()
	{
		self::$rule = array_pop(self::$stack);
	}
	
// PROTECTED
	protected function initialize()
	{
		$this->addOption('path', null);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$path = $this->getOption('path');
		
		self::save();
		self::set($this);
		require $path;
		self::restore();
		
		return parent::evaluate($event);
	}
}