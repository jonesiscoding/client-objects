<?php
/**
 * GreaseTrait.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Headers;

/**
 * Trait for creation of "grease" for Sec-CH-UA-Full-Version-List and Sec-CH-UA headers.
 * @see https://wicg.github.io/ua-client-hints/#grease
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Client\Factory
 */
trait GreaseTrait
{
  /**
   * Creates a faux grease brand list for Chrome.
   *
   * @param bool $full      Use full version numbers in the string
   * @param int  $maxMajor  Maximum major version to use in the string
   *
   * @return string         The faux grease string
   */
  protected function getBrands(bool $full = false, int $maxMajor = 96)
  {
    $bVer = $this->getGreaseVersion($full, $maxMajor);

    return sprintf(
      '"Chrome"; v="%s", %s, "Chromium"; v="%s"',
      $bVer,
      $this->getGrease($full, abs($bVer / 2)),
      $bVer
    );
  }

  /**
   * Returns a grease string with a single browser name & version.
   *
   * @param bool $full Use full version numbers in the string
   * @param int $maxMajor Maximum major version to use in the string
   *
   * @return string
   */
  protected function getGrease(bool $full = false, int $maxMajor = 96)
  {
    $symbols = ['(', ')', ';', '#', ':', ' '];

    return sprintf(
      '%s%s%sBrowser"; v="%s"',
      array_rand($symbols),
      array_rand(['What', 'Cool', 'Not', 'Fun', 'Super', 'Your']),
      array_rand($symbols),
      $this->getGreaseVersion($full)
    );
  }

  /**
   * Returns a randomized version number appropriate for a grease string.
   *
   * @param bool $full Use full version numbers in the string
   * @param int $maxMajor Maximum major version to use in the string
   *
   * @return int|string
   */
  protected function getGreaseVersion(bool $full = false, int $maxMajor = 96)
  {
    return $full ? sprintf('%s.%s.%s', rand(1, $maxMajor), rand(1, 10), rand(1, 10)) : rand(1, $maxMajor);
  }
}
