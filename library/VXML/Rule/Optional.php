<?php
namespace VXML\Rule;

use VXML;

require_once 'DecoratorAbstract.php';

/**
 * Make decorated rule optional. The value will still be validated, 
 * but if value is empty, the rule will be successfull.
 * 
 * Example
 * new Rule\Optional(new Rule\Equal(target, options))
 */
final class Optional extends DecoratorAbstract
{
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$context = $event->getContext();
		$context->save();
		$context->setRelativeTarget($this->rule->getRelativeTarget());
		$values = $context->getPassedValues(\VXML\Context::ALL_TARGETS);
		$context->restore();
		
		foreach($values as $value)
		{
			if(! empty($value))
				return $this->rule->execute($context, $event->getResponse());
		}
		
		$event->getResponse()->addDebug($this, 'value was optional and empty');
		$this->rule->invoke('optional', $event);
		return true;
	}
}