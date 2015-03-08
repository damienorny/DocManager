<?php

namespace DocManager\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DocManagerUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
