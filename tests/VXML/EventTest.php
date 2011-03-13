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

class EventTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var VXML\Event
     */
    private $_event;
    
    /**
     * @var VXML\Rule\RuleAbstract
     */
    private $_rule;
    
    /**
     * @var VXML\Context
     */
    private $_context;
    
    /**
     * @var VXML\Response
     */
    private $_response;
    
    protected function setUp() 
    {
        parent::setUp ();
        
        $this->_rule = new Rule\Equal('field_a', array('equal' => 'test'));
        $this->_context = new Context(array('field_a' => 'test'));
        $this->_response = new Response();
        
        $this->_event = new Event($this->_rule, $this->_context, $this->_response);
    }
    
    protected function tearDown() 
    {
        $this->_event = null;
        parent::tearDown ();
    }
    
    public function testGetRule()
    {
        $this->assertInstanceOf('VXML\Rule\RuleAbstract', $this->_event->getRule());
    }
    
    public function testGetContext()
    {
        $this->assertInstanceOf('VXML\Context', $this->_event->getContext());
    }
    
    public function testGetResponse()
    {
        $this->assertInstanceOf('VXML\Response', $this->_event->getResponse());
    }
}