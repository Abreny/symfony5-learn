<?php
namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        $this->loadFixtureFiles([__DIR__ . '/../fixtures/UserFixturesTest.yaml']);
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $this->assertEquals(10, $userRepository->count([]));
    }
}