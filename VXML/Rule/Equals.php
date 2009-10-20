<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class Equals extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('equals', null);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		if($this->getOption('equals') === null)
			throw new \InvalidArgumentException('equals is undefined');
		
		if($event->getContext()->getPassedValue() == $this->getOption('equals'))
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this, 'value not equal (value: ' . $event->getContext()->getPassedValue() . ', should equal: ' . $this->getOption('equals') . ')');
		return false;
	}
}