<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class Equal extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('equal', null);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		if($event->getContext()->getPassedValue() == $this->getOption('equal'))
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this, 'value not equal (value: ' . $event->getContext()->getPassedValue() . ', should equal: ' . $this->getOption('equal') . ')');
		return false;
	}
}