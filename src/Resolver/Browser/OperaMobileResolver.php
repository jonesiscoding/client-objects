<?php
/**
 * OperaMobileResolver.php
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

class OperaMobileResolver extends ComplexBrowserResolver
{
  /**
   * @return string
   */
  public function getPrimaryBrand(): string
  {
    return 'Opera Mobile';
  }

  /**
   * @return string|null;
   */
  public function getEngineBrand()
  {
    return null;
  }

  public function getPatterns()
  {
    $safari = new BrowserRegex(
      '#(?:Mobile Safari).*(OPR)\/(\d+)\.(\d+)\.(\d+)#',
      '#bot#i',
      function ($uam) {
        $version = implode('.', [$uam[3], $uam[4]]);
        $brand   = 'Safari';

        return [0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    // The 'Opera/x.xx' stopped at 9.80, then the version went into the 'Version/x.x.x' format.
    $o98Mobi = new BrowserRegex(
      '#(Opera)\/9.80.*Opera Mobi/.*Version\/((\d+)\.(\d+)(?:\.(\d+))?)#',
      null,
      function ($uam) {
        $version = implode('.', [$uam[3], $uam[4]]);
        $brand   = 'Presto';

        return [0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    // Versions Before the 9.80 lockdown
    $o6Mobi = new BrowserRegex(
      '#Opera Mobi/.*Opera\s(([0-9]+)\.?([0-9]*))#',
      null,
      function ($uam) {
        $version = implode('.', [$uam[2], $uam[3]]);
        $brand   = 'Presto';

        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    $oMobi = new BrowserRegex(
      '#Opera Mobi#',
      null,
      function ($uam) {
        $brand   = 'Opera Mobile';
        $version = 6.0; // Earliest release
        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    return [$safari, $o98Mobi, $o6Mobi, $oMobi];
  }

  /**
   * @param string $name
   *
   * @return bool
   */
  public function isBrandName($name)
  {
    return 'Safari' === $name || 'Presto' === $name || parent::isBrandName($name);
  }

  protected function isMobileUserAgentString(UserAgentString $userAgentString): bool
  {
    return true;
  }
}
