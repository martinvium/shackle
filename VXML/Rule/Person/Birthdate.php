<?php
namespace VXML\Rule\Person;
use VXML;

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
	 * @param VXML\Event $event
	 */
	public function evaluate($event)
	{
		if(! parent::evaluate($event))
		{
			return false;
		}
		
		$values = $event->getContext()->getPassedValues(array('year', 'month', 'day'));
		
		date_default_timezone_set('Europe/Copenhagen');
		$datetime = date_create();
		$datetime->setDate($values['year'], $values['month'], $values['day']);
		
		if($datetime->format('Y') > 1900 && $datetime->format('Y') < 2100)
		{
			return true;
		}
		
		$event->getResponse()->addFailure($this, 'datetime: ' . $datetime->format('Y-m-d H:i:s'));
		return false;
	}
}