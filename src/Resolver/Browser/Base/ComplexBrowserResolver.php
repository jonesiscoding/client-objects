<?php
/**
 * ComplexBrowserResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser\Base;

use DevCoding\Client\Object\Browser\BrowserImmutable;
use DevCoding\Client\Object\Headers\UserAgentString;

abstract class ComplexBrowserResolver extends BaseBrowserResolver
{
  /**
   * @return BrowserRegex[]
   */
  abstract public function getPatterns();

  /**
   * @param UserAgentString $userAgentString
   *
   * @return bool
   */
  abstract protected function isMobileUserAgentString(UserAgentString $userAgentString): bool;

  public static function fromUserAgentString(UserAgentString $UserAgentString)
  {
    $Resolver = new static();

    foreach ($Resolver->getPatterns() as $RegEx)
    {
      if ($match = $RegEx->match((string) $UserAgentString))
      {
        if ($Resolver->isBrandName($match->brand))
        {
          return new BrowserImmutable(
            $Resolver->getPrimaryBrand(),
            $match->version,
            $Resolver->getEngineBrand(),
            $Resolver->isMobileUserAgentString($UserAgentString)
          );
        }
      }
    }

    return null;
  }
}
