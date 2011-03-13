<?php
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