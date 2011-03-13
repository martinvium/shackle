<?php
namespace VXML\Rule;

use VXML\Event;

/**
 * @todo rename to Range, shorter and number seems somewhat redundant?
 */
final class NumberRange extends RuleAbstract
{
    protected function initialize()
    {
        $this->addOption('min', 0);
        $this->addOption('max', 0);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $value = $event->getContext()->getPassedValue();
        
        $min = $this->getOption('min');
        $max = $this->getOption('max');
        
        if(! $min && ! $max)
            throw new \InvalidArgumentException('either min or max must be defined in rule: ' . get_class($this) . ' on target: ' . $this->getRelativeTarget());
        
        if($min && $value < $min)
        {
            $event->getResponse()->addFailure($this, 'minimum value for range reached (' . $value . ' < ' . $min . ')');
            return false;
        }
        
        if($max && $value > $max)
        {
            $event->getResponse()->addFailure($this, 'maximum value for range reached (' . $value . ' > ' . $max . ')');
            return false;
        }
        
        return true;
    }
}