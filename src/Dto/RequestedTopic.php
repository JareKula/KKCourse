<?php
declare(strict_types=1);

namespace Jarek\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RequestedTopic
{
    public function __construct(
        #[Assert\Type(type: 'string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public readonly mixed $name,

        #[Assert\Type(type: 'integer')]
        #[Assert\Positive]
        public readonly mixed $weight,
    ) {
    }
}