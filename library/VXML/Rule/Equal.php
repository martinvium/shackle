<?php
namespace VXML\Rule;

use VXML\Event;

class Equal extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('equal', null);
	}
	
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		if($event->getContext()->getPassedValue() == $this->getOption('equal'))
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this, 'value not equal (value: ' . $event->getContext()->getPassedValue() . ', should equal: ' . $this->getOption('equal') . ')');
		return false;
	}
}