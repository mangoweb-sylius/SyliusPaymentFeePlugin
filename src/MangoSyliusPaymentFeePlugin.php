<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin;

use MangoSylius\PaymentFeePlugin\DependencyInjection\Compiler\TaxApplicatorCompilerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MangoSyliusPaymentFeePlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterFeeCalculatorsPass());
        $container->addCompilerPass(new TaxApplicatorCompilerPass());
    }
}
