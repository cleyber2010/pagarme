<?php

namespace Src;

class payments
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}