<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Form\Type\Calculator;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class FlatRateConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'mango-sylius.form.payment_calculator.flat_rate_configuration.amount',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'integer', 'groups' => ['sylius']]),
                ],
                'currency' => $options['currency'],
            ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
            ]
            )
            ->setRequired('currency')
            ->setAllowedTypes('currency', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'sylius_shipping_calculator_flat_rate';
    }
}
