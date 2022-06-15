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

  /**
   * @return array|Brand[]
   */
  public function getBrands()
  {
    if (!isset($this->brands))
    {
      if ($m = $this->getBrandMatches($this->string))
      {
        $this->brands = [];
        foreach ($m as $set)
        {
          $version        = new ClientVersion($set['version']);
          $this->brands[] = new Brand(trim($set['brand']), $version);
        }
      }
    }

    return $this->brands;
  }
}
