@set_payment_method_extra_fee
Feature: Set payment method extra fee
  In order to add extra fee for a certain payment method
  As an Administrator
  I want to be able to define a extra fee amount for a payment method

  Background:
    Given the store operates on a single channel in "United States"
    And the store has zones "NorthAmerica", "SouthAmerica" and "Europe"
    And the store has a payment method "Offline" with a code "offline"
    And I am logged in as an administrator

  @ui
  Scenario: Being able to define a fee amount for the payment method
    Given I want to modify the "Offline" payment method
    When I save my changes
    And I add an extra fee of '30'$
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this payment method have an extra fee of '30'$
