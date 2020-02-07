<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\DependencyInjection\Compiler;

use MangoSylius\PaymentFeePlugin\Model\Taxation\Applicator\OrderPaymentTaxesApplicator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaxApplicatorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerToOrderItemsBasedStrategy($container);
        $this->registerToOrderItemUnitsBasedStrategy($container);
    }

    private function registerToOrderItemsBasedStrategy(
        ContainerBuilder $container
    ): void {
        $definition = $container->getDefinition(
            'sylius.taxation.order_items_based_strategy'
        );
        $arg = $definition->getArgument(1);
        $arg[] = new Reference(OrderPaymentTaxesApplicator::class);
        $definition->setArgument(1, $arg);
    }

    private function registerToOrderItemUnitsBasedStrategy(
        ContainerBuilder $container
    ): void {
        $definition = $container->getDefinition(
            'sylius.taxation.order_item_units_based_strategy'
        );
        $arg = $definition->getArgument(1);
        $arg[] = new Reference(OrderPaymentTaxesApplicator::class);
        $definition->setArgument(1, $arg);
    }
}
