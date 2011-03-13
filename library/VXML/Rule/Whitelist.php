<?php
namespace VXML\Rule;

use VXML\Event;

final class Whitelist extends RuleAbstract
{
    protected function initialize()
    {
        $this->addOption('options', null);
        $this->addOption('strict', false);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        if(in_array($event->getContext()->getPassedValue(), $this->getOption('options'), $this->getOption('strict'))) {
            return true;
        }
        
        $event->getResponse()->addFailure($this, 'element not in whitelist (value: ' . $event->getContext()->getPassedValue() . ')');
        return false;
    }
}