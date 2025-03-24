<?php

namespace Jarek\DatabaseAbstractionLayer;

use Jarek\DatabaseAbstractionLayer\Entity\ProviderCollection;

interface ProviderRepositoryInterface
{
    public function getProviderData(): ProviderCollection;
}