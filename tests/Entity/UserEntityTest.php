<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @author Mohammad Najafy <m.najafy@hotmail.com>
 */
class UserEntityTest extends KernelTestCase
{
    private const USERNAME = 'admin3';
    private const USERNAME_UNIQUE = 'admin';
    private const USERNAME_INVALID = 'admin*';
    private const USERNAME_MIN_CARACTERES = 'a';
    private const USERNAME_MAX_CARACTERES = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. 1234567890._-';
    private const EMAIL = 'admin@e-commerce.com3';
    private const EMAIL_UNIQUE = 'admin@e-commerce.com';
    private const EMAIL_INVALID = 'admin e-commerce com';
    private const PASSWORD = 'Admin-1._@';
    private const PASSWORD_INVALID = 'adminadmin';
    private const PASSWORD_MIN_CARACTERES = 'Ad-1';
    private const PASSWORD_MAX_CARACTERES = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. 1234567890._-';
    private const ROLE = ['ROLE_ADMIN'];

    // Test Username
    public function testUsernameIsEmpty(): void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 2);
    }

    public function testUsernameMinLength(): void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(self::USERNAME_MIN_CARACTERES), 1);
    }

    public function testUsernameMaxLength(): void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(self::USERNAME_MAX_CARACTERES), 1);
    }

    public function testUsernameInvalidCracther(): void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(self::USERNAME_INVALID), 1);
    }

    public function testUsernameUnique(): void
    {
        $this->assertHasErrors($this->getEntity()->setUsername(self::USERNAME_UNIQUE), 1);
    }

    // Test Email
    public function testEmailIsEmpty(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testEmailIsInvalid(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail(self::EMAIL_INVALID), 1);
    }

    public function testEmailIsUniqueEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail(self::EMAIL_UNIQUE), 1);
    }

    // Test Password
    public function testPasswordIsEmpty(): void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 2, ['registration']);
    }

    public function testPasswordMinLength(): void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(self::PASSWORD_MIN_CARACTERES), 1, ['registration']);
    }

    public function testPasswordMaxLength(): void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(self::PASSWORD_MAX_CARACTERES), 1, ['registration']);
    }

    public function testPasswordInvalidCaracther(): void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(self::PASSWORD_INVALID), 1, ['registration']);
    }

    // User is valid 
    public function testUserEntityIsValid(): void
    {
        $this->assertHasErrors($this->getEntity());
    }

    // Get User Entity 
    public function getEntity(): User
    {
        return (new User())->setUsername(self::USERNAME)
            ->setEmail(self::EMAIL)
            ->setPassword(self::PASSWORD)
            ->setRoles(self::ROLE);
    }

    public function assertHasErrors(User $user, int $numbreOfExpectedErrors = 0,array $groups = []): ConstraintViolationList
    {
        self::bootKernel();

        if ($groups !== null) {
            $errors = self::$container->get('validator')->validate($user, null, $groups);
        }else {
            $errors = self::$container->get('validator')->validate($user);
        }

        $message = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $message[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }

        $this->assertCount($numbreOfExpectedErrors, $errors, implode(', ', $message));

        return $errors;
    }
}
