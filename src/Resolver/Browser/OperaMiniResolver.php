<?php
/**
 * OperaMiniResolver.php
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

class OperaMiniResolver extends ComplexBrowserResolver
{
  /**
   * @return string
   */
  public function getPrimaryBrand(): string
  {
    return 'Opera';
  }

  /**
   * @return string
   */
  public function getEngineBrand()
  {
    return 'Opera Mini';
  }

  public function getPatterns()
  {
    $mini = new BrowserRegex(
      '#(Opera)\/[9|10]\.[80|50|61].*Mini\/((\d+)\.(\d+)(?:\.(\d+))?)#',
      null,
      function ($uam) {
        $brand   = 'Opera Mini';
        $version = $uam[2];

        return [0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    $mini98 = new BrowserRegex(
      '#(Opera Mini)\/9\.80.*(Mobi|J2ME)\/((\d+)\.(\d+)(?:\.(\d+))?)#',
      null,
      function ($uam) {
        $brand   = 'Opera Mini';
        $version = $uam[3];
        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    $mini9 = new BrowserRegex(
      '#(Opera Mini)\/(9)\s#',
      null,
      function ($uam) {
        $brand   = 'Opera Mini';
        $version = $uam[2];
        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    return [$mini, $mini98, $mini9];
  }

  protected function isMobileUserAgentString(UserAgentString $userAgentString): bool
  {
    return true;
  }
}
