<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Calculator;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;

final class FlatRateCalculator implements CalculatorInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Sylius\Component\Core\Exception\MissingChannelConfigurationException
     */
    public function calculate(BasePaymentInterface $subject, array $configuration): ?int
    {
        assert($subject instanceof PaymentInterface);

        $order = $subject->getOrder();
        assert($order instanceof OrderInterface);

        if ($order->getChannel() === null) {
            throw new \ErrorException('$order->getChannel() cannot by NULL');
        }

        $channelCode = $order->getChannel()->getCode();

        if (!isset($configuration[$channelCode])) {
            throw new MissingChannelConfigurationException(sprintf(
                    'Channel %s has no amount defined for shipping method %s',
                    $order->getChannel()->getName(),
                    $subject->getMethod() !== null ? $subject->getMethod()->getName() : 'null'
                )
            );
        }

        return (int) $configuration[$channelCode]['amount'];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'flat_rate';
    }
}
