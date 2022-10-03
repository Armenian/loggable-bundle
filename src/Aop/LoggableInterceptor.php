<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class LoggableInterceptor implements MethodInterceptorInterface
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer)
    {}

    /**
     * @param MethodInvocation $invocation
     * @return mixed
     * @throws Throwable
     */
    public function intercept(MethodInvocation $invocation): mixed
    {

        $this->logger->debug(sprintf('%s was invoked', $invocation), [
            'arguments' => $this->serializer->serialize($invocation->arguments, 'json'),
        ]);
        try {
            $return = $invocation->proceed();

            $this->logger->debug(sprintf('%s has finished executing', $invocation), [
                'returnValue' => $this->serializer->serialize($return, 'json'),
            ]);
            return $return;
        } catch (Throwable $throwable) {
            $this->logger->debug(sprintf('%s has failed to execute', $invocation), [
                'throwable' => $throwable,
            ]);
            throw $throwable;
        }
    }
}
