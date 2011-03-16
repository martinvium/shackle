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

use VXML\Context;
use VXML\Response;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context
     */
    protected $context;
    
    /**
     * @var Response
     */
    protected $response;
    
    /**
     * @var array
     */
    protected $mockData;
    
    public function setUp()
    {
        $this->mockData = array(
            'firstname'         => 'Jørgen',
            'lastname'            => 'Andersen',
            'email'                => 'jorgen@example.com',
            'salary'            => 25000,
            'empty'                => '',
            'birthdate_year'     => 1984,
            'birthdate_month'    => 05,
            'birthdate_day'        => 23,
            'friends'            => array(
                'mock-a@example.com',
                'mock-b@example.dk'),
            'blocked'            => array(
                'mock-cexample.net',
                'mock-d@example.se')
        );
        
        $this->context = new Context($this->mockData);
        $this->response = new Response();
    }
    
    public function tearDown()
    {
        $this->mockData = null;
        $this->context = null;
        $this->response = null;
    }
}