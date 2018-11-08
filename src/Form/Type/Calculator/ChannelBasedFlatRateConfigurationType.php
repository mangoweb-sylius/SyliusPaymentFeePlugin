<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Form\Type\Calculator;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChannelBasedFlatRateConfigurationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
				'entry_type' => FlatRateConfigurationType::class,
				'entry_options' => function (ChannelInterface $channel): array {
					if ($channel->getBaseCurrency() === null) {
						throw new \ErrorException('$channel->getBaseCurrency() cannot by NULL');
					}

					return [
						'label' => $channel->getName(),
						'currency' => $channel->getBaseCurrency()->getCode(),
					];
				},
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return ChannelCollectionType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'mango-sylius_channel_based_payment_calculator_flat_rate';
	}
}
