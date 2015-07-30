<?php
namespace ScalikePHP;

use Exception;
use RuntimeException;

/**
 * Scala like None
 */
final class None extends Option
{

    /**
     * Singleton instance
     *
     * @var None
     */
    private static $instance = null;

    /**
     * Get a None instance
     *
     * @return None
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->values = [];
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        throw new RuntimeException("None has no value.");
    }

    /**
     * {@inheritdoc}
     */
    public function getOrCall(callable $callback)
    {
        return call_user_func($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse($default)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrNull()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getOrThrow(Exception $exception)
    {
        throw $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function isDefined()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(Option $b)
    {
        return $b;
    }

    /**
     * {@inheritdoc}
     */
    public function orElseCall(callable $f)
    {
        return $f();
    }

    /**
     * {@inheritdoc}
     */
    public function pick($name)
    {
        return $this;
    }

}
