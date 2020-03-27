<?php
declare(strict_types=1);

namespace App\Helpers;

class ConfigHelper
{
    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     * @todo: implement nested arrays supporting
     */
    public static function get(string $key, $default = null)
    {
        $keyParts = explode('.', $key);
        $fileName = $keyParts[0];

        if (!$fileName) {
            throw new \UnexpectedValueException('File name is empty in the config key.');
        }

        $config = include BASE_PATH . '/config/' . $fileName . '.php';
        $section = $keyParts[1];

        if (empty($config[$section])) {
            return $default;
        }

        return $config[$section];
    }
}
