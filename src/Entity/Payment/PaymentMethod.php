<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Entity\Payment;

use Doctrine\ORM\Mapping as ORM;
use MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeInterface;
use MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeTrait;
use Sylius\Component\Core\Model\PaymentMethod as BasePaymentMethod;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_payment_method")
 */
class PaymentMethod extends BasePaymentMethod implements PaymentMethodWithFeeInterface
{
    use PaymentMethodWithFeeTrait;
}
