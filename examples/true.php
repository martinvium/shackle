<?php
use Shackle\Rule;

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');

require_once 'Shackle/Loader.php';
Shackle\Loader::registerAutoload();

$data = array(
    'ekstra1' => 153,
    'ekstra2' => 0,
    'ekstra3' => false,
    'ekstra4' => array(1, 2, 3),
    'ekstra5' => array('a' => 123, 'b' => 432),
    'b_year'  => 1984,
    'b_month' => 10,
    'b_day'   => 10,
    'email'   => 'test43@test.dk',
    'ekstra7' => 'Ja',
    'stringlength' => 'fesfest43ttdesf',
    'stringlength2' => 'fesfest43ttdesf',
    'whitelist' => 'test_b'
);

$data['ekstra6'][] = array('email' => 'test1@test.dk', 'firstname' => 'bla1');
$data['ekstra6'][] = array('email' => 'test2@test.dk', 'firstname' => 'bla2');

// execute
$response = new Shackle\Response();
$import = new Shackle\Rule\Import('.', array('path' => 'validators.php'));
$ret = $import->execute(new Shackle\Context($data), $response);

echo '<table border="1" cellpadding="3">';
foreach($response->getAllMessages() as $msg)
{
    echo '<tr style="background-color: ' . get_color_from_type($msg['type']) . '">';
    echo '<td>' . write_target($msg['target']) . '</td>';
    echo '<td>' . get_class($msg['rule']) . '</td>';
    echo '<td>' . $msg['message'] . '</td>';
    echo '<td>' . $msg['debug'] . '</td>';
    echo '</tr>';
}
echo '<tr><td colspan="3">Result</td><td style="background-color: ' . ($ret ? '#008000' : '#FF2020') . '">' . ($ret ? 'TRUE' : 'FALSE') . '</td></tr>';
echo '</table>';

function get_color_from_type($type)
{
    return ($type == \Shackle\Response::MSG_SUCCESS ? '#008000' : ($type == \Shackle\Response::MSG_DEBUG ? '#DFDFDF' : '#FF2020'));
}

function write_target($target)
{
    if(is_array($target))
    {
        return implode(',', $target);
    }
    else 
    {
        return $target;
    }
}

/**
 * @param Shackle\Event $event
 */
function cb_rule_test($event)
{
    return true;
}