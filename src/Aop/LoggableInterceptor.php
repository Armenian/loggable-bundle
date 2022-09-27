<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Psr\Log\LoggerInterface;
use Throwable;

class LoggableInterceptor implements MethodInterceptorInterface
{

    public function __construct(private readonly LoggerInterface $logger)
    {}

    /**
     * @param MethodInvocation $invocation
     * @return mixed
     * @throws Throwable
     */
    public function intercept(MethodInvocation $invocation): mixed
    {
        $this->logger->debug("'$invocation' was invoked", [
            'arguments' => $invocation->arguments,
        ]);
        try {
            $return = $invocation->proceed();

            $this->logger->debug("'$invocation' has finished executing", [
                'returnValue' => $return,
            ]);

            return $return;
        } catch (Throwable $throwable) {
            $this->logger->warning("'$invocation' has failed to execute", [
                'throwable' => $throwable,
            ]);

            throw $throwable;
        }
    }
}
