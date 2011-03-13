<?php
namespace VXML\Rule;

use VXML\Event;
use VXML\Response;

final class Iterator extends DecoratorAbstract
{
    const NUM_VALUES = 'VXML_Rule_Iterate::NUM_VALUES';
    
    protected function initialize()
    {
        $this->setRelativeTarget($this->rule->getRelativeTarget());
        
        $this->addOption('min', self::NUM_VALUES);
        $this->addOption('max', self::NUM_VALUES);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $results = array();
        $response = $event->getResponse();
        $values = $event->getContext()->getPassedValue();
        
        if (! is_array($values)) {
            throw new \InvalidArgumentException('target for rule Iterator, may only be an array');
        }
        
        $childResponse = new Response();
        foreach ($values as $key => $value) {
            $this->rule->setRelativeTarget($key);
            $results[] = $this->rule->execute($event->getContext(), $childResponse);
        }
        
        $numValid = count(array_filter($results));
        
        $min = ($this->getOption('min') == self::NUM_VALUES ? count($results) : $this->getOption('min'));
        if ($numValid < $min) {
            $response->merge($childResponse);
            $response->addFailure($this, 'min limit reached (valid: ' . $numValid . ', min: ' . $min . ')');
            return false;
        }
        
        $max = ($this->getOption('max') == self::NUM_VALUES ? count($results) : $this->getOption('max'));
        if ($numValid > $max) {
            $response->merge($childResponse);
            $response->addFailure($this, 'max limit reached (valid: ' . $numValid . ', max: ' . $max . ')');
            return false;
        }
        
        $childResponse->convertFailuresToDebug();
        $response->merge($childResponse);
        return true;
    }
}