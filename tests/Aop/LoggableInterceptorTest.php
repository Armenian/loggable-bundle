<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Tests\Aop;

use CG\Proxy\MethodInvocation;
use DMP\LoggableBundle\Aop\LoggableInterceptor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;
use RuntimeException;

class LoggableInterceptorTest extends TestCase
{
    private const RETURN_VALUE = 42;
    private const INVOCATION_NAME = 'Foo::bar';
    private const TEST_EXCEPTION = 'Test exception';
    private const ARGUMENTS = [
        'foo' => 'bar',
    ];

    private LoggableInterceptor $interceptor;
    private MockObject|LoggerInterface $logger;
    private MockObject|SerializerInterface $serializer;
    private MockObject|MethodInvocation $invocation;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->serializer->method('serialize')->willReturn('a:1:{s:9:"arguments";a:1:{s:3:"foo";s:3:"bar";}}');
        $this->invocation = $this->createMock(MethodInvocation::class);
        $this->invocation->method('__toString')->willReturn(self::INVOCATION_NAME);
        $this->invocation->arguments = self::ARGUMENTS;

        $this->interceptor = new LoggableInterceptor($this->logger, $this->serializer);
    }

    /**
     * @throws Throwable
     */
    public function testInterceptShouldLogSuccess(): void
    {
        $this->invocation->expects(self::once())
            ->method('proceed')
            ->willReturn(self::RETURN_VALUE);

        $this->logger->expects(self::exactly(2))->method('debug');

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }

    /**
     * @throws Throwable
     */
    public function testInterceptShouldLogFailure(): void
    {
        $exception = new RuntimeException(self::TEST_EXCEPTION);
        $this->expectExceptionObject($exception);
        $this->invocation->expects(self::once())
            ->method('proceed')
            ->willThrowException($exception);

        $this->logger->expects(self::exactly(2))->method('debug');

        $this->assertSame(self::RETURN_VALUE, $this->interceptor->intercept($this->invocation));
    }
}
