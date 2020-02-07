<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model;

use Sylius\Component\Core\Model\AdjustmentInterface as SyliusAdjustmentInterface;

interface AdjustmentInterface extends SyliusAdjustmentInterface
{
    public const PAYMENT_ADJUSTMENT = 'payment';
}
