<?php
namespace VXML\Rule;

use VXML;

require_once 'RuleAbstract.php';

final class StringLength extends RuleAbstract
{
	const MAX_LENGTH = 'VXML_Rule_StringLength::MAX_LENGTH';
	
	protected function initialize()
	{
		$this->addOption('min', 0);
		$this->addOption('max', self::MAX_LENGTH);
	}
	
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$value = $event->getContext()->getPassedValue();
		$strlen = mb_strlen($value, 'utf-8');
		
		$min = $this->getOption('min');
		if($strlen < $min)
		{
			$event->getResponse()->addFailure($this, 'string to short (' . $min . '<' . $strlen . ')');
			return false;
		}
		
		$max = $this->getOption('max');
		if($max === self::MAX_LENGTH)
		{
			$max = $strlen;
		}
		
		if($strlen > $max)
		{
			$event->getResponse()->addFailure($this, 'string to long (' . $min . '>' . $strlen . ')');
			return false;
		}
		
		return true;
	}
}