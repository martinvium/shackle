<?php
namespace VXML\Rule;

require_once 'DecoratorAbstract.php';

final class Silence extends DecoratorAbstract
{
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		if(! $this->rule->execute($context, $response))
		{
			$message = $response->removeByRule($this->rule);
			$response->addDebug($message['rule'], $message['debug'] . ' (silenced)');
			return false;
		}
		
		return true;
	}
}