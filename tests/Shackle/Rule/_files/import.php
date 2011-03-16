<?php
use Shackle\Rule;

$set = Rule\Import::getInstance();
$set->add(new Rule\Equal('firstname', array('equal' => 'Jørgen')));