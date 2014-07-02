<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * StorageDirectory class
 *
 * PHP version 5
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category  FileSystem
 * @package   StorageFactory
 * @author    Yani Iliev <yani@iliev.me>
 * @author    Bobby Angelov <bobby@servmask.com>
 * @copyright 2014 Yani Iliev, Bobby Angelov
 * @license   https://raw.github.com/borislav-angelov/storage-factory/master/LICENSE The MIT License (MIT)
 * @version   GIT: 2.2.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'StorageAbstract.php';

/**
 * StorageDirectory class
 *
 * @category  FileSystem
 * @package   StorageFactory
 * @author    Yani Iliev <yani@iliev.me>
 * @author    Bobby Angelov <bobby@servmask.com>
 * @copyright 2014 Yani Iliev, Bobby Angelov
 * @license   https://raw.github.com/borislav-angelov/storage-factory/master/LICENSE The MIT License (MIT)
 * @version   GIT: 2.2.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */
class StorageDirectory extends StorageAbstract
{
    protected $directory = null;

    /**
     * CTOR
     */
    public function __construct($name = null) {
        if (empty($name)) {
            $this->directory = $this->getRootPath() . DIRECTORY_SEPARATOR . uniqid() . DIRECTORY_SEPARATOR;
        } else {
            $this->directory = $this->getRootPath() . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
        }

        // Create directory
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }

    /**
     * Get directory name
     *
     * @return string
     */
    public function getName() {
        return $this->directory;
    }

    /**
     * Get directory resource
     *
     * @return resource
     */
    public function getResource() {
        return opendir($this->directory);
    }

    /**
     * Delete directory
     *
     * @return boolean
     */
    public function delete() {
        // Remove child files and directories
        if (self::flush($this->directory)) {
            return rmdir($this->directory);
        }
    }

    /**
     * Recursive copy directory from source to destination path
     *
     * @param  string $from    From absolute path
     * @param  string $to      To absolute path
     * @param  array  $exclude Exclude files and directories
     * @return boolean
     */
    public static function copy($from, $to, $exclude = array()) {
        if (!self::isAccessible($from)) {
            throw new Exception('From path is not accessible (read/write).');
        }

        if (!self::isAccessible($to)) {
            throw new Exception('To path is not accessible (read/write).');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($from),
            RecursiveIteratorIterator::SELF_FIRST
        );

        // Prepare filter pattern
        $filter_pattern = null;
        if (is_array($exclude)) {
            $filters = array();
            foreach ($exclude as $filter) {
                $filters[] = sprintf(
                    '(%s(%s.*)?)',
                    preg_quote($filter, '/'),
                    preg_quote(DIRECTORY_SEPARATOR, '/')
                );
            }

            $filter_pattern = implode('|', $filters);
        }

        foreach ($iterator as $item) {
            // Skip dots
            if ($iterator->isDot()) {
                continue;
            }

            // Validate filter pattern
            if ($filter_pattern) {
                if (preg_match('/^' . $filter_pattern . '$/', $iterator->getSubPathName())) {
                    continue;
                }
            }

            if ($item->isDir()) {
                mkdir($to . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $to . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

        return true;
    }

    /**
     * Recursive delete directory
     *
     * @param  string  $path    Absolute path
     * @param  array   $exclude Exclude files and directories
     * @return boolean
     */
    public static function flush($path, $exclude = array()) {
        if (!self::isAccessible($path)) {
            throw new Exception('Path is not accessible (read/write).');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        // Prepare filter pattern
        $filter_pattern = null;
        if (is_array($exclude)) {
            $filters = array();
            foreach ($exclude as $filter) {
                $filters[] = sprintf(
                    '(%s(%s.*)?)',
                    preg_quote($filter, '/'),
                    preg_quote(DIRECTORY_SEPARATOR, '/')
                );
            }

            $filter_pattern = implode('|', $filters);
        }

        foreach ($iterator as $item) {
            // Skip dots
            if ($iterator->isDot()) {
                continue;
            }

            // Validate filter pattern
            if ($filter_pattern) {
                if (preg_match('/^' . $filter_pattern . '$/', $iterator->getSubPathName())) {
                    continue;
                }
            }

            if ($item->isDir()) {
                rmdir($path . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                unlink($path . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

        return true;
    }

}
