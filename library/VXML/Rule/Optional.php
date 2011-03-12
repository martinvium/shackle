<?php
namespace VXML\Rule;

use VXML\Context;
use VXML\Event;


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
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$context = $event->getContext();
		$context->save();
		$context->setRelativeTarget($this->rule->getRelativeTarget());
		$values = $context->getPassedValues(Context::ALL_TARGETS);
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