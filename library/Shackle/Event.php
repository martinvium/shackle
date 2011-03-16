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
namespace Shackle;

/**
 * Event is passed to rules and callbacks, when they are executed. It
 * contains the parent rule, current context updated with the rules
 * target and a response object, on which to add messages.
 */
final class Event
{
    /**
     * @var Shackle\Rule\RuleAbstract
     */
    private $_rule;
    
    /**
     * @var Shackle\Context
     */
    private $_context;
    
    /**
     * @var Shackle\Response
     */
    private $_response;
    
    /**
     * @param Rule\RuleAbstract $rule
     * @param Context $context
     * @param Response $response
     */
    public function __construct(Rule\RuleAbstract $rule, Context $context, Response $response)
    {
        $this->_rule = $rule;
        $this->_context = $context;
        $this->_response = $response;
    }
    
    /**
     * @return Shackle\Rule\RuleAbstract
     */
    public function getRule()
    {
        return $this->_rule;
    }
    
    /**
     * @return Shackle\Context
     */
    public function getContext()
    {
        return $this->_context;
    }
    
    /**
     * @return Shackle\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }
}