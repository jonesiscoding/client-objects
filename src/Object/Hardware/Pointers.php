<?php

namespace DevCoding\Client\Object\Hardware;

/**
 * Object representing the pointer on a device.
 *
 * @package DevCoding\Client\Object\Hardware
 */
class Pointers extends \ArrayObject
{
  /** @var string Represents a 'fine' pointing device */
  const FINE = 'fine';
  /** @var string Represents a 'coarse' pointing device */
  const COARSE = 'coarse';
  /** @var string Represents 'no pointing' device */
  const NONE = 'none';
  /** @var string Represents a 'coarse' pointer without touch, such as a TV or gaming system. */
  const TOUCHLESS = 'coarse-touchless';

  /** @var string */
  protected $primary;
  /** @var string[]  */
  private $possible = [self::COARSE, self::FINE, self::NONE, self::TOUCHLESS];

  /**
   * @param string   $primary
   * @param string[] $additional
   */
  public function __construct($primary, $additional = [])
  {
    // Merge for later use
    $merged = array_unique(array_merge([$primary], $additional));

    // Validate the input
    if (self::NONE === $primary && !empty(array_diff([self::NONE], [$additional])))
    {
      throw new \InvalidArgumentException($this->getNoneMessage());
    }
    else
    {
      $invalids = array_diff($merged, $this->possible);
      if (!empty($invalids))
      {
        throw new \InvalidArgumentException($this->getInvalidMessage($merged));
      }
    }

    $this->primary = $primary;

    parent::__construct(array_fill_keys($merged, true));
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return implode(', ', $this->getArrayCopy());
  }

  // region //////////////////////////////////////////////// API Methods

  /**
   * @return string
   */
  public function getPrimary(): string
  {
    return $this->primary;
  }

  public function any(string $type): bool
  {
    return $this->offsetExists($type);
  }

  public function is(string $type): bool
  {
    return $type === $this->primary;
  }

  public function isAnyCoarse(): bool
  {
    return $this->any(self::COARSE);
  }

  public function isAnyFine(): bool
  {
    return $this->any(self::FINE);
  }

  public function isAnyTouch(): bool
  {
    return $this->any(self::COARSE) && !$this->any(self::TOUCHLESS);
  }

  public function isCoarse(): bool
  {
    return $this->is(self::COARSE);
  }

  public function isFine(): bool
  {
    return $this->is(self::FINE);
  }

  public function isHybrid(): bool
  {
    return $this->any(self::FINE) && $this->any(self::COARSE) && !$this->any(self::TOUCHLESS);
  }

  /**
   * @return bool
   */
  public function isTouch(): bool
  {
    return $this->is(self::COARSE) && !$this->any(self::TOUCHLESS);
  }

  // endregion ///////////////////////////////////////////// End API Methods

  // region //////////////////////////////////////////////// ArrayObject Methods

  public function getArrayCopy()
  {
    return array_keys(parent::getArrayCopy());
  }

  /**
   * @param string $key
   * @param bool|string|int $value
   *
   * @return void
   */
  public function offsetSet($key, $value)
  {
    $value = $this->toBool($value);
    if (!is_bool($value))
    {
      $value = is_scalar($value) ? $value : (is_object($value) ? get_class($value) : gettype($value));

      throw new \InvalidArgumentException(sprintf('Invalid value (%s) for %s in %s', $value, $key, get_class($this)));
    }
    elseif ($this->primary === $key)
    {
      throw new \LogicException('You cannot change the value of the primary pointer in '.get_class($this));
    }
    elseif (self::NONE === $this->primary && $value)
    {
      throw new \LogicException($this->getNoneMessage());
    }
    elseif (!in_array($key, $this->possible))
    {
      throw new \LogicException($this->getInvalidMessage($value));
    }

    parent::offsetSet($key, $value);
  }

  // endregion ///////////////////////////////////////////// End ArrayObject Methods

  // region //////////////////////////////////////////////// Helper Methods

  /**
   * @param mixed $value
   *
   * @return bool|mixed
   */
  private function toBool($value)
  {
    if (!is_bool($value))
    {
      if (preg_match('#(on|true|1|yes)#i', $value))
      {
        $value = true;
      }
      elseif (preg_match('#(off|false|0|no)#i', $value))
      {
        $value = false;
      }
    }

    return $value;
  }

  /**
   * @param array|string $invalids
   *
   * @return string
   */
  private function getInvalidMessage($invalids): string
  {
    return sprintf(
      'Invalid value "%s" for $pointer in %s.  Must be one of: %s',
      is_array($invalids) ? implode(', ', $invalids) : $invalids,
      get_class($this),
      implode(', ', $this->possible)
    );
  }

  /**
   * @return string
   */
  private function getNoneMessage(): string
  {
    return sprintf('Additional pointers may not be used with "pointer: %s" in "%s"', self::NONE, get_class($this));
  }

  // endregion ///////////////////////////////////////////// End Helper Methods
}
