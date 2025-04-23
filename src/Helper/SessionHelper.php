<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionHelper
{
    public function __construct(private RequestStack $requestStack) {}

    public function getId(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        $request->getSession()->start();
        return $request?->getSession()?->getId();
    }
}
