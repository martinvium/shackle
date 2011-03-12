<?php
namespace VXML\Rule;

use VXML\Event;

final class StringLength extends RuleAbstract
{
	const MAX_LENGTH = 'VXML_Rule_StringLength::MAX_LENGTH';
	
	protected function initialize()
	{
		$this->addOption('min', 0);
		$this->addOption('max', self::MAX_LENGTH);
		$this->addOption('charset', 'utf-8');
	}
	
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$value = $event->getContext()->getPassedValue();
		$strlen = iconv_strlen($value, $this->getOption('charset'));
		
		$min = $this->getOption('min');
		$max = $this->getOption('max');
		
		if(! $min && $max == self::MAX_LENGTH)
			throw new \InvalidArgumentException('either min or max must be defined in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
		
		if($strlen < $min)
		{
			$event->getResponse()->addFailure($this, 'string to short (' . $min . '<' . $strlen . ')');
			return false;
		}
		
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