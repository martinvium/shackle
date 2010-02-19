<?php
require_once 'PHPUnit\Framework\TestCase.php';

abstract class VXML_Rule_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @var VXML\Context
	 */
	protected $context;
	
	/**
	 * @var VXML\Response
	 */
	protected $response;
	
	/**
	 * @var array
	 */
	protected $mockData;
	
	public function setUp()
	{
		$this->mockData = array(
			'firstname' 		=> 'Jørgen',
			'lastname'			=> 'Andersen',
			'salary'			=> 25000,
			'empty'				=> '',
			'birthdate_year' 	=> 1984,
			'birthdate_month'	=> 05,
			'birthdate_day'		=> 23
		);
		
		$this->context = new VXML\Context($this->mockData);
		$this->response = new VXML\Response();
	}
	
	public function tearDown()
	{
		$this->mockData = null;
		$this->context = null;
		$this->response = null;
	}
}