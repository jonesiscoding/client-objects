<?php
/**
 * HeaderBag.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Headers;

/**
 * Container representing HTTP headers, as taken from the $_SERVER global.  Note that all keys should be given without
 * the HTTP_ prefix.
 *
 * The concept of this class is based on the Symfony HttpFoundation component.  This version is simplified and
 * does not contain all the same functionality.
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @license https://github.com/deviscoding/objection/blob/main/LICENSE
 *
 * @package DevCoding\Object\Internet
 */
class HeaderBag
{
  const UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const LOWER = '-abcdefghijklmnopqrstuvwxyz';

  /** @var array Array containing previously parsed headers */
  protected $headers;

  /**
   * Resolves the given header key or array of header keys into a value, using the value of the first header that is
   * set in the $_SERVER global or this object.
   *
   * @param array|string $key_or_keys The header key, without the HTTP_ prefix
   *
   * @return mixed|null The value, or null if it cannot be resolved
   */
  public function resolve($key_or_keys)
  {
    if (!is_array($key_or_keys))
    {
      try
      {
        return $this->get($key_or_keys);
      }
      catch (\Exception $e)
      {
        return null;
      }
    }
    else
    {
      foreach ($key_or_keys as $key)
      {
        if ($this->has($key))
        {
          return $this->resolve($key);
        }
      }
    }

    return null;
  }

  /**
   * Returns the value of the header key given.
   *
   * @param string $id the header key to retrieve, given without the HTTP_ prefix
   *
   * @return mixed the value of the given header key
   *
   * @throws \Exception if the header is not set
   */
  public function get(string $id)
  {
    if (!$this->has($id))
    {
      throw new \Exception(sprintf('The header "%s" was not found.', $id));
    }

    return $this->headers[$id];
  }

  /**
   * Evaluates whether a value is set for the given header key, which should be given without the HTTP_ prefix.
   *
   * @return bool
   */
  public function has(string $id)
  {
    if (!isset($this->headers[$id]))
    {
      $sKey = $this->toServer($id);
      $sVal = $_ENV[$sKey] ?? $_SERVER[$sKey];
      if (!isset($sVal))
      {
        return false;
      }

      $this->headers[$id] = $sVal;
    }

    return true;
  }

  /**
   * Populates the $_SERVER global with the given array of values.  The array keys should be given without the HTTP_
   * prefix.
   *
   * @param string[] $array     the array of header_key => value
   * @param bool     $overwrite if existing values should be overwritten
   *
   * @return $this
   */
  public function populate($array, $overwrite = false)
  {
    foreach ($array as $key => $value)
    {
      $sKey = $this->toServer($key);
      $sVal = $_ENV[$sKey] ?? $_SERVER[$sKey];
      if (!isset($sVal) || $overwrite)
      {
        // TODO: Set in ENV?
        $_SERVER[$sKey] = $value;
      }
    }

    return $this;
  }

  /**
   * Given a header key, returns the key the header would use within the $_SERVER global.
   *
   * @param string $id
   *
   * @return string
   */
  public static function toServer($id)
  {
    return 'HTTP_'.strtr($id, self::LOWER, self::UPPER);
  }
}
