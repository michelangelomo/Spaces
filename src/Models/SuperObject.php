<?php

namespace Michelangelo\Spaces\Models;


abstract class SuperObject {

    private $key, $lastModified, $eTag, $size, $storageClass;

    /**
     * File constructor.
     * @param string $key
     * @param string $lastModified
     * @param string $eTag
     * @param int $size
     * @param string $storageClass
     */
    public function __construct(string $key, string $lastModified, string $eTag, int $size, string $storageClass) {
        $this->key = $key;
        $this->lastModified = $lastModified;
        $this->eTag = $eTag;
        $this->size = $size;
        $this->storageClass = $storageClass;
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getLastModified(): string {
        return $this->lastModified;
    }

    /**
     * @return string
     */
    public function getETag(): string {
        return $this->eTag;
    }

    /**
     * @return int
     */
    public function getSize(): int {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getStorageClass(): string {
        return $this->storageClass;
    }

    /**
     * @param $object
     * @return mixed
     */
    public static function fromObject($object){
        $class = get_called_class();
        return new $class($object['Key'],
            $object['LastModified'],
            $object['ETag'],
            $object['Size'],
            $object['StorageClass']
        );
    }

}