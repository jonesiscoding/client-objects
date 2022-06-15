<?php
/**
 * Pointer.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Hardware;

/**
 * Object representing the pointer on a device.
 *
 * @package DevCoding\Object\System
 */
class Pointer
{
  const FINE         = 'fine';
  const COARSE       = 'coarse';
  const INCONCLUSIVE = 'inconclusive';

  /** @var string */
  protected $type;
  /** @var bool */
  protected $touch;
  /** @var bool */
  protected $metro;

  public function __construct($type, $touch = true, $metro = false)
  {
    $this->type  = $type;
    $this->touch = $touch;
    $this->metro = $metro;
  }

  /**
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  public function isCoarse()
  {
    return 'coarse' === $this->getType();
  }

  public function isFine()
  {
    return 'fine' === $this->getType();
  }

  /**
   * @return bool
   */
  public function isTouch()
  {
    return $this->touch;
  }

  public function isMetro()
  {
    return $this->metro;
  }
}
