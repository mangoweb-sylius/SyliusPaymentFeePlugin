<?php

declare(strict_types=1);

namespace Tests\MangoSylius\PaymentFeePlugin\Behat\Pages\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Channel\UpdatePageInterface as BaseUpdatePageInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface
{
	public function changeExtraFee(string $extraFeePrice): void;

	public function getExtraFee();
}
