<?php

/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit;

class Utils
{
    /**
     * Remove time and memory limits
     */
    public static function setUnlimitedRuntime(): void
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        set_time_limit(0);
    }
    
    /**
     * Set max upload file size and post size
     * @link https://www.php.net/manual/en/ini.list.php
     * @link https://www.php.net/manual/en/configuration.changes.modes.php
     * @deprecated This setting must be changed at the system level
     * @throws \Exception
     */
    public static function setMaxUploadFileSize(string $size = '2M'): void
    {
        throw new Exception('This setting must be changed at the system level.');
    }
    
    /**
     * Get the Namespace from a class object
     */
    public static function getNamespace(object $class): string
    {
        return (new \ReflectionClass($class))->getNamespaceName();
    }
    
    /**
     * Get the directory from a class object
     */
    public static function getDirname(object $class): string
    {
        return dirname((new \ReflectionClass($class))->getFileName());
    }
    
    /**
     * Return an array of the current memory usage in MB
     */
    public static function getMemoryUsage(float $divider = 1048576.2, string $suffix = ' MB'): array
    {
        return [
            'memory' => (memory_get_usage() / $divider) . $suffix,
            'memoryPeak' => (memory_get_peak_usage() / $divider) . $suffix,
            'realMemory' => (memory_get_usage(true) / $divider) . $suffix,
            'realMemoryPeak' => (memory_get_peak_usage(true) / $divider) . $suffix,
        ];
    }
}
