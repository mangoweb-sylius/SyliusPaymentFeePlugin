<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Calculator;

use MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingCalculator implements DelegatingCalculatorInterface
{
	/**
	 * @var ServiceRegistryInterface
	 */
	private $registry;

	public function __construct(ServiceRegistryInterface $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function calculate(PaymentInterface $subject): ?int
	{
		$method = $subject->getMethod();
		if ($method === null) {
			throw new UndefinedPaymentMethodException('Cannot calculate charge for payment without a defined payment method.');
		}

		if (!($method instanceof PaymentMethodWithFeeInterface)) {
			return 0;
		}
		if ($method->getCalculator() === null) {
			return 0;
		}

		$calculator = $this->registry->get($method->getCalculator());
		assert($calculator instanceof CalculatorInterface);

		return $calculator->calculate($subject, $method->getCalculatorConfiguration());
	}
}
