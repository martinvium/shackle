<?php
namespace VXML\Rule;

use VXML;

require_once 'VXML/RuleAbstract.php';

final class NumberRange extends RuleAbstract
{
	protected function initialize()
	{
		$this->addOption('min', 0);
		$this->addOption('max', 0);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$value = $event->getContext()->getPassedValue();
		
		$min = $this->getOption('min');
		if($min && $value < $min)
		{
			$event->getResponse()->addFailure($this, 'minimum value for range reached (' . $value . ' < ' . $min . ')');
			return false;
		}
		
		$max = $this->getOption('max');
		if($max && $value > $max)
		{
			$event->getResponse()->addFailure($this, 'maximum value for range reached (' . $value . ' > ' . $max . ')');
			return false;
		}
		
		return true;
	}
}