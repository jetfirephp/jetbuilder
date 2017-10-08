<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    *
    */
    public function login($name, $email, $password)
    {
        $I = $this;
        $I->amOnPage('/admin/auth#/login');
        $I->waitForElementVisible('#login-form', 5);
        $I->fillField('#email', $email);
        $I->fillField('#password', $password);
        $I->click('.btn-large');
        $I->waitForElementVisible('.profile-info', 5);
        $I->see($name, '.profile-info');
    }
}
