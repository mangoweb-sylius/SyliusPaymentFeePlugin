<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model;

use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Taxation\Model\TaxableInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

interface PaymentMethodWithFeeInterface extends PaymentMethodInterface, TaxableInterface
{
    public function getCalculator(): ?string;

    public function getCalculatorConfiguration(): array;

    public function setTaxCategory(?TaxCategoryInterface $category): void;
}
