<?php
/**
 * UAFullVersionList.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Headers;

use DevCoding\Client\Object\Version\ClientVersion;
use DevCoding\Client\Object\Browser\Brand;

class UAFullVersionList extends UA
{
  /**
   * @return ClientVersion|null
   */
  public function getVersion()
  {
    return $this->getCommonVersion();
  }

  public function getBrands()
  {
    if (!isset($this->brands))
    {
      if ($m = $this->getBrandMatches($this->string))
      {
        $this->brands = [];
        $versions     = [];
        foreach ($m as $set)
        {
          $versions[trim($set['brand'])] = $set['version'];
        }
        $brands = Brand::sort(array_keys($versions));

        foreach ($brands as $brand)
        {
          $this->brands[] = new Brand($brand, new ClientVersion($versions[$brand]));
        }
      }
    }

    return $this->brands;
  }
}
