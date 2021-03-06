<?php
/**
 * BEAR
 *
 * PHP versions 5
 *
 * @category  BEAR
 * @package   BEAR_View
 * @author    Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright 2008-2017 Akihito Koriyama  All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link      https://github.com/bearsaturday
 */

/**
 * ビュー
 *
 * @category  BEAR
 * @package   BEAR_View
 * @author    Akihito Koriyama <akihito.koriyama@gmail.com>
 * @copyright 2008-2017 Akihito Koriyama  All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version    @package_version@
 * @link      https://github.com/bearsaturday
 *
 * @Singleton
 *
 * @config string adapter     ビューアダプタークラス
 * @config bool   ua_sniffing UAスニッフィング？
 */
class BEAR_View extends BEAR_Factory
{
    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * テンプレート名の取得
     *
     * @return array
     *
     * @todo Smarty以外のViewアダプタ
     */
    public function factory()
    {
        $options = $this->_config['enable_ua_sniffing'] ? array('injector' => 'onInjectUaSniffing') : array();
        // 'BEAR_View_Smarty_Adapter' as default
        return BEAR::factory($this->_config['adapter'], $this->_config, $options);
    }
}
