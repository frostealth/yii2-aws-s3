<?php

namespace frostealth\yii2\components\s3;

/**
 * Interface StorageInterface
 *
 * @package frostealth\yii2\components\s3
 */
interface StorageInterface
{
    /**
     * @param string $filename
     * @param string $saveAs
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function get($filename, $saveAs = null);

    /**
     * @param string $filename
     * @param mixed $data
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function put($filename, $data, $acl = null, array $options = []);

    /**
     * @param string $filename
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function delete($filename);

    /**
     * @param string $filename
     * @param string $expires
     * @param array $options
     *
     * @return string
     */
    public function getUrl($filename, $expires = null, array $options = []);

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getCdnUrl($filename);

    /**
     * @param string $prefix
     *
     * @return \Guzzle\Service\Resource\ResourceIteratorInterface
     */
    public function getList($prefix);

    /**
     * @param string $filename
     * @param mixed $source
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function upload($filename, $source, $acl = null, array $options = []);

    /**
     * @param string $filename
     * @param mixed $source
     * @param int $concurrency
     * @param int $minPartSize
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function multipartUpload(
        $filename,
        $source,
        $concurrency = 3,
        $minPartSize = null,
        $acl = null,
        array $options = []
    );
}
