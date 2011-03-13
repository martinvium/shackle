<?php
namespace VXML\Rule;

use VXML\Rule;
use VXML\Event;

class Import extends CompositeAbstract
{
// STATIC
    /**
     * @var Rule
     */
    static private $_rule;
    
    static private $_stack = array();
    
    /**
     * @return Rule
     */
    static public function getInstance()
    {
        return self::$_rule;
    }
    
    static private function set($rule)
    {
        self::$_rule = $rule;
    }
    
    static private function save()
    {
        array_push(self::$_stack, self::$_rule);
    }
    
    static private function restore()
    {
        self::$_rule = array_pop(self::$_stack);
    }
    
// PROTECTED
    protected function initialize()
    {
        $this->addOption('path', null);
    }
    
    /**
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $path = $this->getOption('path');
        
        self::save();
        self::set($this);
        require $path;
        self::restore();
        
        return parent::evaluate($event);
    }
}