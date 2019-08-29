<?php

namespace Snowdog\DevTest\Controller;

class ForbiddenAction
{
    public function execute()
    {
        require __DIR__ . '/../view/403.phtml';
    }
}