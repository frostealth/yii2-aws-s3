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
    public function get($filename, $saveAs = '');

    /**
     * @param string $filename
     * @param mixed $data
     * @param array $metadata
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function put($filename, $data, array $metadata = []);

    /**
     * @param string $filename
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function delete($filename);

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getUrl($filename);

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
     * @param string $sourceFile
     * @param array $metadata
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putFile($filename, $sourceFile, array $metadata = []);
}
