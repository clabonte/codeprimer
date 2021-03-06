<?php

namespace CodePrimer\Adapter;

use CodePrimer\Model\BusinessBundle;
use CodePrimer\Model\BusinessModel;
use CodePrimer\Model\Dataset;
use CodePrimer\Model\Field;
use CodePrimer\Model\Relationship;
use CodePrimer\Model\RelationshipSide;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use RuntimeException;

class DatabaseAdapter
{
    /** @var Inflector */
    protected $inflector;

    public function __construct()
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    /**
     * Extracts the name of Package and transforms it to its database name equivalent.
     * Converts 'dbName', 'db-name' and 'db name' to 'db_name'.
     *
     * @return string
     */
    public function getDatabaseName(BusinessBundle $businessBundle)
    {
        $dbName = $businessBundle->getNamespace().' '.$businessBundle->getName();
        $name = str_replace(['-', ' ', '.'], '_', $this->inflector->singularize($dbName));
        $name = $this->inflector->tableize($name);
        $name = str_replace('__', '_', $name);

        return $name;
    }

    /**
     * Extracts the name of an  business model and transforms it to its table name equivalent.
     * Converts 'tableName', 'table-name' and 'table name' to 'table_names'.
     *
     * @param $model BusinessModel|Dataset
     */
    public function getTableName($model): string
    {
        $name = str_replace(['-', ' ', '.'], '_', $this->inflector->pluralize($model->getName()));
        $name = $this->inflector->tableize($name);
        $name = str_replace('__', '_', $name);

        return $name;
    }

    /**
     * Extracts the name of a relation table based on the entities that are part of the relation.
     */
    public function getRelationTableName(RelationshipSide $relation): string
    {
        if (Relationship::MANY_TO_MANY != $relation->getRelationship()->getType()) {
            throw new RuntimeException('Relation tables can only be created for many-to-many relationships');
        }
        $leftSide = $relation->getRelationship()->getLeftSide();
        $rightSide = $relation->getRelationship()->getRightSide();

        return $this->getTableName($leftSide->getBusinessModel()).'_'.$this->getTableName($rightSide->getBusinessModel());
    }

    /**
     * Extracts the name of an  business model and transforms it to its audit table name equivalent.
     * Converts 'tableName', 'table-name' and 'table name' to 'table_names_logs'.
     */
    public function getAuditTableName(BusinessModel $businessModel): string
    {
        return $this->getTableName($businessModel).'_logs';
    }

    /**
     * Extracts the name of a field and transforms it to its column name equivalent.
     * Converts 'fieldName', 'field-name' and 'field name' to 'field_name'.
     */
    public function getColumnName(Field $field): string
    {
        $name = str_replace(['-', ' ', '.'], '_', $field->getName());
        $name = $this->inflector->tableize($name);
        $name = str_replace('__', '_', $name);

        return $name;
    }

    /**
     * Extracts the name to use as a column to represent an  business model.
     */
    public function getBusinessModelColumnName(BusinessModel $businessModel): string
    {
        $name = str_replace(['-', ' ', '.'], '_', $businessModel->getName());
        $name = $this->inflector->tableize($name).'_id';
        $name = str_replace('__', '_', $name);

        return $name;
    }
}
