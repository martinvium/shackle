<?php
namespace VXML\Rule;

require_once 'RuleAbstract.php';

final class Equals extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('equals', null);
	}
	
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		if($this->getOption('equals') === null)
			throw new \InvalidArgumentException('equals is undefined');
		
		if($context->getPassedValue() == $this->getOption('equals'))
		{
			return true;
		}
		
		$response->addFailure($this, 'value not equal (value: ' . $context->getPassedValue() . ', should equal: ' . $this->getOption('equals') . ')');
		return false;
	}
}