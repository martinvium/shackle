<?php
namespace VXML\Rule;

use VXML\Event;

final class Regex extends RuleAbstract
{
    protected function initialize()
    {
        $this->addOption('pattern', null);
    }
    
    /**
     * @param VXML\Event $event
     */
    protected function evaluate(Event $event)
    {
        if(preg_match($this->getOption('pattern'), $event->getContext()->getPassedValue()))
        {
            return true;
        }
        
        $event->getResponse()->addFailure($this, 'regex failed (value: ' . $event->getContext()->getPassedValue() . ', pattern: ' . $this->getOption('pattern') . ')');
        return false;
    }
}