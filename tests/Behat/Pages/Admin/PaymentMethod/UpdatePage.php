<?php

declare(strict_types=1);

namespace Tests\MangoSylius\PaymentFeePlugin\Behat\Pages\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Channel\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{

    public function changeExtraFee(string $extraFeePrice): void
    {
        $this->getElement('extra_fee')->setValue($extraFeePrice);
    }

    public function getExtraFee()
    {
        return $this->getElement('extra_fee')->getValue();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'extra_fee' => '.calculatorConfiguration input',
        ]);
    }
}
