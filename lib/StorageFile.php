<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * StorageFile class
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
 * @version   GIT: 1.0.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */

/**
 * StorageFile class
 *
 * @category  FileSystem
 * @package   StorageFactory
 * @author    Yani Iliev <yani@iliev.me>
 * @author    Bobby Angelov <bobby@servmask.com>
 * @copyright 2014 Yani Iliev, Bobby Angelov
 * @license   https://raw.github.com/borislav-angelov/storage-factory/master/LICENSE The MIT License (MIT)
 * @version   GIT: 1.0.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */
class StorageFile implements StorageInterface
{
    protected $file = null;

    public function __construct() {
        if ($this->isAccessible($this->getRootPath())) {
            $this->file = tempnam($this->getRootPath(), 'sm_');
        } else if ($this->isAccessible(sys_get_temp_dir())) {
            $this->file = tempnam(sys_get_temp_dir(), 'sm_');
        } else {
            throw new Exception( 'Storage directory is not accessible (read/write).' );
        }
    }

    /**
     * Get absolute file path or file resource
     *
     * @return mixed
     */
    public function getAs( $type = 'resource' ) {
        return ($type === 'resource' ? fopen($this->file, 'w+') : $this->file);
    }

    /**
     * Get storage absolute path
     *
     * @return mixed
     */
    public function getRootPath() {
        if (defined('STORAGE_PATH')) {
            return realpath(STORAGE_PATH);
        }
    }

    /**
     * Delete file
     *
     * @return boolean
     */
    public function delete() {
        return @unlink($this->file);
    }

    /**
     * Is path accessible (read/write)
     *
     * @param  string  Absolute path
     * @return boolean Path is accessible or not
     */
    protected function isAccessible($path) {
        return is_readable($path) && is_writable($path);
    }
}
