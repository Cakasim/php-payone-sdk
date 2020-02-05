<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Container;

use Cakasim\Payone\Sdk\Container\Container;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\A;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\AbstractA;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\AInterface;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\B;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\BInterface;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\C;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\ChildOfA;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\D;
use Cakasim\Payone\Sdk\Tests\Container\DummyClasses\E;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ContainerTest extends TestCase
{
    /**
     * @testdox Container construction and self binding
     */
    public function testContainer(): void
    {
        $container = new Container();
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertTrue($container->has(ContainerInterface::class));
        $this->assertSame($container, $container->get(ContainerInterface::class));
    }

    /**
     * @testdox Get valid entry
     */
    public function testGetEntry(): void
    {
        $container = new Container();

        $container->bind(AInterface::class, A::class);
        $this->assertInstanceOf(A::class, $container->get(AInterface::class));

        $container->bind(B::class);
        $this->assertInstanceOf(B::class, $container->get(B::class));
    }

    /**
     * @testdox Get non-existing entry throws exception
     */
    public function testGetNonExistingEntry(): void
    {
        $container = new Container();
        $this->expectException(NotFoundExceptionInterface::class);
        $container->get(C::class);
    }

    /**
     * @testdox Binding to existing entry throws exception
     */
    public function testBindingToExistingEntry(): void
    {
        $container = new Container();
        $container->bind(AInterface::class, A::class);
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind(AInterface::class, ChildOfA::class);
    }

    /**
     * @testdox Make valid class binding
     */
    public function testClassBinding(): void
    {
        $container = new Container();
        $container->bind(AbstractA::class, A::class);
        $container->bind(BInterface::class, B::class);
        $container->bind(C::class, null, true);
        $this->assertInstanceOf(A::class, $container->get(AbstractA::class));
        $this->assertInstanceOf(B::class, $container->get(BInterface::class));

        $c1 = $container->get(C::class);
        $c2 = $container->get(C::class);
        $this->assertInstanceOf(C::class, $c1);
        $this->assertSame($c1, $c2);
    }

    /**
     * @testdox Class binding with non-existing abstract throws exception
     */
    public function testClassBindingWithNonExistingAbstract(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind('Non\Existing\AbstractClass', A::class);
    }

    /**
     * @testdox Class binding with non-existing concrete throws exception
     */
    public function testClassBindingWithNonExistingConcrete(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind(AInterface::class, 'Non\Existing\ConcreteClass');
    }

    /**
     * @testdox Class binding throws exception if concrete is no subclass of abstract
     */
    public function testClassBindingWithBadSubclass(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind(AInterface::class, B::class);
    }

    /**
     * @testdox Class binding throws exception if concrete is not instantiable
     */
    public function testClassBindingWithNonInstantiableConcrete(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind(AInterface::class, AbstractA::class);
    }

    /**
     * @testdox Class binding throws exception if the constructor of concrete is invalid
     */
    public function testClassBindingWithInvalidConstructor(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bind(D::class);
    }

    /**
     * @testdox Make valid instance binding
     */
    public function testInstanceBinding(): void
    {
        $container = new Container();
        $container->bindInstance(AbstractA::class, new ChildOfA());
        $container->bindInstance(BInterface::class, new B());
        $container->bindInstance(C::class, new C());
        $this->assertInstanceOf(ChildOfA::class, $container->get(AbstractA::class));
        $this->assertInstanceOf(B::class, $container->get(BInterface::class));
        $this->assertInstanceOf(C::class, $container->get(C::class));
    }

    /**
     * @testdox Instance binding with invalid concrete throws exception
     */
    public function testInstanceBindingWithInvalidConcrete(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bindInstance(AInterface::class, 'I am not an object!');
    }

    /**
     * @testdox Instance binding throws exception if concrete is not an instance of abstract
     */
    public function testInstanceBindingWithBadInstanceOf(): void
    {
        $container = new Container();
        $this->expectException(ContainerExceptionInterface::class);
        $container->bindInstance(AInterface::class, new B());
    }

    /**
     * @testdox Make valid callable binding
     */
    public function testCallableBinding(): void
    {
        $container = new Container();

        $container->bindCallable(AbstractA::class, function (BInterface $b) {
            return new A();
        });

        $container->bindCallable(BInterface::class, function () {
            return new B();
        });

        $container->bindCallable(C::class, function (AbstractA $a, BInterface $b) {
            return new C();
        }, true);

        $this->assertInstanceOf(A::class, $container->get(AbstractA::class));
        $this->assertInstanceOf(B::class, $container->get(BInterface::class));

        $c1 = $container->get(C::class);
        $c2 = $container->get(C::class);
        $this->assertInstanceOf(C::class, $c1);
        $this->assertSame($c1, $c2);
    }

    /**
     * @testdox Callable binding throws exception if the callable has no proper type hints
     */
    public function testCallableBindingWithInvalidCallable(): void
    {
        $container = new Container();

        $this->expectException(ContainerExceptionInterface::class);

        $container->bindCallable(A::class, function ($invalid, B $param, string $type, $hints = null) {
            return new A();
        });
    }

    /**
     * @testdox Callable binding throws exception if the callable returns an invalid value
     */
    public function testCallableBindingWithBadReturnValue(): void
    {
        $container = new Container();

        $container->bindCallable(A::class, function () {
            return null;
        });

        $this->expectException(ContainerExceptionInterface::class);
        $container->get(A::class);
    }

    /**
     * @testdox Callable binding throws exception if the callable returns a concrete that is not an instance of abstract
     */
    public function testCallableBindingWithBadInstanceOf(): void
    {
        $container = new Container();

        $container->bindCallable(A::class, function () {
            return new B();
        });

        $this->expectException(ContainerExceptionInterface::class);
        $container->get(A::class);
    }

    /**
     * @testdox Dependency injection of non-existing entries throws exception
     */
    public function testDiOfNonExistingEntries(): void
    {
        $container = new Container();
        $container->bind(E::class); // E requires A, B and C
        $container->bind(A::class);
        $container->bind(B::class);
        $this->expectException(ContainerExceptionInterface::class);
        $container->get(E::class);
    }
}
