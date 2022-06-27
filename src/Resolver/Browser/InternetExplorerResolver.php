<?php
/**
 * InternetExplorerResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Object\Headers\UserAgentString;
use DevCoding\Client\Resolver\Browser\Base\BrowserRegex;
use DevCoding\Client\Resolver\Browser\Base\ComplexBrowserResolver;

class InternetExplorerResolver extends ComplexBrowserResolver
{
  public function getPrimaryBrand(): string
  {
    return 'Internet Explorer';
  }

  public function getEngineBrand()
  {
    return null;
  }

  /**
   * @param UserAgentString $userAgentString
   *
   * @return bool
   */
  protected function isMobileUserAgentString(UserAgentString $userAgentString): bool
  {
    return $userAgentString->isMatch($this->getMobilePattern());
  }

  /**
   * @return BrowserRegex[]
   */
  public function getPatterns()
  {
    $exclude = '#(bot)#i';
    return [
        new BrowserRegex('#(?<brand>Trident)\/[0-9]\.[0-9];[^rv]*rv:((?<version>[0-9._]+))#', $exclude),
        new BrowserRegex('#(?<brand>MSIE)\s*(?<version>[0-9_.]+)[^;]*;#', $exclude),
    ];
  }

  /**
   * @param string $name
   *
   * @return bool
   */
  public function isBrandName($name)
  {
    return 'Trident' === $name || 'MSIE' === $name || parent::isBrandName($name);
  }

  /**
   * @return string
   */
  public function getMobilePattern()
  {
    return '#(Windows Phone|IEMobile|MSIEMobile|Windows CE)#i';
  }
}
