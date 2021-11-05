<?php

namespace frostealth\yii2\aws\s3\handlers;

use frostealth\yii2\aws\s3\commands\UploadCommand;
use frostealth\yii2\aws\s3\base\handlers\Handler;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

/**
 * Class UploadCommandHandler
 *
 * @package frostealth\yii2\aws\s3\handlers
 */
final class UploadCommandHandler extends Handler
{
    /**
     * @param \frostealth\yii2\aws\s3\commands\UploadCommand $command
     *
     * @return \Aws\ResultInterface|\GuzzleHttp\Promise\PromiseInterface
     */
    public function handle(UploadCommand $command)
    {
        $source = $this->sourceToStream($command->getSource());
        $options = array_filter($command->getOptions());

        $promise = $this->s3Client->uploadAsync(
            $command->getBucket(),
            $command->getFilename(),
            $source,
            $command->getAcl(),
            $options
        );

        return $command->isAsync() ? $promise : $promise->wait();
    }

    /**
     * Create a new stream based on the input type.
     *
     * @param resource|string|StreamInterface $source path to a local file, resource or stream
     *
     * @return StreamInterface
     */
    protected function sourceToStream($source): StreamInterface
    {
        if (is_string($source)) {
            $source = Utils::tryFopen($source, 'r+');
        }

        return Utils::streamFor($source);
    }
}
