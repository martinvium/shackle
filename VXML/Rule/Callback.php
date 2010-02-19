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
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$callback = $this->getOption('callback');
		
		$this->addListener('callback', $callback);
		if(count(array_filter($this->invoke('callback', $event))) == 1)
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this);
		return false;
	}
}