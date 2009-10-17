<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

abstract class CompositeAbstract extends RuleAbstract
{
	const NUM_RULES = 'VXML_Rule_CompositeAbstract::NUM_RULES';
	
	public function __construct($target, $options = array())
	{
		parent::__construct($target, $options);
	}
	
	protected function initialize()
	{
		$this->addOption('min', self::NUM_RULES);
		$this->addOption('max', self::NUM_RULES);
	}
	
	/**
	 * @param VXML\Rule\RuleAbstract $rule
	 * @return VXML\Rule\RuleAbstract
	 */
	public function add($rule)
	{
		return $this->addListener('components', $rule);
	}
	
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		$num_components = count($this->getListeners('components'));
		
		$child_response = new VXML\Response();
		$num_valid = count(array_filter($this->invoke('components', $context, $child_response)));
		
		$min_limit = ($this->getOption('min') == self::NUM_RULES ? $num_components : $this->getOption('min'));
		if($num_valid < $min_limit)
		{
			$response->merge($child_response);
			$response->addFailure($this, 'min limit reached (valid: ' . $num_valid . ', min: ' . $min_limit . ')');
			return false;
		}
		
		$max_limit = ($this->getOption('max') == self::NUM_RULES ? $num_components : $this->getOption('max'));
		if($num_valid > $max_limit)
		{
			$response->merge($child_response);
			$response->addFailure($this, 'max limit reached (valid: ' . $num_valid . ', max: ' . $max_limit . ')');
			return false;
		}
		
		$child_response->convertFailuresToDebug();
		$response->merge($child_response);
		return true;
	}
	
// PRIVATE
	private function getThresholdOption($name)
	{
		$num = $this->getOption($name);
		return $num == self::NUM_RULES ? count($this->getListeners('components')) : $num;
	}
}