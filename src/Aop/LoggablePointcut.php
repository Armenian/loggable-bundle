<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Aop;

use DMP\LoggableBundle\Annotation\Loggable;
use DMP\AopBundle\Aop\PointcutInterface;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionMethod;

class LoggablePointcut implements PointcutInterface
{

    public function __construct(private readonly Reader $reader)
    {}

    public function matchesClass(ReflectionClass $class): bool
    {
        return true;
    }

    public function matchesMethod(ReflectionMethod $method): bool
    {
        return null !== $this->reader->getMethodAnnotation($method, Loggable::class);
    }
}
