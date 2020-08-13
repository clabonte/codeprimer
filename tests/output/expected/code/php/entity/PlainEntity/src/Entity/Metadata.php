<?php
/*
 * This file has been generated by CodePrimer.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodePrimer\Tests\Entity;

/**
 * Class Metadata
 * Variable set of extra information
 * @package CodePrimer\Tests\Entity
 */
class Metadata
{
    /** @var string The name to uniquely identify this metadata */
    protected $name = '';

    /** @var string The value associated with this metadata */
    protected $value = '';

    /**
     * Metadata default constructor
     * @var string $name The name to uniquely identify this metadata
     * @var string $value The value associated with this metadata
     */
    public function __construct(
        string $name,
        string $value
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @codeCoverageIgnore
     * @param string $name
     * @return Metadata
     */
    public function setName(string $name): Metadata
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @codeCoverageIgnore
     * @param string $value
     * @return Metadata
     */
    public function setValue(string $value): Metadata
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}
