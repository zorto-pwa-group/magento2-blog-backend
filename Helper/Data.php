<?php
/**
 * Copyright ZT. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 */

namespace ZT\Blog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * ZT Blog Helper
 */
class Data extends AbstractHelper
{
    /**
     * Retrieve translated & formated date
     * @param string $format
     * @param string $dateOrTime
     * @return string
     */
    public function getTranslatedDate($format, $dateOrTime)
    {
        $time = is_numeric($dateOrTime) ? $dateOrTime : strtotime($dateOrTime);
        $month = ['F' => '%1', 'M' => '%2'];

        foreach ($month as $from => $to) {
            $format = str_replace($from, $to, $format);
        }

        $date = date($format, $time);

        foreach ($month as $to => $from) {
            $date = str_replace($from, __(date($to, $time)), $date);
        }

        return $date;
    }

    /**
     * Retrieve store config value
     * @param string $path
     * @param null $storeId
     * @return mixed
     */
    public function getConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Generate URL key from string
     * @param $string
     * @return string
     */
    public function urlKeyGeneration($string){
        $url = preg_replace('#[^0-9a-z]+#i', '-', $string);
        $lastCharTitle = substr($string, -1);
        $lastUrlChar = substr($url, -1);
        if ($lastUrlChar == "-" && $lastCharTitle != "-"){
            $url = substr($url, 0, strlen($url) - 1);
        }
        $urlKey = strtolower($url) . ".html";
        return $urlKey;
    }
}
