<?php
/**
 * Copyright 2011 Martin Vium
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
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