<?php

namespace Michelangelo\Spaces;

use Aws\S3\S3Client;
use Michelangelo\Spaces\Models\Directory;
use Michelangelo\Spaces\Models\File;

class Spaces {

    const endpoint = '.digitaloceanspaces.com';

    protected $region, $space, $client;

    /**
     * Spaces constructor.
     * @param string $accessKey
     * @param string $secretKey
     * @param string $region
     * @param string $spaceName
     * @throws \Exception
     */
    public function __construct(string $accessKey, string $secretKey, string $region, string $spaceName) {
        $this->region = $region;
        $this->space = $spaceName;

        $endpoint = "https://" . ((!empty($spaceName)) ? "$spaceName." : "") . "$region" . self::endpoint;
        //Init client
        try {
            $this->client = new S3Client([
                'region' => $region,
                'version' => 'latest',
                'endpoint' => $endpoint,
                'credentials' => array(
                    'key'    => $accessKey,
                    'secret' => $secretKey,
                ),
                'bucket_endpoint' => true,
                'signature_version' => 'v4-unsigned-body'
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param string $dir
     * @return array
     */
    public function objects(string $dir = '') : array {
        $objects = $this->client->getIterator('ListObjects', array(
            'Bucket' => $this->space,
            "Prefix" => $dir,
        ));

        $objectArray = [];
        foreach ($objects as $object) {
            $key = $object['Key'];
            self::endsWith($key, '/') ?
                $objectArray[] = Directory::fromObject($object)
                : $objectArray[] = File::fromObject($object);
        }
        return $objectArray;
    }

    /*
     * Utility
     */
    private static  function endsWith($haystack, $needle) {
        $length = strlen($needle);
        return $length === 0 ||
            (substr($haystack, -$length) === $needle);
    }
}