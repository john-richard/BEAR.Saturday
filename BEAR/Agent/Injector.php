<?php
/**
 * BEAR
 *
 * PHP versions 5
 *
 * @category  BEAR
 * @package   BEAR_Agent
 * @author    Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright 2008-2017 Akihito Koriyama All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link      https://github.com/bearsaturday
 */

/**
 * UAインジェクター
 *
 * @category  BEAR
 * @package   BEAR_Agent
 * @author    Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright 2008-2017 Akihito Koriyama All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link      https://github.com/bearsaturday
 */
class BEAR_Agent_Injector implements BEAR_Injector_Interface
{
    /**
     *　Inject
     *
     * @param BEAR_Agent &$object BEAR_Agentオブジェクト
     * @param array $config
     *
     * @return void
     */
    public static function inject($object, $config)
    {
        $agent = BEAR::dependency('BEAR_Agent');
        $config = $agent->adapter->getConfig();
        $role = $config['role'];
        foreach ($role as $agent) {
            $method = 'onInject' . $agent;
            if (method_exists($object, $method)) {
                $object->$method();

                return;
            }
        }
        $object->onInject();
    }
}
