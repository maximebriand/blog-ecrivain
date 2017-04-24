<?php

namespace DMB\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;


class DMBUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
