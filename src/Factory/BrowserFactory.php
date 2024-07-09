<?php
/**
 * BrowserFactory.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Factory;

use DevCoding\Client\Object\Browser\Browser;
use DevCoding\Client\Resolver\Browser\BrowserResolver;

/**
 * Factory for the building of Browser objects
 *
 * @package DevCoding\Client\Factory
 */
class BrowserFactory
{
  /** @var BrowserResolver[] */
  protected $resolvers;

  /**
   * Instantiates a BrowserFactory object from a configuration array.  See BrowserResolver::fromArray
   *
   * @param array $config
   *
   * @return BrowserFactory
   */
  public static function fromConfig(array $config)
  {
    $resolvers = [];
    foreach ($config as $value)
    {
      $resolvers[] = BrowserResolver::fromArray($value);
    }

    return new BrowserFactory($resolvers);
  }

  /**
   * Instantiates a BrowserFactory instance using the given array of BrowserResolver objects.
   *
   * @param BrowserResolver[] $resolvers
   */
  public function __construct(array $resolvers)
  {
    $this->resolvers = $resolvers;
  }

  /**
   * Build a Browser object from the given string. which can be a Sec-CH-UA-Full-Version-List header, Sec-CH-UA header,
   * or a legacy User Agent string.
   *
   * @param string $string
   *
   * @return Browser|null
   */
  public function build(string $string)
  {
    foreach ($this->resolvers as $resolver)
    {
      if ($browser = $resolver->resolve($string))
      {
        return $browser;
      }
    }

    return null;
  }
}
