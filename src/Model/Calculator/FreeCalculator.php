<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Calculator;

use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;

final class FreeCalculator implements CalculatorInterface
{
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Sylius\Component\Core\Exception\MissingChannelConfigurationException
	 */
	public function calculate(BasePaymentInterface $subject, array $configuration): ?int
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType(): string
	{
		return 'free';
	}
}
