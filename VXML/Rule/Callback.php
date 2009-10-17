<?php
namespace VXML\Rule;

require_once 'RuleAbstract.php';

final class Callback extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('callback', null);
	}
	
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		$callback = $this->getOption('callback');
		
		if($callback === null)
			throw new \InvalidArgumentException('callback is undefined');
		
		$this->addListener('callback', $callback);
		if(count(array_filter($this->invoke('callback', $context, $response))) == 1)
		{
			return true;
		}
		
		$response->addFailure($this);
		return false;
	}
}