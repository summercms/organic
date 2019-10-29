<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/organic/blob/master/LICENSE.md CC-BY-SA-4.0
 * @link https://github.com/MockingMagician/organic/blob/master/README.md
 */

namespace MockingMagician\Organic;

use MockingMagician\Organic\Exception\FilePathException;
use MockingMagician\Organic\Exception\InodeCreateLinkException;
use MockingMagician\Organic\Exception\InodeMoveToException;

abstract class AbstractInode extends FileInfo implements InodeInterface
{
    /**
     * @param string $path
     *
     * @throws InodeMoveToException
     * @throws Exception\FilePathException
     *
     * @return InodeInterface the moved file
     */
    public function moveTo(string $path): InodeInterface
    {
        \clearstatcache(true, $path);
        if (\file_exists($path)) {
            throw new InodeMoveToException(
                $this->getPath(),
                $path,
                'A file or directory with same name already exist'
            );
        }

        try {
            \rename($this->getPath(), $path);
        } catch (\Throwable $e) {
            throw new InodeMoveToException($this->getPath(), $path, $e->getMessage());
        }

        $this->__construct($path);

        return $this;
    }

    /**
     * Create a symlink.
     *
     * @param string $path
     *
     * @throws InodeCreateLinkException
     * @throws FilePathException
     *
     * @return InodeInterface
     */
    public function createLink(string $path): InodeInterface
    {
        \clearstatcache(true, $path);
        if (\file_exists($path)) {
            throw new InodeCreateLinkException(
                $this->getPath(),
                $path,
                'A file or directory with same name already exist'
            );
        }

        try {
            \symlink($this->getPath(), $path);
        } catch (\Throwable $e) {
            throw new InodeCreateLinkException($this->getPath(), $path, $e->getMessage());
        }

        return new static($path);
    }

    /**
     * @param string     $path
     * @param Permission $permission
     *
     * @return InodeInterface the created Inode
     */
    abstract public static function create(string $path, Permission $permission): InodeInterface;

    /**
     * Delete the inode. An inode is a file or a directory.
     *
     * @return bool in case of success
     */
    abstract public function delete(): bool;
}
