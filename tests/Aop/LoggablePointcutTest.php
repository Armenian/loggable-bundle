<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Tests\Aop;

use DMP\LoggableBundle\Annotation\Loggable;
use DMP\LoggableBundle\Aop\LoggablePointcut;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class LoggablePointcutTest extends TestCase
{

    private LoggablePointcut $pointcut;
    private MockObject|Reader $reader;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(Reader::class);
        $this->pointcut = new LoggablePointcut($this->reader);
    }

    public function testMatchesClass(): void
    {
        $reflectionClass = $this->createMock(ReflectionClass::class);
        $this->assertTrue($this->pointcut->matchesClass($reflectionClass));
    }

    public function testMatchesMethodTrue(): void
    {
        $annotation = $this->createMock(Loggable::class);
        $reflectionMethod = $this->createMock(ReflectionMethod::class);
        $this->reader
            ->method('getMethodAnnotation')
            ->with($reflectionMethod, Loggable::class)
            ->willReturn($annotation);

        $this->assertTrue($this->pointcut->matchesMethod($reflectionMethod));
    }

    public function testMatchesFalse(): void
    {
        $reflectionMethod = $this->createMock(ReflectionMethod::class);
        $this->reader
            ->method('getMethodAnnotation')
            ->with($reflectionMethod, Loggable::class)
            ->willReturn(null);

        $this->assertFalse($this->pointcut->matchesMethod($reflectionMethod));
    }


}
