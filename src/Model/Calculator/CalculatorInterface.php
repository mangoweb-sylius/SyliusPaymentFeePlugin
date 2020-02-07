<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Calculator;

use Sylius\Component\Payment\Model\PaymentInterface;

interface CalculatorInterface
{
    public function calculate(PaymentInterface $subject, array $configuration): ?int;

    public function getType(): string;
}
