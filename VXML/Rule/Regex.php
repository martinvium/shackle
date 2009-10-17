<?php
namespace VXML\Rule;

require_once 'RuleAbstract.php';

final class Regex extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('pattern', null);
	}
	
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		if($this->getOption('pattern') === null)
			throw new \InvalidArgumentException('pattern is undefined');
		
		if(preg_match($this->getOption('pattern'), $context->getPassedValue()))
		{
			return true;
		}
		
		$response->addFailure($this);
		return false;
	}
}