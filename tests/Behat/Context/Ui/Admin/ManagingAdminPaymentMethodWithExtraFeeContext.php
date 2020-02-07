<?php

declare(strict_types=1);

namespace Tests\MangoSylius\PaymentFeePlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\MangoSylius\PaymentFeePlugin\Behat\Pages\Admin\PaymentMethod\UpdatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingAdminPaymentMethodWithExtraFeeContext implements Context
{
	/** @var UpdatePageInterface */
	private $updatePage;

	public function __construct(
		UpdatePageInterface $updatePage
	) {
		$this->updatePage = $updatePage;
	}

	/**
	 * @When I add an extra fee of :arg1$
	 */
	public function iAddAnExtraFeeOf($arg1)
	{
		$this->updatePage->changeExtraFee($arg1);
	}

	/**
	 * @Then this payment method have an extra fee of :arg1$
	 */
	public function thisPaymentMethodHaveAExtraFeeOnIt($arg1)
	{
		Assert::eq($this->updatePage->getExtraFee(), $arg1);
	}
}
