<?php
namespace VXML;

use VXML\Context;
use VXML\Response;

interface Rule
{
    public function execute(Context $context, Response $response);
}