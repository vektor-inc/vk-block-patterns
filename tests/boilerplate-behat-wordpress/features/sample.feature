Feature: Example Features

 Scenario: Check Varktech Pricing Deals PRO Update 
    Given the screen size is 1440x900
    And I login as "e2e-test-user-admin" with password "*@LcAaIzy&EknmdgSiN0M6)7"
    When I am on "/wp-admin/"
    Then I should not see "pro plugin will **not discount** until updated"
     
 Scenario: Upate api success in Develop.
    Given I am on "https://dev.vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-pro&installed_version=1.6.2&php=7.3.7&locale=ja&lightning-pro-license-key=e4487803e18ccbe3f33e95e405591fc3"
    Then I should see "https:\/\/dev.vws.vektor-inc.co.jp\/wp-content\/themes\/lightning-pro-child-vws\/updates\/?action=download&slug=lightning-pro"
    
 Scenario: Upate api fail when license key is wrong  in Develop.
    Given I am on "https://dev.vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-pro&installed_version=1.6.2&php=7.3.7&locale=ja&lightning-pro-license-key=fe3e095f3dbe0592a52fc5f8321062fx"
    Then I should not see "https:\/\/dev.vws.vektor-inc.co.jp\/wp-content\/themes\/lightning-pro-child-vws\/updates\/?action=download&slug=lightning-pro"
  
  Scenario: Upate api success in Production.
    Given I am on "https://vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-pro&installed_version=1.6.2&php=7.3.7&locale=ja&lightning-pro-license-key=fe3e095f3dbe0592a52fc5f8321062fa"
    Then I should see "https:\/\/vws.vektor-inc.co.jp\/wp-content\/themes\/lightning-pro-child-vws\/updates\/?action=download&slug=lightning-pro"
    
  Scenario: Upate api fail when license key is wrong  in Production.
    Given I am on "https://vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-pro&installed_version=1.6.2&php=7.3.7&locale=ja&lightning-pro-license-key=fe3e095f3dbe0592a52fc5f8321062fx"
    Then I should not see "https:\/\/vws.vektor-inc.co.jp\/wp-content\/themes\/lightning-pro-child-vws\/updates\/?action=download&slug=lightning-pro"
    
  Scenario: Forum post with permission
    Given the screen size is 1440x900
    And I login as "e2e-test-user-buy-pro" with password "f40QlA47CcqQo"
    
    When I am on "/forums/forum/lightning"
    Then I should see "「Lightning」にトピックを新規作成"
    
  Scenario: Forum post without permission
    Given the screen size is 1440x900
    And I login as "e2e-test-user" with password "f40QlA47CcqQo"
    
    When I am on "/forums/forum/lightning"
    Then I should see "トピックを新規作成することはできません。"
    Then take a screenshot and save it to "_out/1440x900.png"