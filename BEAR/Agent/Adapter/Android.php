<?php
/**
 * BEAR
 *
 * PHP versions 5
 *
 * @category   BEAR
 * @package    BEAR_Agent
 * @subpackage Adapter
 * @author     Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright  2008-2017 Akihito Koriyama All rights reserved.
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link       https://github.com/bearsaturday
 */

/**
 * Androidエージェントアダプター
 *
 * @category   BEAR
 * @package    BEAR_Agent
 * @subpackage Adapter
 * @author     Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright  2008-2017 Akihito Koriyama All rights reserved.
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link       https://github.com/bearsaturday
 */
class BEAR_Agent_Adapter_Android extends BEAR_Agent_Adapter implements BEAR_Agent_Adapter_Interface
{
    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $contentType = isset($this->_config['content_type']) ? $this->_config['content_type'] : 'text/html';
        $this->_config['agent_filter'] = true;
        $this->_config['header'] = 'Content-Type: ' . $contentType . '; charset=utf-8';
        $this->_config['charset'] = 'utf-8';
        $this->_config['enable_js'] = true;
        $this->_config['role'] = array(BEAR_Agent::UA_ANDROID, BEAR_Agent::UA_DEFAULT);
    }
}
