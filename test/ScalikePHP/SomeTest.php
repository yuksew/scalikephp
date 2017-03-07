<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace Test\ScalikePHP;

use ScalikePHP\Option;
use ScalikePHP\Seq;
use ScalikePHP\Some;

/**
 * Tests for Some.
 *
 * @see \ScalikePHP\Some
 */
class SomeTest extends TestCase
{

    /**
     * Tests for Some::count().
     *
     * @see \ScalikePHP\Some::count()
     */
    public function testCount(): void
    {
        Assert::same(1, Some::create(1)->count());
        Assert::same(1, Some::create(2)->count());
        Assert::same(1, Some::create("abc")->count());
        Assert::same(1, Some::create("xyz")->count());
    }

    /**
     * Tests for Some::create().
     *
     * @see \ScalikePHP\Some::create()
     */
    public function testCreate(): void
    {
        Assert::instanceOf(Some::class, Some::create(1));
        Assert::instanceOf(Some::class, Some::create("abc"));
        Assert::instanceOf(Some::class, Some::create(null));
        Assert::same(1, Some::create(1)->get());
        Assert::same("abc", Some::create("abc")->get());
        Assert::same(null, Some::create(null)->get());
    }

    /**
     * Tests for Some::each().
     *
     * @see \ScalikePHP\Some::each()
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, "spy"], func_get_args());
        };
        $spy->shouldReceive("spy")->with(1)->once();
        Some::create(1)->each($f);
    }

    /**
     * Tests for Option::exists().
     *
     * @see \ScalikePHP\Option::exists()
     */
    public function testExists(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::true(Some::create(1)->exists($p));
        Assert::false(Some::create(2)->exists($p));
    }

    /**
     * Tests for Option::filter().
     *
     * @see \ScalikePHP\Option::filter()
     */
    public function testFilter(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::some(1, Some::create(1)->filter($p));
        Assert::none(Some::create(2)->filter($p));
    }

    /**
     * Tests for Option::filterNot().
     *
     * @see \ScalikePHP\Option::filterNot()
     */
    public function testFilterNot(): void
    {
        $p = function (int $x): bool {
            return $x !== 1;
        };
        Assert::some(1, Some::create(1)->filterNot($p));
        Assert::none(Some::create(2)->filterNot($p));
    }

    /**
     * Tests for Some::find().
     *
     * @see \ScalikePHP\Some::find()
     */
    public function testFind(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::some(1, Some::create(1)->filter($p));
        Assert::none(Some::create(2)->filter($p));
    }

    /**
     * Tests for Some::flatMap().
     *
     * @see \ScalikePHP\Some::flatMap()
     */
    public function testFlatMap(): void
    {
        $returnsSome = function ($x): Option {
            return Option::from($x * 2);
        };
        $returnsNone = function (): Option {
            return Option::none();
        };
        $returnsArray = function ($x): array {
            return [$x * 2];
        };
        Assert::some(2, Some::create(1)->flatMap($returnsSome));
        Assert::none(Some::create(1)->flatMap($returnsNone));
        Assert::throws(
            \LogicException::class,
            function () use ($returnsArray): void {
                Some::create(1)->flatMap($returnsArray);
            }
        );
    }

    /**
     * Tests for Some::flatten().
     *
     * @see \ScalikePHP\Some::flatten()
     */
    public function testFlatten(): void
    {
        Assert::some("abc", Some::create(Some::create("abc"))->flatten());
        Assert::none(Some::create(Option::none())->flatten());
    }

    /**
     * Tests for Some::forAll().
     *
     * @see \ScalikePHP\Some::forAll()
     */
    public function testForAll(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::true(Some::create(1)->forAll($p));
        Assert::false(Some::create(2)->forAll($p));
    }

    /**
     * Tests for Some::get().
     *
     * @see \ScalikePHP\Some::get()
     */
    public function testGet(): void
    {
        Assert::same(1, Some::create(1)->get());
        Assert::same("abc", Some::create("abc")->get());
    }

    /**
     * Tests for Some::getOrElse().
     *
     * @see \ScalikePHP\Some::getOrElse()
     */
    public function testGetOrElse(): void
    {
        $spy = self::spy();
        $f = function () use ($spy) {
            return call_user_func_array([$spy, "spy"], func_get_args());
        };
        $spy->shouldNotReceive("spy")->andReturn("abc");
        Assert::same(1, Some::create(1)->getOrElse($f));
        Assert::same("abc", Some::create("abc")->getOrElse($f));
    }

    /**
     * Tests for Some::groupBy().
     *
     * @see \ScalikePHP\Some::groupBy()
     */
    public function testGroupBy(): void
    {
        $key = "abc";
        $value = "xyz";
        $array = [$key => $value];
        $some = Some::create($array);
        $closure = function (array $x) use ($key): string {
            return $x[$key];
        };
        Assert::same(1, $some->groupBy($key)->size());
        Assert::same(1, $some->groupBy($closure)->size());
        Assert::same([$value], $some->groupBy($key)->keys()->toArray());
        Assert::same([$value], $some->groupBy($closure)->keys()->toArray());
        Assert::same([$array], $some->groupBy($key)->get($value)->get()->toArray());
        Assert::same([$array], $some->groupBy($closure)->get($value)->get()->toArray());
    }

    /**
     * Tests for Some::head().
     *
     * @see \ScalikePHP\Some::head()
     */
    public function testHead(): void
    {
        Assert::same(1, Some::create(1)->head());
        Assert::same("abc", Some::create("abc")->head());
    }

    /**
     * Tests for Some::headOption().
     *
     * @see \ScalikePHP\Some::headOption()
     */
    public function testHeadOption(): void
    {
        Assert::some(1, Some::create(1)->headOption());
        Assert::some("abc", Some::create("abc")->headOption());
    }

    /**
     * Tests for Some::isDefined().
     *
     * @see \ScalikePHP\Some::isDefined()
     */
    public function testIsDefined(): void
    {
        Assert::true(Some::create(1)->isDefined());
        Assert::true(Some::create("abc")->isDefined());
    }

    /**
     * Tests for Some::isEmpty().
     *
     * @see \ScalikePHP\Some::isEmpty()
     */
    public function testIsEmpty(): void
    {
        Assert::false(Some::create(1)->isEmpty());
        Assert::false(Some::create("abc")->isEmpty());
    }

    /**
     * Tests for Some::last().
     *
     * @see \ScalikePHP\Some::last()
     */
    public function testLast(): void
    {
        Assert::same(1, Some::create(1)->last());
        Assert::same("abc", Some::create("abc")->last());
    }

    /**
     * Tests for Some::lastOption().
     *
     * @see \ScalikePHP\Some::lastOption()
     */
    public function testLastOption(): void
    {
        Assert::some(1, Some::create(1)->lastOption());
        Assert::some("abc", Some::create("abc")->lastOption());
    }

    /**
     * Tests for Some::map().
     *
     * @see \ScalikePHP\Some::map()
     */
    public function testMap(): void
    {
        $f = function (int $x): int {
            return $x * 2;
        };
        Assert::some(2, Some::create(1)->map($f));
        Assert::some(4, Some::create(2)->map($f));
        Assert::some(6, Some::create(3)->map($f));
    }

    /**
     * Tests for Some::max().
     *
     * @see \ScalikePHP\Some::max()
     */
    public function testMax(): void
    {
        Assert::same(1, Some::create(1)->max());
        Assert::same("abc", Some::create("abc")->max());
    }

    /**
     * Tests for Some::maxBy().
     *
     * @see \ScalikePHP\Some::maxBy()
     */
    public function testMaxBy(): void
    {
        $f = function ($x): string {
            return strval($x);
        };
        Assert::same(1, Some::create(1)->maxBy($f));
        Assert::same("abc", Some::create("abc")->maxBy($f));
    }

    /**
     * Tests for Some::min().
     *
     * @see \ScalikePHP\Some::min()
     */
    public function testMin(): void
    {
        Assert::same(1, Some::create(1)->min());
        Assert::same("abc", Some::create("abc")->min());
    }

    /**
     * Tests for Some::minBy().
     *
     * @see \ScalikePHP\Some::minBy()
     */
    public function testMinBy(): void
    {
        $f = function ($x): string {
            return strval($x);
        };
        Assert::same(1, Some::create(1)->minBy($f));
        Assert::same("abc", Some::create("abc")->minBy($f));
    }

    /**
     * Tests for Some::mkString().
     *
     * @see \ScalikePHP\Some::mkString()
     */
    public function testMkString(): void
    {
        Assert::same("1", Some::create(1)->mkString());
        Assert::same("abc", Some::create("abc")->mkString());
    }

    /**
     * Tests for Some::nonEmpty().
     *
     * @see \ScalikePHP\Some::nonEmpty()
     */
    public function testNonEmpty(): void
    {
        Assert::true(Some::create(1)->nonEmpty());
        Assert::true(Some::create("abc")->nonEmpty());
    }

    /**
     * Tests for Some::orElse().
     *
     * @see \ScalikePHP\Some::orElse()
     */
    public function testOrElse(): void
    {
        $a = Some::create(1);
        $b = Some::create("abc");
        $f = function (): string {
            return "xyz";
        };
        Assert::same($a, $a->orElse($f));
        Assert::same($b, $b->orElse($f));
    }

    /**
     * Tests for Some::orNull().
     *
     * @see \ScalikePHP\Some::orNull()
     */
    public function testOrNull(): void
    {
        Assert::same(1, Some::create(1)->orNull());
        Assert::same("abc", Some::create("abc")->orNull());
    }

    /**
     * Tests for Some::pick().
     *
     * @see \ScalikePHP\Some::pick()
     */
    public function testPick(): void
    {
        Assert::some("xyz", Some::create(["abc" => "xyz"])->pick("abc"));
    }

    /**
     * Tests for Some::size().
     *
     * @see \ScalikePHP\Some::size()
     */
    public function testSize(): void
    {
        Assert::same(1, Some::create(1)->size());
        Assert::same(1, Some::create(2)->size());
        Assert::same(1, Some::create("abc")->size());
        Assert::same(1, Some::create("xyz")->size());
    }

    /**
     * Tests for Some::take().
     *
     * @see \ScalikePHP\Some::take()
     */
    public function testTake(): void
    {
        Assert::instanceOf(Some::class, Some::create(1)->take(1));
        Assert::instanceOf(Some::class, Some::create("abc")->take(1));
        Assert::same([1], Some::create(1)->take(1)->toArray());
        Assert::same([1], Some::create(1)->take(2)->toArray());
        Assert::same(["abc"], Some::create("abc")->take(1)->toArray());
        Assert::same(["abc"], Some::create("abc")->take(2)->toArray());
    }

    /**
     * Tests for Some::takeRight().
     *
     * @see \ScalikePHP\Some::takeRight()
     */
    public function testTakeRight(): void
    {
        Assert::instanceOf(Some::class, Some::create(1)->takeRight(1));
        Assert::instanceOf(Some::class, Some::create("abc")->takeRight(1));
        Assert::same([1], Some::create(1)->takeRight(1)->toArray());
        Assert::same([1], Some::create(1)->takeRight(2)->toArray());
        Assert::same(["abc"], Some::create("abc")->takeRight(1)->toArray());
        Assert::same(["abc"], Some::create("abc")->takeRight(2)->toArray());
    }

    /**
     * Tests for Some::toArray().
     *
     * @see \ScalikePHP\Some::toArray()
     */
    public function testToArray(): void
    {
        Assert::same([1], Some::create(1)->toArray());
        Assert::same(["abc"], Some::create("abc")->toArray());
    }

    /**
     * Tests for Some::toSeq().
     *
     * @see \ScalikePHP\Some::toSeq()
     */
    public function testToSeq(): void
    {
        Assert::instanceOf(Seq::class, Some::create(1)->toSeq());
        Assert::instanceOf(Seq::class, Some::create("abc")->toSeq());
        Assert::same([1], Some::create(1)->toSeq()->toArray());
        Assert::same(["abc"], Some::create("abc")->toSeq()->toArray());
    }

}