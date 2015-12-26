<?php

namespace frostealth\yii2\aws\s3;

use Aws\S3\S3Client;
use GuzzleHttp\Psr7;
use Psr\Http\Message\StreamInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Storage
 *
 * @package frostealth\yii2\aws\s3
 */
class Storage extends Component implements StorageInterface
{
    const ACL_PRIVATE = 'private';
    const ACL_PUBLIC_READ = 'public-read';
    const ACL_PUBLIC_READ_WRITE = 'public-read-write';
    const ACL_AUTHENTICATED_READ = 'authenticated-read';
    const ACL_BUCKET_OWNER_READ = 'bucket-owner-read';
    const ALC_BUCKET_OWNER_FULL_CONTROL = 'bucket-owner-full-control';

    /**
     * @var \Aws\Credentials\CredentialsInterface|array|callable
     */
    public $credentials;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $bucket;

    /**
     * @var string
     */
    public $cdnHostname;

    /**
     * @var string
     */
    public $defaultAcl;

    /**
     * @var bool|array
     */
    public $debug;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->credentials)) {
            throw new InvalidConfigException('S3 credentials isn\'t set.');
        }

        if (empty($this->region)) {
            throw new InvalidConfigException('Region isn\'t set.');
        }

        if (empty($this->bucket)) {
            throw new InvalidConfigException('You must set bucket name.');
        }

        if (!empty($this->cdnHostname)) {
            $this->cdnHostname = rtrim($this->cdnHostname, '/');
        }

        $args = $this->prepareArgs($this->options, [
            'version' => '2006-03-01',
            'region' => $this->region,
            'credentials' => $this->credentials,
            'debug' => $this->debug,
        ]);

        $this->client = new S3Client($args);
    }

    /**
     * @return S3Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function put($filename, $data, $acl = null, array $options = [])
    {
        $args = $this->prepareArgs($options, [
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'Body' => $data,
            'ACL' => !empty($acl) ? $acl : $this->defaultAcl,
        ]);

        return $this->execute('PutObject', $args);
    }

    /**
     * @inheritDoc
     */
    public function get($filename, $saveAs = null)
    {
        $args = $this->prepareArgs([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SaveAs' => $saveAs,
        ]);

        return $this->execute('GetObject', $args);
    }

    /**
     * @inheritDoc
     */
    public function exist($filename, array $options = [])
    {
        return $this->getClient()->doesObjectExist($this->bucket, $filename, $options);
    }

    /**
     * @inheritDoc
     */
    public function delete($filename)
    {
        return $this->execute('DeleteObject', [
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getUrl($filename)
    {
        return $this->getClient()->getObjectUrl($this->bucket, $filename);
    }

    /**
     * @inheritDoc
     */
    public function getPresignedUrl($filename, $expires)
    {
        $command = $this->getClient()->getCommand('GetObject', ['Bucket' => $this->bucket, 'Key' => $filename]);
        $request = $this->getClient()->createPresignedRequest($command, $expires);

        return (string)$request->getUri();
    }

    /**
     * @inheritDoc
     */
    public function getCdnUrl($filename)
    {
        return $this->cdnHostname . '/' . $filename;
    }

    /**
     * @inheritDoc
     */
    public function getList($prefix = null, array $options = [])
    {
        $args = $this->prepareArgs($options, [
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
        ]);

        return $this->execute('ListObjects', $args);
    }

    /**
     * @inheritDoc
     */
    public function upload($filename, $source, $acl = null, array $options = [])
    {
        return $this->getClient()->upload(
            $this->bucket,
            $filename,
            $this->toStream($source),
            !empty($acl) ? $acl : $this->defaultAcl,
            $options
        );
    }

    /**
     * @inheritDoc
     */
    public function uploadAsync(
        $filename,
        $source,
        $concurrency = null,
        $partSize = null,
        $acl = null,
        array $options = []
    ) {
        $args = $this->prepareArgs($options, [
            'concurrency' => $concurrency,
            'part_size' => $partSize,
        ]);

        return $this->getClient()->uploadAsync(
            $this->bucket,
            $filename,
            $this->toStream($source),
            !empty($acl) ? $acl : $this->defaultAcl,
            $args
        );
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return \Aws\ResultInterface
     */
    protected function execute($name, array $args)
    {
        $command = $this->getClient()->getCommand($name, $args);

        return $this->getClient()->execute($command);
    }

    /**
     * @param array $a
     *
     * @return array
     */
    protected function prepareArgs(array $a)
    {
        $result = [];
        $args = func_get_args();

        foreach ($args as $item) {
            $item = array_filter($item);
            $result = array_replace($result, $item);
        }

        return $result;
    }

    /**
     * Create a new stream based on the input type.
     *
     * @param resource|string|StreamInterface $source path to a local file, resource or stream
     *
     * @return StreamInterface
     */
    protected function toStream($source)
    {
        if (is_string($source)) {
            $source = Psr7\try_fopen($source, 'r+');
        }

        return Psr7\stream_for($source);
    }
}
