<?php

declare(strict_types=1);

namespace DMP\LoggableBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Loggable
{

}
