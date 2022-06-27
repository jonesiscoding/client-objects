<?php
/**
 * EdgeResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Resolver\Browser\Base\SimpleBrowserResolver;

/**
 * Class EdgeResolver.
 *
 * @package DevCoding\Factory\Browser
 */
class EdgeResolver extends SimpleBrowserResolver
{
  public function getEngineBrand()
  {
    return null;
  }

  public function getPrimaryBrand(): string
  {
    return 'Microsoft Edge';
  }

  public function getIncludePattern()
  {
    return '#(?<brand>Edge)\/(?<version>[0-9_.]+)#';
  }

  public function getExcludePattern()
  {
    return '#bot#i';
  }

  public function getMobilePattern()
  {
    return '#(Windows Mobile)#';
  }

  public function isBrandName($name)
  {
    return 'Edge' === $name || parent::isBrandName($name);
  }
}
