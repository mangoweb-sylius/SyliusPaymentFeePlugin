<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Taxation\Applicator;

use MangoSylius\PaymentFeePlugin\Model\AdjustmentInterface;
use MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeInterface;
use Payum\Core\Model\PaymentInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Webmozart\Assert\Assert;

class OrderPaymentTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /** @var CalculatorInterface */
    private $calculator;

    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    /** @var TaxRateResolverInterface */
    private $taxRateResolver;

    public function __construct(
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        TaxRateResolverInterface $taxRateResolver
    ) {
        $this->calculator = $calculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->taxRateResolver = $taxRateResolver;
    }

    private function getPaymentFee(OrderInterface $order): int
    {
        $paymentFees = $order->getAdjustmentsRecursively(AdjustmentInterface::PAYMENT_ADJUSTMENT);
        if (!$paymentFees->count()) {
            return 0;
        }

        $paymentFee = $paymentFees->first();

        return $paymentFee->getAmount();
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        $paymentTotal = $this->getPaymentFee($order);

        if (0 === $paymentTotal) {
            return;
        }

        $paymentMethod = $this->getPaymentMethod($order);
        if ($paymentMethod === null) {
            return;
        }

        $taxRate = $this->taxRateResolver->resolve($paymentMethod, ['zone' => $zone]);
        if (null === $taxRate) {
            return;
        }

        $taxAmount = $this->calculator->calculate($paymentTotal, $taxRate);
        if (0.00 === $taxAmount) {
            return;
        }

        $label = $taxRate->getLabel() ?? 'payment tax';
        $this->addAdjustment($order, (int) $taxAmount, $label, $taxRate->isIncludedInPrice());
    }

    private function addAdjustment(OrderInterface $order, int $taxAmount, string $label, bool $included): void
    {
        /** @var AdjustmentInterface $paymentTaxAdjustment */
        $paymentTaxAdjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, $label, $taxAmount, $included);
        $order->addAdjustment($paymentTaxAdjustment);
    }

    private function getPaymentMethod(OrderInterface $order): ?PaymentMethodWithFeeInterface
    {
        /** @var PaymentInterface|bool $shipment */
        $payment = $order->getPayments()->first();
        if (false === $payment) {
            return null;
        }

        $method = $payment->getMethod();

        /** @var PaymentMethodWithFeeInterface $method */
        Assert::isInstanceOf($method, PaymentMethodWithFeeInterface::class);

        return $method;
    }
}
