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
namespace VXML;

/**
 * Context acts as a front for rules to the input data
 */
class Context
{
    const SEPERATOR   = '/';
    const WILDCARD       = '*';
    const RELATIVE    = '.';
    const PARENT      = '..';
    const ALL_TARGETS = 'VXML_Context::ALL_ARGS';
    
    /**
     * Input data for rules to be applied to
     * 
     * @var array
     */
    private $_data = array();
    
    /**
     * Target(s) currently being processed
     * 
     * @var array
     */
    private $_resolvedTargets = array();
    
    /**
     * Stack for saving/restoring targets being processed
     * 
     * @var array
     */
    private $_resolvedTargetsStack = array();
    
    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->_data = $data;
    }
    
    /**
     * Save current target on stack
     * 
     * @return void
     */
    public function save()
    {
        $this->_resolvedTargetsStack[] = $this->_resolvedTargets;
    }
    
    /**
     * Restore last saved target from stack
     * 
     * @return void
     */
    public function restore()
    {
        $this->_resolvedTargets = array_pop($this->_resolvedTargetsStack);
    }
    
    /**
     * Update current target relative to the previous one
     * 
     * @todo rename to setTarget() target doesnt have to be relative
     * @param mixed $targets
     * @return void
     */
    public function setRelativeTarget($targets)
    {
        if (is_scalar($targets)) {
            $targets = array($targets);
        }
        
        if (! is_array($targets)) {
            throw new \InvalidArgumentException('target must be a scalar or an array');
        }
        
        $resolvedTargets = array();
        foreach ($targets as $target) {
            if ($this->isAbsolute($target)) {
                $resolvedTargets[] = self::RELATIVE . $target;
            } else if (count($this->_resolvedTargets)) {
                foreach($this->_resolvedTargets as $oTarget) {
                    $resolvedTargets[] = $oTarget . self::SEPERATOR . $target;
                }
            } else {
                $resolvedTargets[] = $target;
            }
        }
        
        $this->_resolvedTargets = $resolvedTargets;
    }
    
    /**
     * Get current target(s)
     * 
     * @todo rename to getResolvedTargets() always returns an array
     * @return array of one or more targets
     */
    public function getResolvedTarget()
    {
        return $this->_resolvedTargets;
    }
    
    /**
     * Get value from context based on current target
     */
    public function getPassedValue()
    {
        if (count($this->_resolvedTargets) > 1) {
            throw new \InvalidArgumentException(
                'rule only expects 1 target, multiple were given: ' . count($this->_resolvedTargets)
            );
        }
        
        return $this->getValueFromTarget(current($this->_resolvedTargets));
    }
    
    /**
     * Get array of values from context based on current target, validated against 
     * the proto passed as first parameter 
     * 
     * @throws InvalidArgumentException if target does not match proto
     * @param array $proto map values to proto
     * @return array of values matched by target and proto
     */
    public function getPassedValues($proto)
    {
        if ($proto == self::ALL_TARGETS) {
            $proto = $this->_resolvedTargets;
        }
        
        if (count($this->_resolvedTargets) != count($proto))
            throw new \InvalidArgumentException('number of targets mismatch');
        
        $values = array();
        foreach ($proto as $key => $name) {
            $values[$name] = $this->getValueFromTarget($this->_resolvedTargets[$key]);
        }
        
        return $values;
    }
    
    /**
     * Test if a specific target is absolute (e.g. prefixed by /)
     * 
     * @param string $target
     * @return boolean
     */
    private function isAbsolute($target)
    {
        if (! is_scalar($target)) {
            throw new \InvalidArgumentException('can only test scalars for absoluteness');
        }
        
        return (substr($target, 0, 1) == self::SEPERATOR);
    }
    
    /**
     * Parse through data to find a specific targets value
     * 
     * @param string $target
     * @return array
     */
    private function getValueFromTarget($target)
    {
        $data = $this->_data;
        $pieces = explode(self::SEPERATOR, $target);
        
        foreach ($pieces as $piece) {
            switch ($piece) {
                case self::RELATIVE:
                    continue;
                default:
                    $data = (isset($data[$piece]) ? $data[$piece] : null);     
            }
        }
        
        return $data;
    }
}