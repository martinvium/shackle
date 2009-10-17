<?php
namespace VXML\Rule\Person;
use VXML\Rule\CompositeAbstract;

require_once 'VXML/Rule/CompositeAbstract.php';

final class Birthdate extends CompositeAbstract
{
	public function __construct($year_month_day, $message, $options = array())
	{
		if(! is_array($year_month_day))
			throw new \InvalidArgumentException('you must specify year, month and day targets for birthdate rule');
		
		parent::__construct($year_month_day, $message, $options);
	}
	
	/**
	 * @param VXML_Context $context
	 * @param VXML_Response $response
	 */
	public function evaluate($context, $response)
	{
		if(! parent::evaluate($context, $response))
		{
			return false;
		}
		
		$values = $context->getPassedValues(array('year', 'month', 'day'));
		
		date_default_timezone_set('Europe/Copenhagen');
		$datetime = date_create();
		$datetime->setDate($values['year'], $values['month'], $values['day']);
		
		if($datetime->format('Y') > 1900 && $datetime->format('Y') < 2100)
		{
			return true;
		}
		
		$response->addFailure($this, 'datetime: ' . $datetime->format('Y-m-d H:i:s'));
		return false;
	}
}