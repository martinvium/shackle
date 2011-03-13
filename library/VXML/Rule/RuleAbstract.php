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

use VXML\Event;
use VXML\Response;
use VXML\Context;
use VXML\Rule;

abstract class RuleAbstract implements Rule
{
    /**
     * Relative target of the rule, to be applied to the context
     * 
     * @var array
     */
    private $_target = array();
    
    /**
     * Last target of the rule, after it has been applied to the context
     * WARNING: This can change for rules that are applied multiple times
     * e.g. by Iterator
     * 
     * @var array
     */
    private $_resolvedTargets = array();
    
    /**
     * Rules options
     * 
     * @var array
     */
    private $_options = array();
    
    /**
     * Rules and callbacks listening to this rules events
     * 
     * @var array
     */
    private $_eventListeners = array();
    
// MAGIC
    /**
     * @param mixed $target
     * @param array $options OPTIONAL
     */
    public function __construct($target, $options = array())
    {
        $this->setRelativeTarget($target);
        $this->addOption('message', false);
        $this->initialize();
        
        $this->setOptions($options);
    }
    
// PUBLIC
    /**
     * To be implemented by a composite rule
     * 
     * @param Rule $rule
     * @return Rule
     */
    public function add($rule)
    {
        throw new \Exception('not supported');
    }
    
    /**
     * Add a rule or callback to an event
     * 
     * @param string $event
     * @param string|Rule $rule
     * @return $rule
     */
    public function addListener($event, $rule)
    {
        return $this->_eventListeners[$event][] = $rule;
    }
    
    /**
     * Evaluate rule and invoke events and setup context
     * 
     * @param Context $context
     * @param Response $response
     * @return boolean
     */
    public function execute(Context $context, Response $response)
    {
        $context->save();
        $context->setRelativeTarget($this->getRelativeTarget());
        $this->_resolvedTargets = $context->getResolvedTarget();
        
        $event = new Event($this, $context, $response);
        $this->invoke('before', $event);
        
        $ret = $this->evaluate($event);
        if ($ret) {
            $response->addSuccess($this);
            $this->invoke('valid', $event);
        } else {
            $this->invoke('failure', $event);
        }
        
        $this->invoke('after', $event);
        
        $context->restore();
        
        return $ret;
    }
    
    /**
     * Get the rules failure message
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->getOption('message');
    }
    
    /**
     * Get the relative target of the rule
     * 
     * @return array
     */
    public function getRelativeTarget()
    {
        return $this->_target;
    }
    
    /**
     * Set the relative target of the rule
     * 
     * @param string $target
     */
    final public function setRelativeTarget($target)
    {
        $this->_target = $target;
    }
    
    /**
     * Get the last target of the rule, after it has been applied to the context
     * WARNING: This can change for rules that are applied multiple times
     * e.g. by Iterator
     * 
     * @return array
     */
    final public function getResolvedTarget()
    {
        return $this->_resolvedTargets;
    }
    
    /**
     * Invoke an collection of rules based on event type
     * 
     * @param string $type
     * @param Event $event
     * @return array
     */
    final public function invoke($type, $event)
    {
        $results = array();
        if (isset($this->_eventListeners[$type])) {
            foreach ($this->_eventListeners[$type] as $eventHandler) {
                $results[] = $this->handleEvent($eventHandler, $event);
            }
        }
        
        return $results;
    }
    
    protected function handleEvent($eventHandler, $event)
    {
        if ($eventHandler instanceof Rule) {
            return $eventHandler->execute($event->getContext(), $event->getResponse());
        } else if ($eventHandler instanceof Closure) {
            return (bool)$eventHandler($event);
        } else if (is_callable($eventHandler)) {
            return (bool)call_user_func($eventHandler, $event);
        } else {
            throw new \InvalidArgumentException('invalid event type: ' . var_export($eventHandler, true));
        }
    }

    /**
     * Evaluate the rule and add any messages to the response object
     *
     * @param Event $event
     * @return boolean
     */
    abstract protected function evaluate(Event $event);
    
    /**
     * Called to initialize any resources needed for the rule
     */
    protected function initialize()
    {
        
    }
    
    /**
     * Get option by name
     * 
     * @throws InvalidArgumentException if option name is unknown
     * @param string $name
     * @return mixed
     */
    final protected function getOption($name)
    {
        if ($this->_options[$name] === null) {
            $msg  = 'option "' . $name . '" is required, but was left undefined in rule: ';
            $msg .= get_class($this) . ' on target: ' . $this->getRelativeTarget();
            throw new \InvalidArgumentException();
        }
        
        return $this->_options[$name];
    }
    
    /**
     * Register an option with a rule, if the default value is null, it is required
     * to be filled out in userland.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return void
     */
    final protected function addOption($name, $defaultValue)
    {
        $this->_options[$name] = $defaultValue;
    }
    
    /**
     * Set options of a rule
     * 
     * @param array $options
     * @return void
     */
    final protected function setOptions($options)
    {
        if (! is_array($options)) {
            $msg  = 'options must be an array in rule: ' . get_class($this);
            $msg .= ' on target: ' . $this->getRelativeTarget();
            throw new \InvalidArgumentException();
        }
        
        foreach ($options as $name => $value) {
            if (! array_key_exists($name, $this->_options)) {
                throw new \InvalidArgumentException('option "' . $name . '" does not exist');
            }
            
            $this->_options[$name] = $value;
        }
    }
    
    /**
     * Get all event listeners by event type
     * 
     * @param string $type
     * @return array
     */
    final protected function getListeners($type)
    {
        if (! array_key_exists($type, $this->_eventListeners)) {
            return array();
        }
        
        return $this->_eventListeners[$type];
    }
}