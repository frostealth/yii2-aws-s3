<?php

namespace frostealth\yii2\aws\s3\base\commands\traits;

/**
 * Trait Options
 *
 * @package frostealth\yii2\aws\s3\base\commands\traits
 */
trait Options
{
    /** @var array */
    protected $options = [];

    /**
     * @param array $value
     *
     * @return $this
     */
    final public function withOptions(array $value)
    {
        $this->options = $value;

        return $this;
    }

    /**
     * @return array
     */
    final public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    final public function withOption(string $name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }
}
