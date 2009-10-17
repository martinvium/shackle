<?php
namespace VXML\Rule;

use VXML;

require_once 'DecoratorAbstract.php';

final class Silence extends DecoratorAbstract
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