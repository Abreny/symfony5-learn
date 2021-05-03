<?php

namespace App\Tests\Entity;

use App\Entity\ValidationCode;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationCodeTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->validator = static::$container
            ->get('validator');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->validator = null;
    }

    private function getValidationCode(): ValidationCode
    {
        return (new ValidationCode())
            ->setCode('12345')
            ->setDescription('Description code test')
            ->setCreatedAt(new DateTime())
            ->setStatus('pending');
    }

    private function assertHasErrors(ValidationCode $code, $errorExpected = 0)
    {
        self::bootKernel();
        $errors = $this->validator->validate($code);
        $this->assertCount($errorExpected, $errors);
    }

    public function testValidCode()
    {
        $this->assertHasErrors($this->getValidationCode(), 0);
    }
    public function testInvalidCodeContainsNotOnlyDigit()
    {
        $this->assertHasErrors($this->getValidationCode()->setCode('123er'), 1);
    }
    public function testInvalidCodeContainsLessThanFiveDigits()
    {
        $this->assertHasErrors($this->getValidationCode()->setCode('123'), 1);
    }

    public function testInvalidCodeContainsGreaterThanFiveDigits()
    {
        $this->assertHasErrors($this->getValidationCode()->setCode('1234567'), 1);
    }

    public function testInvalidBlankCode()
    {
        $this->assertHasErrors($this->getValidationCode()->setCode(''), 1);
        $this->assertHasErrors($this->getValidationCode()->setCode('    '), 1);
    }

    public function testCodeRequiredAttributes()
    {
        $this->assertHasErrors(new ValidationCode(), 4);
    }

    public function testValidationCodeStatus()
    {
        $this->assertHasErrors($this->getValidationCode()->setStatus('invalid'), 1);
        $this->assertHasErrors($this->getValidationCode()->setStatus('used'), 0);
        $this->assertHasErrors($this->getValidationCode()->setStatus('pending'), 0);
    }
}
