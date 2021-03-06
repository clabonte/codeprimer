<?php

namespace CodePrimer\Model\Data;

use CodePrimer\Model\BusinessModel;
use CodePrimer\Model\Field;
use InvalidArgumentException;

class Data
{
    // Constants used to specify the level of details associated with a given, non-native field
    /** @var string Only include a reference to the BusinessModel or Dataset associated with a given field */
    const REFERENCE = 'reference';
    /** @var string Only include the attributes of the BusinessModel or Dataset associated with a given field */
    const ATTRIBUTES = 'attributes';
    /** @var string Include the attributes and direct relations of the BusinessModel associated with a given field */
    const FULL = 'full';

    /** @var BusinessModel */
    private $businessModel;

    /** @var Field */
    private $field;

    /** @var string One of the following constants: REFERENCE, BASIC, FULL */
    private $details;

    /** @var string The name associated with this data (e.g. variable) based on its usage/location context */
    private $name;

    /** @var string|null A description of this data. If not set, the field's description will be used */
    private $description = null;

    /**
     * Data constructor.
     *
     * @param Field|string $field
     * @param string       $details The level of details to associate with a non-native field (e.g. BusinessModel or Dataset)
     *
     * @throws InvalidArgumentException If the details provided is not valid
     */
    public function __construct(BusinessModel $businessModel, $field, string $details = self::REFERENCE)
    {
        $realField = $this->validate($businessModel, $field, $details);

        $this->businessModel = $businessModel;
        $this->field = $realField;
        $this->details = $details;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getBusinessModel(): BusinessModel
    {
        return $this->businessModel;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getName(): string
    {
        if (null === $this->name) {
            return $this->field->getName();
        }

        return $this->name;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setName(string $name): Data
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string A description of this data, or the associated field's description if one is not provided
     */
    public function getDescription(): string
    {
        if (null === $this->description) {
            return $this->field->getDescription();
        }

        return $this->description;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setDescription(string $description): Data
    {
        $this->description = $description;

        return $this;
    }

    public function isSame(Data $otherData): bool
    {
        $result = true;

        if ($this->businessModel->getName() !== $otherData->getBusinessModel()->getName()) {
            $result = false;
        } elseif ($this->field->getName() !== $otherData->getField()->getName()) {
            $result = false;
        } elseif ($this->details !== $otherData->getDetails()) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param Field|string $requestedField
     *
     * @throws InvalidArgumentException If any field is not valid
     */
    private function validate(BusinessModel $businessModel, $requestedField, string $details): Field
    {
        $name = $requestedField;
        if ($requestedField instanceof Field) {
            $name = $requestedField->getName();
        } elseif (!is_string($requestedField)) {
            throw new InvalidArgumentException('Requested field must be either of type Field or string');
        }
        $field = $businessModel->getField($name);
        if (null == $field) {
            throw new InvalidArgumentException('Requested field '.$name.' is not defined in BusinessModel '.$businessModel->getName());
        }

        switch ($details) {
            case self::ATTRIBUTES:
            case self::REFERENCE:
            case self::FULL:
                break;
            default:
                throw new InvalidArgumentException('Invalid details provided: '.$details.'. Must be one of: attributes, reference or full');
        }

        return $field;
    }
}
