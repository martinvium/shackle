<?php
namespace VXML\Rule;

use VXML;

require_once 'DecoratorAbstract.php';

/**
 * Silences the decorated rule, changing any failures to debug. You will
 * still be able to hook into the failure via listeners.
 * 
 * Example:
 * new Rule\Silent(new Rule\Equal(target, options))
 */
final class Silent extends DecoratorAbstract
{
	/**
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
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