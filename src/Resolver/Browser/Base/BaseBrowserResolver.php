<?php
/**
 * BaseBrowserResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser\Base;

use DevCoding\Client\Object\Browser\BrowserImmutable;
use DevCoding\Client\Object\Headers\HeaderBag;
use DevCoding\Client\Object\Headers\UA;
use DevCoding\Client\Object\Headers\UAFullVersionList;
use DevCoding\Client\Object\Headers\UserAgentString;

abstract class BaseBrowserResolver implements ClientUAMatchInterface
{
  abstract public static function fromUserAgentString(UserAgentString $UserAgentString);

  /**
   * @return BrowserImmutable|null
   */
  public static function fromHeaders()
  {
    $Resolver  = new static();
    $HeaderBag = new HeaderBag();
    $isMobile  = $HeaderBag->resolve('Sec-CH-UA-Mobile');

    if ($fullVersionList = $HeaderBag->resolve('Sec-CH-UA-Full-Version-List'))
    {
      // If we have a full version list, that has everything we need
      return BaseBrowserResolver::fromUAFullVersionList($fullVersionList, $isMobile);
    }
    elseif ($uaHint = $HeaderBag->resolve('Sec-CH-UA'))
    {
      // And a full version, use them.  This header is deprecated, so we might never see it.
      if ($v = $HeaderBag->resolve('Sec-CH-UA-Full-Version'))
      {
        $UA = new UA($uaHint);

        foreach ($Resolver->getBrands() as $brandName)
        {
          if (!$UA->isBrandName($brandName))
          {
            return null;
          }
        }

        return new BrowserImmutable($Resolver->getBrands(), $v, $isMobile);
      }

      // Otherwise, just use the UA, which includes the major version anyway.
      return SimpleBrowserResolver::fromUA($uaHint, $isMobile);
    }
    elseif ($userAgentString = $HeaderBag->resolve(UserAgentString::HEADERS))
    {
      return SimpleBrowserResolver::fromUserAgentString($userAgentString);
    }

    return null;
  }

  /**
   * @param UA|string $obj_or_string
   * @param bool      $mobile
   *
   * @return BrowserImmutable|null
   */
  public static function fromUA($obj_or_string, $mobile = false)
  {
    $BM  = new static();
    $CUA = $obj_or_string instanceof UA ? $obj_or_string : new UA($obj_or_string);

    foreach ($BM->getBrands() as $brandName)
    {
      if (!$CUA->isBrandName($brandName))
      {
        return null;
      }
    }

    return new BrowserImmutable($BM->getBrands(), $CUA->getVersion(), $mobile);
  }

  public static function fromUAFullVersionList($fullVersionList, $mobile = false)
  {
    $Resolver    = new static();
    $VersionList = new UAFullVersionList($fullVersionList);

    foreach ($Resolver->getBrands() as $brandName)
    {
      if (!$VersionList->isBrandName($brandName))
      {
        return null;
      }
    }

    return new BrowserImmutable($Resolver->getBrands(), $VersionList->getVersion(), $mobile);
  }

  /**
   * @param $name
   *
   * @return bool
   */
  public function isBrandName($name)
  {
    return strtolower($this->getBrands()[0]) == trim(strtolower($name));
  }
}
