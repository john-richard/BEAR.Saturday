<?php
/**
 * BEAR
 *
 * PHP versions 5
 *
 * @category  BEAR
 * @package   BEAR_Util
 * @author    Akihito Koriyama <koriyama@users.sourceforge.jp>
 * @copyright 2008 Akihito Koriyama  All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version   SVN: Release: $Id: Util.php 1302 2009-12-22 03:37:43Z koriyama@users.sourceforge.jp $
 * @link      http://api.bear-project.net/BEAR_Util/BEAR_Util.html
 */
/**
 * ユーティリティクラス
 *
 * <pre>
 * デバックモードの時のみ使用するクラス群です。
 * フレームワークが使用しています。
 * </pre>
 *
 * @category  BEAR
 * @package   BEAR_Util
 * @author    Akihito Koriyama <koriyama@users.sourceforge.jp>
 * @copyright 2008 Akihito Koriyama  All rights reserved.
 * @license   http://opensource.org/licenses/bsd-license.php BSD
 * @version   SVN: Release: $Id: Util.php 1302 2009-12-22 03:37:43Z koriyama@users.sourceforge.jp $
 * @link      http://api.bear-project.net/BEAR_Util/BEAR_Util.html
 */
final class BEAR_Util
{

    /**
     * 再帰でファイルリストを得る
     *
     * @param string $path ファイルまたはディレクトリパス
     *
     * @return array
     */
    public static function getFilesList($path)
    {
        static $_files = array();
        $files = 0;
        $dir = opendir($path);
        while (($file = readdir($dir)) !== false) {
            if ($file[0] == '.') {
                continue;
            }
            if (strpos($path . DIRECTORY_SEPARATOR . $file, '__bear') !== false) {
                continue;
            }
            $fileParts = explode('.', $path . DIRECTORY_SEPARATOR . $file);
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                //dir
                self::getFilesList($path . DIRECTORY_SEPARATOR . $file);
            } else {
                //file
                if (array_pop($fileParts) !== 'php') {
                    continue;
                }
                $fullPath = $path . DIRECTORY_SEPARATOR . $file;
                $_files[] = str_replace(_BEAR_APP_HOME, '', $fullPath);
            }
        }
        closedir($dir);
        return $_files;
    }

    /**
     * 全てのキャッシュをクリア
     *
     * @return void
     */
    public static function clearAllCache($showMessage = true)
    {
        $app = BEAR::get('app');
        if ($app['core']['debug'] !== true) {
            return;
        }
        BEAR::factory('BEAR_Dev_Shell')->clearCache();
        if ($showMessage) {
        	Panda::message('Cache Clear', '全てのキャッシュファイルとキャッシュメモリをクリアしました');
        }
    }

    /**
     * ステータスを表示
     *
     * @return void
     *
     * @ignore
     */
    public static function printStatus($msg, $color = '#dddddd"')
    {
        $app = BEAR::get('app');
        if ($app['core']['debug'] !== true) {
            return;
        }
        static $y = 0;
        print '<div name="name!" style="font-size: 9px; position: absolute;';
        print " top: {$y}px; left: 0px; text-align: left padding:";
        print '5px 3px 3px 3px;background-color:orange;color:white;';
        print "border: 1px solid {$color}\">{$msg}</div>";
        $y += 20;
    }

    /**
     * 必須項目アサーション
     *
     * 連想配列に指定のキー配列が全て含まれてるか検査し、問題があれば指定したコードの例外を投げます。
     *
     * <code
     * // $valuesには'id'と'name'がなければいけない
     * BEAR_Util::required(array('id', 'name'), $values);
     * </code>
     * @param array $required 必須項目
     * @param array $values   入力項目
     *
     * @return void
     *
     * @throws BEAR_Exception 必須項目が足りない場合の例外
     */
    public static function required(array $required, $values, $msg = '', $code = BEAR::CODE_BAD_REQUEST)
    {
        if (count(array_intersect($required, array_keys($values))) != count($required)) {
            $info = array('required' => $required, 'values' => $values);
            $msg = "Required Exception";
            throw new BEAR_Exception($msg, array('info' => $info));
        }
    }

    /**
     * 再帰配列マージ（ディスクティンティブ）
     *
     * @param array $array
     * @param array $array
     *
     * @return array
     *
     * @ignore
     *
     * @link http://www.php.net/manual/ja/function.array-merge-recursive.php
     */
    public static function &arrayMergeRecursiveDistinct()
    {
        $aArrays = func_get_args();
        $aMerged = $aArrays[0];
        for ($i = 1; $i < count($aArrays); $i++) {
            if (is_array($aArrays[$i])) {
                foreach ($aArrays[$i] as $key => $val) {
                    if (is_array($aArrays[$i][$key])) {
                        $aMerged[$key] = is_array($aMerged[$key]) ? BEAR::arrayMergeRecursiveDistinct($aMerged[$key], $aArrays[$i][$key]) : $aArrays[$i][$key];
                    } else {
                        $aMerged[$key] = $val;
                    }
                }
            }
        }
        return $aMerged;
    }

    /**
     * アンシリアライズ
     *
     * @deprecated 環境で動作が不安定
     *
     * @param mixed $ser アンシリアライズするデータ
     *
     * @return string
     */
    public static function unserialize($data)
    {
        trigger_error('BEAR_Util::unserialize deprecated', E_USER_DEPRECATED);
    	return unserialize($data);
//        ini_set('unserialize_callback_func', 'BEAR_Autoload');
//        $data = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $data);
//        return unserialize($data);
    }

    /**
     * get_object_varsの再帰版
     *
     * @param mixed $data 入力データ
     *
     * @return string
     */
    public static function getObjectVarsRecursive($data) {
           if (is_object($data)) {
                   foreach (get_object_vars($data) as $key => $val) {
                           $ret[$key] = self::getObjectVarsRecursive($val);
                   }
                   return $ret;
           } elseif (is_array($data)) {
                   foreach ($data as $key => $val) {
                           $ret[$key] = self::getObjectVarsRecursive($val);
                   }
                   return $ret;
           } else {
                   return $data;
           }
    }

    /**
     * ファイルを指定ディレクトリ下で再帰的に消去
     *
     * @param $dir　　　　　　　ディレクトリパス
     * @param $deleteRootToo 指定ディレクトリも消去するか
     *
     * @return void
     */
    public static function unlinkRecursive($dir, $deleteRootToo = false)
    {
        $dh = opendir($dir);
        if(!$dh) {
            return;
        }
        while (false !== ($obj = readdir($dh))) {
            if(substr($obj, 0, 1) == '.'){
                continue;
            }
            if (!@unlink($dir . '/' . $obj)){
                self::unlinkRecursive($dir.'/'.$obj, true);
            }
        }
        closedir($dh);
        if ($deleteRootToo)
        {
            rmdir($dir);
        }
    }
}