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
    static private $rule;
    
    static private $stack = array();
    
    /**
     * @return Rule
     */
    static public function getInstance()
    {
        return self::$rule;
    }
    
    static private function set($rule)
    {
        self::$rule = $rule;
    }
    
    static private function save()
    {
        array_push(self::$stack, self::$rule);
    }
    
    static private function restore()
    {
        self::$rule = array_pop(self::$stack);
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