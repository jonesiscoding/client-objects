<?php

namespace DevCoding\Client\Resolver\Browser;

class BrowserPattern
{
  /** @var string */
  public $name;
  /** @var string|null */
  public $include;
  /** @var string|null */
  public $exclude;
  /** @var callable|null */
  public $normalizer;
  /** @var string[] */
  public $brands;
  /** @var string|null */
  public $version;

  public static function fromArray(array $pattern)
  {
    return new BrowserPattern($pattern['include'], $pattern['exclude'] ?? null, $pattern['normalizer'] ?? null);
  }

  public function __construct(string $include, $exclude = null, $brands = null, $version = null, $normalizer = null)
  {
    if (isset($exclude) && !is_string($exclude))
    {
      throw new \InvalidArgumentException(sprintf('If set, %s::$exclude must be a string', __CLASS__));
    }

    if (isset($normalizer) && !is_callable($exclude))
    {
      throw new \InvalidArgumentException(sprintf('If set, %s::$normalizer must be a callable', __CLASS__));
    }

    if (isset($brands) && empty($brands))
    {
      throw new \InvalidArgumentException(sprintf('If set, %s::$brands must not be empty', __CLASS__));
    }

    if (isset($normalizer))
    {
      if (!is_callable($normalizer))
      {
        throw new \InvalidArgumentException(sprintf('If set, %s::$normalizer must be a callable', __CLASS__));
      }

      $this->normalizer = $normalizer;
    }

    $this->include = $include;
    $this->exclude = $exclude ?? null;
    $this->brands  = isset($brands) && !is_array($brands) ? [$brands] : $brands;
    $this->version = $version;
  }

  public function isString(string $string, &$match): bool
  {
    if (isset($this->exclude) && preg_match($this->exclude, $string))
    {
      return false;
    }

    if (preg_match($this->include, $string, $match))
    {
      $match = $this->normalize($match);

      return true;
    }

    return false;
  }

  private function normalize($array)
  {
    if (isset($this->normalizer))
    {
      return call_user_func($this->normalizer, $array);
    }
    else
    {
      // Set the brand if we can identify it in the array
      $brand = $array['brand'] ?? (is_numeric($array['0']) ? null : $array['0']);

      // Set the engine if we can identify it in the array
      $engine = $array['engine'] ?? null;

      // Strip brand/engine from resulting array
      $array = array_filter($array, function ($v) use ($brand, $engine) {
        return $v !== $brand && $v !== $engine;
      });

      // Build the version if needed
      if (array_key_exists('version', $array))
      {
        $version = $array['version'];
      }
      elseif (array_key_exists('major', $array))
      {
        $major   = $array['major'];
        $minor   = $array['minor'] ?? null;
        $patch   = $array['patch'] ?? null;
        $build   = $array['build'] ?? null;
        $version = implode('.', [$major, $minor, $patch]);
        $version += !empty($build) ? $build : null;
      }
      else
      {
        // Only take entries that contain at least one number
        $numbers = array_filter($array, function ($v) {
          return preg_match('#[0-9]#', $v);
        });
        $version = !empty($numbers) ? implode('.', $numbers) : null;
      }

      // Combine all known brands, putting class properties first
      $brands = array_merge($this->brands, array_filter([$brand, $engine]));
      // Make parsable brand string
      $brand = implode('|', $brands);

      return [ 0 => $array[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
    }
  }
}
