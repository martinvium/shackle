<?php
use Shackle\Rule;

$set = Rule\Import::getInstance();
$set3 = $set->add(new Rule\Iterator(new Rule\Set('.', array('min' => 1))));
$set3->add(new Rule\Equal('email', array('equal' => 'test2@test.dk', 'message' => 'ekstra6 failed (email is invalid)')));
$set3->add(new Rule\Equal('firstname', array('equal' => 'bla2', 'message' => 'ekstra6 failed (firstname is invalid)')));
$set3->add(new Rule\Equal('/ekstra1', array('equal' => 153, 'message' => 'ekstra6 failed (absolute test)')));
