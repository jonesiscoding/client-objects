<?php
/**
 * SafariResolver.php
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

class SafariResolver extends ComplexBrowserResolver
{
  public function getBrands()
  {
    return ['Safari'];
  }

  public function getPatterns()
  {
    $exclude = '#(PhantomJS|Silk|rekonq|OPR|Chrome|Android|Edge|bot)#';
    $regex[] = new BrowserRegex(
      '#(Version)\/(\d+)\.(\d+)(?:\.(\d+))?.*Safari\/#',
      $exclude,
      function ($uam) {
        // Safari Newer Versions
        $major = $uam[2] ?? null;
        $minor = $uam[3] ?? null;
        $patch = $uam[4] ?? null;
        $fVers = implode('.', [$major, $minor, $patch]);

        return [0 => $uam[0], 1 => 'Safari', 'brand' => 'Safari', 2 => $fVers, 'version' => $fVers];
      }
    );

    // Chrome, Edge, or Firefox on iOS
    $regex[] = new BrowserRegex($this->getMobilePattern(), $exclude);

    // Versions below 3.x do not have the Version/ in the UA.  Since we can't get the version number, we go with the
    // highest version that didn't have the version number in the UA.  It doesn't really matter, since this would be
    // a fairly feature-less browser by modern standards anyway.
    $regex[] = new BrowserRegex('#(Safari)\/\d+#', $exclude, function ($uam) {
      return [0 => $uam[0], 1 => 'Safari', 'brand' => 'Safari', 2 => '2.0.4', 'version' => '2.0.4'];
    });

    return $regex;
  }

  protected function isMobileUserAgentString(UserAgentString $userAgentString): bool
  {
    return $userAgentString->isMatch($this->getMobilePattern());
  }

  public function getMobilePattern()
  {
    return '#(?<brand>CriOS|EdgiOS|FxiOS)\/(?<version>[0-9\.]+)#';
  }
}
