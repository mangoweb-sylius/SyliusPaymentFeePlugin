<?php

declare(strict_types=1);

namespace MangoSylius\PaymentFeePlugin\Model;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;

trait PaymentMethodWithFeeTrait
{
    /**
     * @var string|null
     * @ORM\Column(name="calculator", type="text", nullable=true)
     */
    protected $calculator;

    /**
     * @var TaxCategoryInterface|null
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxation\Model\TaxCategory")
     * @ORM\JoinColumn(name="tax_category_id")
     */
    protected $taxCategory;

    /**
     * @var array
     * @ORM\Column(name="calculator_configuration", type="json", nullable=true)
     */
    protected $calculatorConfiguration = [];

    public function getCalculator(): ?string
    {
        return $this->calculator;
    }

    public function setCalculator(?string $calculator)
    {
        $this->calculator = $calculator;
    }

    public function getCalculatorConfiguration(): array
    {
        return $this->calculatorConfiguration ?? [];
    }

    public function setCalculatorConfiguration(array $calculatorConfiguration)
    {
        $this->calculatorConfiguration = $calculatorConfiguration;
    }

    public function getTaxCategory(): ?TaxCategoryInterface
    {
        return $this->taxCategory;
    }

    public function setTaxCategory(?TaxCategoryInterface $taxCategory): void
    {
        $this->taxCategory = $taxCategory;
    }
}
