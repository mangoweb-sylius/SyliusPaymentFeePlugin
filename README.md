<p align="center">
    <a href="https://www.mangoweb.cz/en/" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/38423357?s=200&v=4"/>
    </a>
</p>
<h1 align="center">Payment Fee Plugin</h1>

## Features

* Charge extra fee for using payment method.
* Typical usage: Cash on Delivery.
* Taxes are implemented the same way as taxes for shipping fees.

<p align="center">
	<img src="https://raw.githubusercontent.com/mangoweb-sylius/SyliusPaymentFeePlugin/master/doc/admin.png"/>
</p>

## Installation

1. Run `$ composer require mangoweb-sylius/sylius-payment-fee-plugin`.
2. Register `\MangoSylius\PaymentFeePlugin\MangoSyliusPaymentFeePlugin` in your Kernel.
3. Your Entity `PaymentMethod` has to implement `\MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeInterface`. You can use Trait `MangoSylius\PaymentFeePlugin\Model\PaymentMethodWithFeeTrait`. 

For guide how to use your own entity see [Sylius docs - Customizing Models](https://docs.sylius.com/en/1.3/customization/model.html)

### Admin

1. Add this to `@SyliusAdmin/PaymentMethod/_form.html.twig` template.

```twig

<div class="ui segment">
	<h4 class="ui dividing header">{{ 'mango-sylius.ui.payment_charges'|trans }}</h4>
	{{ form_row(form.calculator) }}
	{% for name, calculatorConfigurationPrototype in form.vars.prototypes %}
		<div id="{{ form.calculator.vars.id }}_{{ name }}" data-container=".calculatorConfiguration"
			 data-prototype="{{ form_widget(calculatorConfigurationPrototype)|e }}">
		</div>
	{% endfor %}
	<div class="ui segment calculatorConfiguration">
		{% if form.calculatorConfiguration is defined %}
			{% for field in form.calculatorConfiguration %}
				{{ form_row(field) }}
			{% endfor %}
		{% endif %}
	</div>
</div>
```

2. Add this to `AdminBundle/Resources/views/Order/Show/Summary/_totals.html.twig`.

```twig

{% set paymentFeeAdjustment = constant('MangoSylius\\PaymentFeePlugin\\Model\\AdjustmentInterface::PAYMENT_ADJUSTMENT') %}

{% set paymentFeeAdjustments = order.getAdjustmentsRecursively(paymentFeeAdjustment) %}
{% if paymentFeeAdjustments is not empty %}
	<tr>
		<td colspan="4" id="payment-fee">

			<div class="ui relaxed divided list">
				{% for paymentFeeLabel, paymentFeeAmount in sylius_aggregate_adjustments(paymentFeeAdjustments) %}
					<div class="item">
						<div class="content">
							<span class="header">{{ paymentFeeLabel }}</span>
							<div class="description">
								{{ money.format(paymentFeeAmount, order.currencyCode) }}
							</div>
						</div>
					</div>
				{% endfor %}
			</div>

		</td>
		<td colspan="4" id="paymentFee-total" class="right aligned">
			<strong>{{ 'mango-sylius.ui.paymentFee_total'|trans }}</strong>:
			{{ money.format(order.getAdjustmentsTotal(paymentFeeAdjustment) ,order.currencyCode) }}
		</td>
	</tr>
{% endif %}
```

## Development

### Usage

- Create symlink from .env.dist to .env or create your own .env file
- Develop your plugin in `/src`
- See `bin/` for useful commands

### Testing

After your changes you must ensure that the tests are still passing.
* Easy Coding Standard
  ```bash
  bin/ecs.sh
  ```
* PHPStan
  ```bash
  bin/phpstan.sh
  ```
License
-------
This library is under the MIT license.

Credits
-------
Developed by [manGoweb](https://www.mangoweb.eu/).
