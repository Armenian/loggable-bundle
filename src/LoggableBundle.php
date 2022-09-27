<?php

declare(strict_types=1);

namespace DMP\LoggableBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use DMP\LoggableBundle\DependencyInjection\LoggableExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class LoggableBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        AnnotationReader::addGlobalIgnoredName('suppress');
        return LoggableExtension::class;
    }
}
