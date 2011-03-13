<?php
namespace VXML\Rule;

use VXML\Response;
use VXML\Event;

final class Invert extends DecoratorAbstract
{
    /**
     * @todo Re-adding rules like this will screw up targets
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $mock_response = new Response();
        if(! $this->rule->execute($event->getContext(), $mock_response))
        {
            foreach($mock_response->getFailureMessages() as $failure)
            {
                $mock_response->addSuccess($failure['rule'], $failure['debug']);
            }
            return true;
        }
        else
        {
            foreach($mock_response->getSuccessMessages() as $success)
            {
                $event->getResponse()->addFailure($success['rule'], $success['debug']);
            }
            return false;
        }
    }
}