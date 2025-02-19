<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase {

    private function getValidator(): ValidatorInterface
    {
        self::bootKernel();
        return self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidEntity(): void
    {
        $user = (new User())
            ->setEmail('userEntity@ratisseur.com')
            ->setPassword('CouCou@1234')
            ->setRoles(['ROLE_USER']);   

        $errors = $this->getValidator()->validate($user);

        self::assertCount(0, $errors, "L'entité User ne devrait pas contenir d'erreurs de validation.");
    }

    // ----------- TESTS SUR L'EMAIL -----------

    public function testInvalidEmailFormat(): void
    {
        $user = (new User())->setEmail('invalid-email')->setPassword('CouCou@1234');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "L'email invalide devrait être rejeté.");
    }

    public function testEmptyEmail(): void
    {
        $user = (new User())->setEmail('')->setPassword('CouCou@1234');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un email vide devrait être rejeté.");
    }

    // ----------- TESTS SUR LE MOT DE PASSE -----------

    public function testShortPassword(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('Short1!');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe trop court devrait être rejeté.");
    }

    public function testPasswordWithoutUppercase(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('lowercase1!');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe sans majuscule devrait être rejeté.");
    }

    public function testPasswordWithoutLowercase(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('UPPERCASE1!');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe sans minuscule devrait être rejeté.");
    }

    public function testPasswordWithoutDigit(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('NoDigitPass!');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe sans chiffre devrait être rejeté.");
    }

    public function testPasswordWithoutSpecialCharacter(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('NoSpecialChar1');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe sans caractère spécial devrait être rejeté.");
    }

    public function testEmptyPassword(): void
    {
        $user = (new User())->setEmail('valid@email.com')->setPassword('');
        $errors = $this->getValidator()->validate($user);

        self::assertGreaterThan(0, count($errors), "Un mot de passe vide devrait être rejeté.");
    }
}