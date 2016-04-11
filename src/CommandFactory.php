<?php

namespace frostealth\yii2\aws\s3;

use frostealth\yii2\aws\s3\commands\DeleteCommand;
use frostealth\yii2\aws\s3\commands\ExistCommand;
use frostealth\yii2\aws\s3\commands\GetCommand;
use frostealth\yii2\aws\s3\commands\GetPresignedUrlCommand;
use frostealth\yii2\aws\s3\commands\GetUrlCommand;
use frostealth\yii2\aws\s3\commands\PutCommand;
use frostealth\yii2\aws\s3\commands\RestoreCommand;
use frostealth\yii2\aws\s3\commands\UploadCommand;
use frostealth\yii2\aws\s3\interfaces;

/**
 * Class CommandFactory
 *
 * @package frostealth\yii2\aws\s3
 */
class CommandFactory
{
    /** @var \frostealth\yii2\aws\s3\interfaces\CommandBuilder */
    protected $builder;

    /**
     * CommandFactory constructor.
     *
     * @param \frostealth\yii2\aws\s3\interfaces\CommandBuilder $builder
     */
    public function __construct(interfaces\CommandBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $filename
     *
     * @return \frostealth\yii2\aws\s3\commands\GetCommand
     */
    public function get(string $filename): GetCommand
    {
        /** @var GetCommand $command */
        $command = $this->builder->build(GetCommand::class);
        $command->setFilename($filename);

        return $command;
    }

    /**
     * @param string $filename
     * @param mixed  $body
     *
     * @return \frostealth\yii2\aws\s3\commands\PutCommand
     */
    public function put(string $filename, $body): PutCommand
    {
        /** @var PutCommand $command */
        $command = $this->builder->build(PutCommand::class);
        $command->setFilename($filename)->setBody($body);

        return $command;
    }

    /**
     * @param string $filename
     *
     * @return \frostealth\yii2\aws\s3\commands\DeleteCommand
     */
    public function delete(string $filename): DeleteCommand
    {
        /** @var DeleteCommand $command */
        $command = $this->builder->build(DeleteCommand::class);
        $command->setFilename($filename);

        return $command;
    }

    /**
     * @param string $filename
     * @param mixed  $source
     *
     * @return \frostealth\yii2\aws\s3\commands\UploadCommand
     */
    public function upload(string $filename, $source): UploadCommand
    {
        /** @var UploadCommand $command */
        $command = $this->builder->build(UploadCommand::class);
        $command->setFilename($filename)->setSource($source);

        return $command;
    }

    /**
     * @param string $filename
     * @param int    $days      lifetime of the active copy in days
     *
     * @return \frostealth\yii2\aws\s3\commands\RestoreCommand
     */
    public function restore(string $filename, int $days): RestoreCommand
    {
        /** @var RestoreCommand $command */
        $command = $this->builder->build(RestoreCommand::class);
        $command->setFilename($filename)->setDays($days);

        return $command;
    }

    /**
     * @param string $filename
     *
     * @return \frostealth\yii2\aws\s3\commands\ExistCommand
     */
    public function exist(string $filename): ExistCommand
    {
        /** @var ExistCommand $command */
        $command = $this->builder->build(ExistCommand::class);
        $command->setFilename($filename);

        return $command;
    }

    /**
     * @param string $filename
     *
     * @return \frostealth\yii2\aws\s3\commands\GetUrlCommand
     */
    public function getUrl(string $filename): GetUrlCommand
    {
        /** @var GetUrlCommand $command */
        $command = $this->builder->build(GetUrlCommand::class);
        $command->setFilename($filename);

        return $command;
    }

    /**
     * @param string $filename
     * @param mixed  $expires
     *
     * @return \frostealth\yii2\aws\s3\commands\GetPresignedUrlCommand
     */
    public function getPresignedUrl(string $filename, $expires): GetPresignedUrlCommand
    {
        /** @var GetPresignedUrlCommand $command */
        $command = $this->builder->build(GetPresignedUrlCommand::class);
        $command->setFilename($filename)->setExpires($expires);

        return $command;
    }
}
