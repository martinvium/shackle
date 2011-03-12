<?php
namespace VXML\Rule;

use VXML\Event;

/**
 * Silences the decorated rule, changing any failures to debug. You will
 * still be able to hook into the failure via listeners.
 * 
 * Example:
 * new Silent(new Equal(target, options))
 */
final class Silent extends DecoratorAbstract
{
	/**
	 * @param Event $event
	 */
	protected function evaluate(Event $event)
	{
		$response = $event->getResponse();
		if(! $this->rule->execute($event->getContext(), $response))
		{
			$message = $response->removeByRule($this->rule);
			$response->addDebug($message['rule'], $message['debug'] . ' (silenced)');
		}
		
		return true;
	}
}