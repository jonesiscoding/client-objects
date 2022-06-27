<?php
/**
 * EdgResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

class EdgResolver extends ChromeResolver
{
  public function getPrimaryBrand(): string
  {
    return 'Microsoft Edge';
  }

  public function getIncludePattern()
  {
    return '#(?<brand>Edg|EdgA)\/(?<version>[0-9\.]+)#';
  }

  public function getExcludePattern()
  {
    return str_replace(['Edg|', 'Edg)'], ['', ')'], parent::getExcludePattern());
  }

  public function isBrandName($name)
  {
    return 'Edg' === $name || 'EdgA' === $name || parent::isBrandName($name);
  }
}

