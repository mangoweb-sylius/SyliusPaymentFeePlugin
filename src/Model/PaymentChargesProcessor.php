<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model;

use MangoSylius\PaymentFeePlugin\Model\Calculator\DelegatingCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Model\AdjustmentInterface as BaseAdjustmentInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class PaymentChargesProcessor implements OrderProcessorInterface
{
	/**
	 * @var FactoryInterface
	 */
	private $adjustmentFactory;

	/**
	 * @var DelegatingCalculatorInterface
	 */
	private $paymentChargesCalculator;

	/**
	 * @param FactoryInterface $adjustmentFactory
	 * @param DelegatingCalculatorInterface $paymentChargesCalculator
	 */
	public function __construct(
		FactoryInterface $adjustmentFactory,
		DelegatingCalculatorInterface $paymentChargesCalculator
	) {
		$this->adjustmentFactory = $adjustmentFactory;
		$this->paymentChargesCalculator = $paymentChargesCalculator;
	}

	public function process(BaseOrderInterface $order): void
	{
		assert($order instanceof OrderInterface);

		$order->removeAdjustments(AdjustmentInterface::PAYMENT_ADJUSTMENT);

		foreach ($order->getPayments() as $payment) {
			$paymentCharge = $this->paymentChargesCalculator->calculate($payment);

			if ($paymentCharge === null) {
				continue;
			}

			$adjustment = $this->adjustmentFactory->createNew();
			assert($adjustment instanceof BaseAdjustmentInterface);

			$adjustment->setType(AdjustmentInterface::PAYMENT_ADJUSTMENT);
			$adjustment->setAmount($paymentCharge);
			$adjustment->setLabel($payment->getMethod() !== null ? $payment->getMethod()->getName() : null);
			$adjustment->setOriginCode($payment->getMethod() !== null ? $payment->getMethod()->getCode() : null);
			$adjustment->setNeutral(false);

			$order->addAdjustment($adjustment);
		}
	}
}
