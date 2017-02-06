<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * Scala like Some.
 */
final class Some extends Option
{

    /**
     * Create a Some instance
     *
     * @param mixed $value 値
     * @return Some
     */
    public static function create($value): Some
    {
        return new static($value);
    }

    /**
     * Constructor
     *
     * @param mixed $value 値
     */
    protected function __construct($value)
    {
        parent::__construct([$value]);
    }

    /**
     * @inheritdoc
     */
    public function filter(\Closure $p): Option
    {
        return $p($this->values[0]) ? $this : Option::none();
    }

    /**
     * @inheritdoc
     */
    public function flatMap(\Closure $f): Option
    {
        $x = $f($this->values[0]);
        if ($x instanceof Option) {
            return $x;
        } else {
            throw new \LogicException("Closure should returns an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function flatten(): Option
    {
        if ($this->values[0] instanceof Option) {
            return $this->values[0];
        } else {
            throw new \LogicException("Element should be an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function getOrCall(\Closure $f)
    {
        return $this->getOrElse($f);
    }

    /**
     * @inheritdoc
     */
    public function getOrElse(\Closure $default)
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function getOrThrow(\Exception $exception)
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function isDefined(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $x = $this->values[0];
        return $x instanceof \JsonSerializable ? $x->jsonSerialize() : $x;
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $f): Some
    {
        return static::create($f($this->values[0]));
    }

    /**
     * @inheritdoc
     */
    public function max()
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f)
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function orElse(\Closure $b): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function orNull()
    {
        return $this->values[0];
    }

    /**
     * @inheritdoc
     */
    public function orElseCall(\Closure $f): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function pick($name): Option
    {
        $x = $this->values[0];
        if (is_array($x) || $x instanceof \ArrayAccess) {
            return Option::fromArray($x, $name);
        } elseif (is_object($x) && (property_exists($x, $name) || method_exists($x, '__get'))) {
            return Option::from($x->{$name});
        } else {
            return Option::none();
        }
    }

}
