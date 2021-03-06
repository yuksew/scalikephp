<?php
namespace ScalikePHP;

/**
 * A Map Implementation using PHP array
 */
class ArrayMap extends Map
{

    /**
     * {@inheritdoc}
     */
    public function append($keyOrArray, $value = null)
    {
        if (is_array($keyOrArray)) {
            return new ArrayMap($keyOrArray + $this->toArray());
        } elseif ($keyOrArray instanceof Map) {
            return new ArrayMap($keyOrArray->toArray() + $this->toArray());
        } elseif ($keyOrArray instanceof \Traversable) {
            return new ArrayMap(Map::from($keyOrArray)->toArray() + $this->toArray());
        } else {
            return new ArrayMap([$keyOrArray => $value] + $this->toArray());
        }
    }

    /**
     * 指定されたキーが存在するかどうかを判定する
     *
     * @param string $key
     * @return bool
     */
    public function contains($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            if (call_user_func($f, $x, $key)) {
                $array[$key] = $x;
            }
        }
        return new ArrayMap($array);
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $result = call_user_func($f, $x, $key);
            if (is_array($result)) {
                $array = $result + $array;
            } elseif ($result instanceof ScalikeTraversable) {
                $array = $result->toArray() + $array;
            } elseif ($result instanceof \Traversable) {
                $array = Map::from($result)->toArray() + $array;
            } else {
                throw new \InvalidArgumentException('$f should returns a Traversable or an array.');
            }
        }
        return new ArrayMap($array);
    }

    /**
     * {@inheritdoc}
     */
    public function fold($z, callable $f)
    {
        foreach ($this->values as $key => $x) {
            $z = call_user_func($f, $z, $x);
        }
        return $z;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return Option::fromArray($this->values, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse($key, $default)
    {
        return $this->get($key)->getOrElse($default);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return Seq::fromArray(array_keys($this->values));
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            list($newKey, $newValue) = call_user_func($f, $x, $key);
            $array[$newKey] = $newValue;
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     */
    public function mapValues(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $array[$key] = call_user_func($f, $x);
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     *
     * Scala 同様, 値ではなくキーが最大となる要素（キーと値のペア）を返す.
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.max');
        }
        return $this->toSeq()->max();
    }

    /**
     * {@inheritdoc}
     *
     * Scala 同様, 値ではなく要素（キーと値のペアを）返す.
     */
    public function maxBy(callable $f)
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.max');
        }
        $max = null;
        $res = null;
        foreach ($this->values as $key => $value) {
            $x = call_user_func($f, $value, $key);
            if ($max === null || $max < $x) {
                $max = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     *
     * Scala 同様, 値ではなくキーが最小となる要素（キーと値のペア）を返す.
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.min');
        }
        return $this->toSeq()->min();
    }

    /**
     * {@inheritdoc}
     *
     * Scala 同様, 値ではなく要素（キーと値のペアを）返す.
     */
    public function minBy(callable $f)
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.min');
        }
        $min = null;
        $res = null;
        foreach ($this->values as $key => $value) {
            $x = call_user_func($f, $value, $key);
            if ($min === null || $min > $x) {
                $min = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq()
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $array[] = [$key, $x];
        }
        return Seq::fromArray($array);
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return Seq::fromArray($this->values);
    }

}
