<?php

namespace CodePrimer\Helper;

use CodePrimer\Model\Field;
use CodePrimer\Model\Package;

class FieldHelper
{
    /**
     * Checks if a field should contain a UUID value.
     */
    public function isUuid(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::UUID);
    }

    /**
     * Checks if a field should be stored as a string.
     */
    public function isString(Field $field): bool
    {
        $result = false;

        switch (strtolower($field->getType())) {
            case FieldType::STRING:
            case FieldType::TEXT:
            case FieldType::UUID:
            case FieldType::EMAIL:
            case FieldType::URL:
            case FieldType::PASSWORD:
            case FieldType::PHONE:
            case FieldType::RANDOM_STRING:
                $result = true;
                break;
        }

        return $result;
    }

    /**
     * Checks if a field should be stored as a Date only.
     */
    public function isDate(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::DATE);
    }

    /**
     * Checks if a field should be stored as a Time only.
     */
    public function isTime(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::TIME);
    }

    /**
     * Checks if a field should be stored as a Date and Time.
     */
    public function isDateTime(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::DATETIME);
    }

    /**
     * Checks if a field should be stored as a bool.
     */
    public function isBoolean(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::BOOL) || 0 === strcasecmp($field->getType(), FieldType::BOOLEAN);
    }

    /**
     * Checks if a field should be stored as binary blob or byte array.
     */
    public function isBinary(Field $field): bool
    {
        // TODO: Implement isBinary() method.
        return false;
    }

    /**
     * Checks if a field should be stored as an 4-byte integer.
     */
    public function isInteger(Field $field): bool
    {
        $type = strtolower($field->getType());

        return (FieldType::INT == $type) || (FieldType::INTEGER == $type);
    }

    /**
     * Checks if a field should be stored as a 8-byte long.
     */
    public function isLong(Field $field): bool
    {
        $type = strtolower($field->getType());

        return (FieldType::LONG == $type) || (FieldType::ID == $type);
    }

    /**
     * Checks if a field should contain be stored as a 4-byte float.
     */
    public function isFloat(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::FLOAT);
    }

    /**
     * Checks if a field should contain be stored as a 8-byte double.
     */
    public function isDouble(Field $field): bool
    {
        $type = strtolower($field->getType());

        return (FieldType::DOUBLE == $type) || (FieldType::DECIMAL == $type) || (FieldType::PRICE == $type);
    }

    /**
     * Checks if a field represents an identifier (e.g. primary key in a relational database).
     */
    public function isIdentifier(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::ID) || 0 === strcasecmp($field->getType(), FieldType::UUID);
    }

    /**
     * Checks if a field represents an auto-incremented value.
     */
    public function isAutoIncrement(Field $field): bool
    {
        return 0 === strcasecmp($field->getType(), FieldType::ID);
    }

    /**
     * Checks if a field represents a known Entity in a given package.
     */
    public function isEntity(Field $field, Package $package): bool
    {
        $result = false;

        $entity = $package->getEntity($field->getType());
        if (isset($entity)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Checks if a field represents a managed datetime field used to automatically track the time at which an entity has
     * been created.
     */
    public function isEntityCreatedTimestamp(Field $field): bool
    {
        $result = false;

        if ($this->isDateTime($field) && $field->isManaged()) {
            $name = strtolower($field->getName());
            if ('created' === substr($name, 0, strlen('created'))) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Checks if a field represents a managed datetime field used to automatically track the time at which an entity has
     * been updated last
     * Checks if a field represents a managed datetime field used to track the entity creation timestamp.
     */
    public function isEntityUpdatedTimestamp(Field $field): bool
    {
        $result = false;

        if ($this->isDateTime($field) && $field->isManaged()) {
            $name = strtolower($field->getName());
            if ('updated' === substr($name, 0, strlen('updated'))) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Checks if a given field stores only ASCII characters.
     */
    public function isAsciiString(Field $field): bool
    {
        $result = false;

        switch ($field->getType()) {
            case FieldType::UUID:
            case FieldType::EMAIL:
            case FieldType::URL:
            case FieldType::PHONE:
                $result = true;
                break;
        }

        return $result;
    }
}
