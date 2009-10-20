<?php
namespace VXML\Rule;

use VXML;

require_once 'DecoratorAbstract.php';

final class Invert extends DecoratorAbstract
{
	/**
	 * @todo Re-adding rules like this will screw up targets
	 * @param VXML\Event $event
	 */
	protected function evaluate($event)
	{
		$mock_response = new VXML\Response();
		if(! $this->rule->execute($event->getContext(), $mock_response))
		{
			foreach($mock_response->getFailureMessages() as $failure)
			{
				$mock_response->addSuccess($failure['rule'], $failure['debug']);
			}
			return true;
		}
		else
		{
			foreach($mock_response->getSuccessMessages() as $success)
			{
				$event->getResponse()->addFailure($success['rule'], $success['debug']);
			}
			return false;
		}
	}
}