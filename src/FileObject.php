<?php

/**
 * @author Marc MOREAU <moreau.marc.web@gmail.com>
 * @license https://github.com/MockingMagician/organic/blob/master/LICENSE.md CC-BY-SA-4.0
 * @link https://github.com/MockingMagician/organic/blob/master/README.md
 */

namespace MockingMagician\Organic;

use MockingMagician\Organic\Exception\FileAlreadyExistException;
use MockingMagician\Organic\Exception\FileDeleteException;
use MockingMagician\Organic\Exception\FilePathException;
use MockingMagician\Organic\Permission\Permission;
use MockingMagician\Organic\Permission\PermissionFactory;

class FileObject extends AbstractInode implements IOFileAwareInterface
{
    public function __construct(string $path)
    {
        parent::__construct($path);
        if (!$this->isFile()) {
            throw new FilePathException($this->getPath());
        }
    }

    /**
     * Delete the inode. An inode is a file or a directory.
     *
     * @throws FileDeleteException
     *
     * @return bool in case of success
     */
    public function delete(): bool
    {
        try {
            \unlink($this->getPath());
        } catch (\Throwable $e) {
            throw new FileDeleteException($this->getPath(), $e);
        }

        return true;
    }

    /**
     * @param string $path
     * @param $permissions
     *
     * @throws FileAlreadyExistException
     * @throws FilePathException
     *
     * @return FileObject|InodeInterface
     */
    public static function create(string $path, Permission $permissions = null): InodeInterface
    {
        if (\file_exists($path)) {
            throw new FileAlreadyExistException($path);
        }

        if (null === $permissions) {
            $permissions = PermissionFactory::defaultFile();
        }

        \file_put_contents($path, '', LOCK_EX);
        \chmod($path, $permissions->getMode());

        return new static($path);
    }

    /**
     * Get an interface for IO on file
     *
     * @param string $openMode
     *
     * @throws \Exception
     *
     * @return IOFileInterface
     */
    public function getIO(string $openMode = 'r'): IOFileInterface
    {
        return new IOFile($this->getPath(), $openMode);
    }
}
