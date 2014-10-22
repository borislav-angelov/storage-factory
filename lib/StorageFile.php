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
 * @version   GIT: 2.7.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'StorageAbstract.php';

/**
 * StorageFile class
 *
 * @category  FileSystem
 * @package   StorageFactory
 * @author    Yani Iliev <yani@iliev.me>
 * @author    Bobby Angelov <bobby@servmask.com>
 * @copyright 2014 Yani Iliev, Bobby Angelov
 * @license   https://raw.github.com/borislav-angelov/storage-factory/master/LICENSE The MIT License (MIT)
 * @version   GIT: 2.7.0
 * @link      https://github.com/borislav-angelov/storage-factory/
 */
class StorageFile extends StorageAbstract
{
    protected $file = null;

    protected $handler = null;

    /**
     * CTOR
     */
    public function __construct($name = null, $path = null, $mode = 'a+') {
        if (empty($name)) {
            $this->file = tempnam($path, null);
        } else {
            $this->file = $path . DIRECTORY_SEPARATOR . $name;
        }

        // Set handler
        $this->handler = fopen($this->file, $mode);
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getName() {
        return basename($this->file);
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getPath() {
        return $this->file;
    }

    /**
     * Get file handler
     *
     * @return resource
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     * Get file size
     *
     * @return integer
     */
    public function getSize() {
        return filesize($this->file);
    }

    /**
     * Get file line
     *
     * @return string
     */
    public function getLine() {
        return fgets($this->handler);
    }

    /**
     * Write file line
     *
     * @return integer
     */
    public function writeLine($line) {
        return fwrite( $this->handler, $line . PHP_EOL);
    }

    /**
     * Set file pointer
     *
     * @return integer
     */
    public function setPointer($offset) {
        return fseek($this->handler, $offset);
    }

    /**
     * Get file pointer
     *
     * @return integer
     */
    public function getPointer() {
        return ftell($this->handler);
    }

    /**
     * Close file
     *
     * @return boolean
     */
    public function close() {
        return fclose($this->handler);
    }

    /**
     * Delete file
     *
     * @return boolean
     */
    public function delete() {
        return unlink($this->file);
    }
}
