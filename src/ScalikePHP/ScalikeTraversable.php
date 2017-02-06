<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * Scala like Traversable Implementation.
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    /**
     * 値.
     *
     * @var iterable
     */
    protected $values;

    /**
     * 値.
     *
     * @var array
     */
    protected $array = null;

    /**
     * Constructor.
     *
     * @param iterable $values
     */
    protected function __construct(iterable $values)
    {
        $this->values = $values;
    }

    /**
     * @inheritdoc
     */
    final public function count(): int
    {
        return $this->size();
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        foreach ($this->values as $value) {
            $f($value);
        }
    }

    /**
     * @inheritdoc
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->values as $value) {
            if ($p($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function filterNot(\Closure $p)
    {
        return $this->filter(function ($x) use ($p) {
            return !$p($x);
        });
    }

    /**
     * @inheritdoc
     */
    public function find(\Closure $p): Option
    {
        foreach ($this->values as $x) {
            if ($p($x)) {
                return Option::some($x);
            }
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->values as $x) {
            if (!$p($x)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get values as generator.
     *
     * @return \Generator
     */
    protected function getGenerator(): \Generator
    {
        foreach ($this->values as $value) {
            yield $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): \Iterator
    {
        return $this->values instanceof \Iterator ? $this->values : $this->getGenerator();
    }

    /**
     * @inheritdoc
     */
    public function head()
    {
        foreach ($this->values as $x) {
            return $x;
        }
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function headOption(): Option
    {
        foreach ($this->values as $x) {
            return Option::some($x);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function groupBy($f): Map
    {
        $array = [];
        if (is_string($f)) {
            $key = function ($x) use ($f) {
                return Option::from($x)->pick($f)->getOrElse(function () use ($f): void {
                    throw new \RuntimeException("Undefined index {$f}");
                });
            };
        } elseif ($f instanceof \Closure) {
            $key = $f;
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("Seq::toMap() needs a string or \\Closure. {$type} given.");
        }
        foreach ($this->values as $x) {
            $k = $key($x);
            $array[$k] = isset($array[$k]) ? $array[$k]->append([$x]) : Seq::from($x);
        }
        return Map::from($array);
    }

    /**
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        return $this->takeRight(1)->head();
    }

    /**
     * @inheritdoc
     */
    public function lastOption(): Option
    {
        return $this->takeRight(1)->headOption();
    }

    /**
     * 要素を文字列化して結合する
     *
     * @param string $sep
     * @return string
     */
    public function mkString(string $sep = ""): string
    {
        return implode($sep, $this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function nonEmpty(): bool
    {
        return $this->size() !== 0;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        if (isset($this->values[$offset])) {
            return $this->values[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $x)
    {
        throw new \BadMethodCallException;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException;
    }

    /**
     * @inheritdoc
     */
    public function size(): int
    {
        return count($this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function take(int $n): Seq
    {
        return Seq::fromArray(array_slice($this->toArray(), 0, $n));
    }

    /**
     * @inheritdoc
     */
    public function takeRight(int $n): Seq
    {
        return Seq::fromArray(array_slice($this->toArray(), 0 - $n));
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        if ($this->array === null) {
            if (is_array($this->values)) {
                $this->array = $this->values;
            } else {
                $this->array = [];
                foreach ($this->values as $value) {
                    $this->array[] = $value;
                }
            }
        }
        return $this->array;
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return Seq::fromArray($this->values);
    }

}
