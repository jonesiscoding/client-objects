<?php

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Object\Browser\Brand;
use DevCoding\Client\Object\Browser\Browser;
use DevCoding\Client\Object\Browser\BrowserFeature;
use DevCoding\Client\Object\Headers\UA;
use DevCoding\Client\Object\Headers\UAFullVersionList;


class BrowserResolver
{
  /** @var BrowserPattern[] */
  public $patterns;
  /** @var string[] */
  public $brands;
  /** @var BrowserFeature[] */
  public $features;
  /** @var string */
  public $agent;

  /**
   * @param array $config
   *
   * @return BrowserResolver
   */
  public static function fromArray(array $config): BrowserResolver
  {
    $patterns = [];
    if (isset($config['patterns']))
    {
      foreach ($config['patterns'] as $pattern)
      {
        $patterns[] = BrowserPattern::fromArray($pattern);
      }
    }

    $name     = $config['name']     ?? 'Browser';
    $brands   = $config['brands']   ?? [$name];
    $features = $config['features'] ?? [];

    return new BrowserResolver($brands, $patterns, $features);
  }

  /**
   * @param string[]         $brands
   * @param BrowserPattern[] $patterns
   * @param array            $features
   */
  public function __construct(array $brands, array $patterns, array $features)
  {
    $this->brands   = $brands;
    $this->patterns = $patterns;
    $this->features = $features;
  }

  /**
   * @param string $string
   *
   * @return Browser|null
   */
  public function resolve(string $string)
  {
    if (preg_match('#^"([^"]+)";v="([^"]+)",#', $string, $match))
    {
      $object = false === strpos('.', $match[1]) ? new UA($string) : new UAFullVersionList($string);

      foreach ($this->brands as $brand)
      {
        if ($object->isBrandName($brand))
        {
          $brands = $object->getBrands();
          $brands = array_walk($brands, function (Brand $v) {
            return $v->getName();
          });

          return new Browser($brands, $object->getVersion(), $this->features);
        }
      }

      return null;
    }
    else
    {
      return $this->resolveLegacy($string);
    }
  }

  protected function resolveLegacy(string $string)
  {
    foreach ($this->patterns as $pattern)
    {
      if ($pattern->isString($string, $match))
      {
        $brands  = !empty($match['brand']) ?? explode('|', $match->brand);
        $version = $match['version']       ?? null;

        return new Browser($brands, $version, $this->features);
      }
    }

    return null;
  }
}
