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
namespace Shackle\Rule;

use Shackle\Rule;

abstract class DecoratorAbstract extends RuleAbstract
{
    /**
     * @var Rule
     */
    protected $rule;
    
    /**
     * @param Rule $rule
     */
    public function __construct(Rule $rule, $options = array())
    {
        $this->rule = $rule;
        
        parent::__construct('.', $options);
    }
    
    public function add($rule)
    {
        return $this->rule->add($rule);
    }
    
    public function addListener($type, $rule)
    {
        return $this->rule->addListener($type, $rule);
    }
    
    public function getMessage()
    {
        return $this->rule->getMessage();
    }
}