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
 * @version   GIT: 1.0.0
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
 * @version   GIT: 1.0.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */
class StorageDirectory extends StorageAbstract
{
    protected $directory = null;

    /**
     * CTOR
     */
    public function __construct() {
        $this->directory = $this->getRootPath() . DIRECTORY_SEPARATOR . uniqid();
        mkdir($this->directory);
    }

    /**
     * Get a file or directory as resource
     *
     * @param  string Get a file or directory as resource or absolute path
     * @return mixed
     */
    public function getAs($type = 'string') {
        if ( $type === 'string' ) {
            return $this->directory;
        } else {
            throw new Exception( 'Unable to retrieve directory as ' . $type . '. Make sure the method is implemented' );
        }
    }

    /**
     * Get storage absolute path
     *
     * @return mixed
     */
    public function getRootPath() {
        if (defined('STORAGE_PATH') && $this->isAccessible(STORAGE_PATH)) {
            return STORAGE_PATH;
        } else if ($this->isAccessible(sys_get_temp_dir())) {
            return sys_get_temp_dir();
        } else {
            throw new Exception('Storage directory is not accessible (read/write).');
        }
    }

    /**
     * Delete a file or directory
     *
     * @return string
     */
    public function delete() {
        return rmdir( $this->directory );
    }

}
