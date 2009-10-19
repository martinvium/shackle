<?php
use VXML\Rule;

// decorators
require_once 'VXML/Rule/Iterate.php';

// rules
require_once 'VXML/Rule/Equals.php';
require_once 'VXML/Rule/Set.php';

$set = Rule\Import::getInstance();
$set3 = $set->add(new Rule\Iterate(new Rule\Set('.', array('min' => 1))));
$set3->add(new Rule\Equals('email', array('equals' => 'test2@test.dk', 'message' => 'ekstra6 failed (email is invalid)')));
$set3->add(new Rule\Equals('firstname', array('equals' => 'bla2', 'message' => 'ekstra6 failed (firstname is invalid)')));
$set3->add(new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'ekstra6 failed (absolute test)')));
