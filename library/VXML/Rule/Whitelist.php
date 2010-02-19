<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class Whitelist extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('options', null);
		$this->addOption('strict', false);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		if(in_array($event->getContext()->getPassedValue(), $this->getOption('options'), $this->getOption('strict')))
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this, 'element not in whitelist (value: ' . $event->getContext()->getPassedValue() . ')');
		return false;
	}
}