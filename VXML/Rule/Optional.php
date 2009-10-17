<?php
namespace VXML\Rule;

use VXML;

require_once 'DecoratorAbstract.php';

final class Optional extends DecoratorAbstract
{
	/**
	 * @param VXML\Context $context
	 * @param VXML\Response $response
	 */
	protected function evaluate($context, $response)
	{
		$context->save();
		$context->setRelativeTarget($this->rule->getRelativeTarget());
		$values = $context->getPassedValues(\VXML\Context::ALL_TARGETS);
		$context->restore();
		
		foreach($values as $value)
		{
			if(! empty($value))
				return $this->rule->execute($context, $response);
		}
		
		$response->addDebug($this, 'value was optional and empty');
		$this->rule->invoke('optional', $context, $response);
		return true;
	}
}