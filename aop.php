<?php

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;


use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

/**
 * Monitor aspect
 */
class MonitorAspect implements Aspect
{

    /**
     * Method that will be called before real method
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("execution(public Shopware\Bundle\SearchBundleDBAL\ProductNumberSearch->*(*))")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
        error_log('Calling Before Interceptor for method: ' . $invocation->getMethod()->getName() . '()');
    }
}

/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends \Go\Core\AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new MonitorAspect());
    }
}

// Initialize an application aspect container
$applicationAspectKernel = ApplicationAspectKernel::getInstance();
$applicationAspectKernel->init(array(
        'debug' => true, // use 'false' for production mode
        // Cache directory
        'cacheDir' => __DIR__ . '/aopcache',
        'appDir' => __DIR__ . '/engine/',
        // Include paths restricts the directories where aspects should be applied, or empty for all source files
        'includePaths' => array(
            __DIR__ . '/engine/'
        ),
        'excludePaths' => array(
            __DIR__ . '/engine/Library'
        )
    )
);