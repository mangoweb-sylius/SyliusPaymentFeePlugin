<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model\Calculator;

use Sylius\Component\Payment\Model\PaymentInterface;

interface DelegatingCalculatorInterface
{
	public function calculate(PaymentInterface $subject): ?int;
}
