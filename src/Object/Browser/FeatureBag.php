<?php
/**
 * FeatureBag.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use Psr\Container\ContainerInterface;

/**
 * Container for holding a collection of BrowserFeature objects
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Client\Factory
 */
class FeatureBag implements ContainerInterface, \JsonSerializable
{
  /** @var BrowserFeature[] */
  protected $features;

  /**
   * @param BrowserFeature[] $features
   */
  public function __construct(array $features)
  {
    $this->features = $features;
  }

  /**
   * @param string $id
   *
   * @return BrowserFeature|null
   */
  public function get(string $id)
  {
    return $this->features[$id] ?? null;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function has(string $id): bool
  {
    return isset($this->features[$id]);
  }

  /**
   * {@inheritDoc}
   */
  public function jsonSerialize()
  {
    return $this->features;
  }
}
