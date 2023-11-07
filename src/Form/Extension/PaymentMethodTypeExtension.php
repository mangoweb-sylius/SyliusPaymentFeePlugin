<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Form\Extension;

use MangoSylius\PaymentFeePlugin\Form\Type\CalculatorChoiceType;
use MangoSylius\PaymentFeePlugin\Model\Calculator\CalculatorInterface;
use MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeInterface;
use Sylius\Bundle\PaymentBundle\Form\Type\PaymentMethodType as SyliusPaymentMethodType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class PaymentMethodTypeExtension extends AbstractTypeExtension
{
	/**
	 * @var ServiceRegistryInterface
	 */
	private $calculatorRegistry;

	/**
	 * @var FormTypeRegistryInterface
	 */
	private $formTypeRegistry;

	public function __construct(
		ServiceRegistryInterface $calculatorRegistry,
		FormTypeRegistryInterface $formTypeRegistry
	) {
		$this->calculatorRegistry = $calculatorRegistry;
		$this->formTypeRegistry = $formTypeRegistry;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->addEventSubscriber(new AddCodeFormSubscriber())
			->add('taxCategory', TaxCategoryChoiceType::class)
			->add('calculator', CalculatorChoiceType::class, [
				'label' => 'mango-sylius.form.payment_method.calculator',
			]
			)->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
				$method = $event->getData();

				if ($method === null || $method->getId() === null) {
					return;
				}

				if ($method instanceof PaymentMethodWithFeeInterface && $method->getCalculator() !== null) {
					$this->addConfigurationField($event->getForm(), $method->getCalculator());
				}
			}
			)
			->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
				$data = $event->getData();

				if (!is_array($data) || empty($data) || !array_key_exists('calculator', $data)) {
					return;
				}

				$this->addConfigurationField($event->getForm(), $data['calculator']);
			}
			);

		$prototypes = [];
		foreach ($this->calculatorRegistry->all() as $name => $calculator) {
			assert($calculator instanceof CalculatorInterface);
			$calculatorType = $calculator->getType();

			if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
				continue;
			}

			$form = $builder->create('calculatorConfiguration', $this->formTypeRegistry->get($calculatorType, 'default'));

			$prototypes['calculators'][$name] = $form->getForm();
		}

		$builder->setAttribute('prototypes', $prototypes);
	}

	private function addConfigurationField(FormInterface $form, string $calculatorName): void
	{
		$calculator = $this->calculatorRegistry->get($calculatorName);
		assert($calculator instanceof CalculatorInterface);

		$calculatorType = $calculator->getType();
		if (!$this->formTypeRegistry->has($calculatorType, 'default')) {
			return;
		}

		$form->add('calculatorConfiguration', $this->formTypeRegistry->get($calculatorType, 'default'));
	}

	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['prototypes'] = [];
		foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
			foreach ($prototypes as $type => $prototype) {
				$view->vars['prototypes'][$group . '_' . $type] = $prototype->createView($view);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getExtendedTypes(): iterable
	{
		return [SyliusPaymentMethodType::class];
	}
}
