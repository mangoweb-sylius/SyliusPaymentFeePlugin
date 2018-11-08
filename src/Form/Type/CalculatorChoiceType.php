<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CalculatorChoiceType extends AbstractType
{
	/**
	 * @var array
	 */
	private $calculators;

	/**
	 * @param array $calculators
	 */
	public function __construct(array $calculators)
	{
		$this->calculators = $calculators;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'choices' => array_flip($this->calculators),
			])
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return ChoiceType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'sylius_payment_calculator_choice';
	}
}
