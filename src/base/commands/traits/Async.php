<?php

namespace frostealth\yii2\aws\s3\base\commands\traits;

/**
 * Trait Async
 *
 * @package frostealth\yii2\aws\s3\base\commands\traits
 */
trait Async
{
    /** @var bool */
    private $isAsync = false;

    /**
     * @param bool $async
     *
     * @return $this
     */
    final public function async(bool $async = true)
    {
        $this->isAsync = $async;

        return $this;
    }

    /**
     * @return bool
     */
    final public function isAsync(): bool
    {
        return $this->isAsync;
    }
}
