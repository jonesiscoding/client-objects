<?php
/**
 * BrowserFeatureFactory.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Factory;

use DevCoding\Client\Object\Browser\BrowserFeature;
use DevCoding\Client\Object\Browser\FeatureBag;
use DevCoding\Parser\CanIUse\Feature;
use DevCoding\Parser\CanIUse\FeatureFactory;
use DevCoding\Parser\CanIUse\StatCollection;

/**
 * Factory for the building of BrowserFeature objects
 *
 * @package DevCoding\Client\Factory
 */
class BrowserFeatureFactory
{
  /** @var FeatureFactory */
  protected $factory;
  /** @var Feature[] */
  protected $features = [];

  // region //////////////////////////////////////////////// Instantiation Functions

  /**
   * @param string $path Absolute path to the fyrd/caniuse data
   */
  public function __construct(string $path)
  {
    $this->factory = new FeatureFactory($path.'/features-json');
  }

  // endregion ///////////////////////////////////////////// End Instantiation Functions

  // region //////////////////////////////////////////////// Public Functions

  /**
   * Builds a FeatureBag of BrowserFeature objects from the given array of feature names and the given agent name.
   *
   * @param string[] $features   Array of short feature names from the "Can I Use" data.
   * @param string   $agentName  An agent name from the "Can I Use" data, typically supplied by a BrowserFactory config.
   *
   * @return FeatureBag                 The FeatureBag object, loaded with the applicable BrowserFeature objects
   * @throws \InvalidArgumentException  If a requested feature is not found in the "Can I Use" data
   */
  public function buildBag(array $features, string $agentName)
  {
    $data = [];
    foreach ($features as $feature)
    {
      $data[$feature] = $this->build($feature, $agentName);
    }

    return new FeatureBag($data);
  }

  /**
   * Builds a BrowserFeature object using the given feature key and agent name.
   *
   * @param string $featureName Short feature key from the "Can I Use" data
   * @param string $agentName   Short agent name from the "Can I Use" data
   *
   * @return BrowserFeature             The resulting BrowserFeature object
   * @throws \InvalidArgumentException  If the given feature name is not found in the "Can I Use" data
   */
  public function build(string $featureName, string $agentName)
  {
    $stats = $this->getStatsForAgent($featureName, $agentName);

    if (empty($stats))
    {
      throw new \InvalidArgumentException("Could not load '$featureName' for '$agentName'.");
    }

    return new BrowserFeature($featureName, $stats->getSupported(), $stats->getPrefix());
  }

  // endregion ///////////////////////////////////////////// End Public Functions

  // region //////////////////////////////////////////////// Helper Functions

  /**
   * Returns a Parser\CanIUse\StatCollection object for the given feature name and agent name.
   *
   * @param string $featureName Short feature key from the "Can I Use" data
   * @param string $agentName   Short agent name from the "Can I Use" data
   *
   * @return StatCollection|null  The resulting StatCollection object
   */
  protected function getStatsForAgent(string $featureName, string $agentName)
  {
    return $this->getStats($featureName)[$agentName] ?? null;
  }

  /**
   * Returns an array of Parser\CanIUse\StatCollection objects for the given feature name.
   *
   * @param string $featureName
   *
   * @return StatCollection[]|null
   */
  protected function getStats(string $featureName)
  {
    return $this->getFeature($featureName)->getStats();
  }

  /**
   * Returns a Parser\CanIUse\Feature object for the given feature name.
   *
   * @param string $featureName
   *
   * @return Feature
   */
  protected function getFeature(string $featureName): Feature
  {
    if (!isset($this->features[$featureName]))
    {
      $this->features[$featureName] = $this->factory->build($featureName);
    }

    return $this->features[$featureName];
  }
}
