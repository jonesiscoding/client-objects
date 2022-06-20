<?php
/**
 * BrowserFactory.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Factory;

use DevCoding\Client\Object\Headers\UA;
use DevCoding\Client\Object\Headers\UAFullVersionList;
use DevCoding\Client\Object\Headers\UserAgentString;
use DevCoding\Client\Resolver\Browser\Base\BaseBrowserResolver;
use DevCoding\Client\Resolver\Browser\ChromeResolver;
use DevCoding\Client\Resolver\Browser\EdgResolver;
use DevCoding\Client\Resolver\Browser\FirefoxResolver;
use DevCoding\Client\Resolver\Browser\InternetExplorerResolver;
use DevCoding\Client\Resolver\Browser\OperaMiniResolver;
use DevCoding\Client\Resolver\Browser\OperaMobileResolver;
use DevCoding\Client\Resolver\Browser\OperaResolver;
use DevCoding\Client\Resolver\Browser\SafariResolver;
use DevCoding\CodeObject\Helper\StringHelper;

class BrowserFactory
{
  const RESOLVERS = [
      ChromeResolver::class,
      EdgResolver::class,
      FirefoxResolver::class,
      SafariResolver::class,
      InternetExplorerResolver::class,
      OperaResolver::class,
      OperaMobileResolver::class,
      OperaMiniResolver::class
  ];
  /**
   * @var
   */
  protected $resolvers;

  /**
   * @param string[]|BaseBrowserResolver[] $resolvers
   */
  public function __construct(array $resolvers = self::RESOLVERS)
  {
    $this->resolvers = $resolvers;
  }

  public static function build($criteria = null, $resolvers = self::RESOLVERS)
  {
    $Factory = new BrowserFactory($resolvers);

    if (!isset($criteria))
    {
      return $Factory->fromHeaders();
    }
    elseif ($criteria instanceof UAFullVersionList)
    {
      return $Factory->fromUAFullVersionList($criteria);
    }
    elseif ($criteria instanceof UA)
    {
      return $Factory->fromUA($criteria);
    }
    elseif ($criteria instanceof UserAgentString)
    {
      return $Factory->fromUserAgentString($criteria);
    }
    elseif ($Factory->isStringable($criteria))
    {
      return $Factory->fromString($criteria);
    }

    return null;
  }

  public function fromString(string $string)
  {
    return $this->fromUserAgentString(new UserAgentString($string));
  }

  public function fromUA(UA $UA)
  {
    foreach ($this->getResolvers() as $class)
    {
      /** @var BaseBrowserResolver $Resolver */
      $Resolver = $class instanceof BaseBrowserResolver ? $class : new $class();
      if ($Browser = $Resolver::fromUA($UA))
      {
        return $Browser;
      }
    }

    return null;
  }

  public function fromUAFullVersionList(UAFullVersionList $UA)
  {
    foreach ($this->getResolvers() as $class)
    {
      /** @var BaseBrowserResolver $Resolver */
      $Resolver = $class instanceof BaseBrowserResolver ? $class : new $class();
      if ($Browser = $Resolver::fromUAFullVersionList($UA))
      {
        return $Browser;
      }
    }

    return null;
  }

  public function fromUserAgentString(UserAgentString $UserAgentString)
  {
    foreach ($this->getResolvers() as $class)
    {
      /** @var BaseBrowserResolver $Resolver */
      $Resolver = $class instanceof BaseBrowserResolver ? $class : new $class();
      if ($Browser = $Resolver::fromUserAgentString($UserAgentString))
      {
        return $Browser;
      }
    }

    return null;
  }

  public function fromHeaders()
  {
    foreach ($this->getResolvers() as $class)
    {
      /** @var BaseBrowserResolver $Resolver */
      $Resolver = new $class();
      if ($Browser = $Resolver::fromHeaders())
      {
        return $Browser;
      }
    }

    return null;
  }

  protected function getResolvers()
  {
    return $this->resolvers;
  }

  /**
   * Evaluates whether the given value can be cast to a string without error.
   *
   * @param mixed $val the value to evaluate
   *
   * @return bool TRUE if the value may be safely cast to a string
   */
  protected function isStringable($val)
  {
    return StringHelper::create()->isStringable($val);
  }
}
