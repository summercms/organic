<?php

namespace MockingMagician\Organic;


class DirectoryObject extends AbstractInode
{

    /**
     * @param string $path
     * @param Permission $permission
     *
     * @return InodeInterface the created Inode
     */
    public static function create(string $path, Permission $permission): InodeInterface
    {
        // TODO: Implement create() method.
    }

    /**
     * Delete the inode. An inode is a file or a directory.
     *
     * @return bool in case of success
     */
    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }
}