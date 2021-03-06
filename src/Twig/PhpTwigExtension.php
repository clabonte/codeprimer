<?php

namespace CodePrimer\Twig;

use CodePrimer\Helper\EventHelper;
use CodePrimer\Helper\FieldHelper;
use CodePrimer\Helper\PriceHelper;
use CodePrimer\Model\BusinessBundle;
use CodePrimer\Model\BusinessModel;
use CodePrimer\Model\Data\Data;
use CodePrimer\Model\Data\EventData;
use CodePrimer\Model\Dataset;
use CodePrimer\Model\Derived\Event;
use CodePrimer\Model\Field;
use InvalidArgumentException;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * Class PhpTwigExtension.
 *
 * @author Christian Labonté
 */
class PhpTwigExtension extends LanguageTwigExtension
{
    public function getFilters(): array
    {
        $filters = parent::getFilters();

        $filters[] = new TwigFilter('hint', [$this, 'hintFilter'], ['is_safe' => ['html'], 'needs_context' => true]);
        $filters[] = new TwigFilter('namespace', [$this, 'namespaceFilter'], ['is_safe' => ['html'], 'needs_context' => true]);
        $filters[] = new TwigFilter('value', [$this, 'valueFilter'], ['is_safe' => ['html'], 'needs_context' => true]);

        return $filters;
    }

    public function getTests(): array
    {
        $tests = parent::getTests();

        $tests[] = new TwigTest('dateTimeUsed', [$this, 'dateTimeUsed']);

        return $tests;
    }

    /**
     * Checks if the DateTime type is used/required for a given entity.
     *
     * @param BusinessModel|Event $obj
     */
    public function dateTimeUsed($obj): bool
    {
        $result = false;

        $fieldHelper = new FieldHelper();

        if ($obj instanceof BusinessModel || $obj instanceof Dataset) {
            foreach ($obj->getFields() as $field) {
                if ($fieldHelper->isDateTime($field) || $fieldHelper->isDate($field) || $fieldHelper->isTime($field)) {
                    $result = true;
                    break;
                }
            }
        } elseif ($obj instanceof Event) {
            $eventHelper = new EventHelper();
            $list = $eventHelper->getNamedData($obj);
            foreach ($list as $data) {
                if ($fieldHelper->isDateTime($data->getField()) || $fieldHelper->isDate($data->getField()) || $fieldHelper->isTime($data->getField())) {
                    $result = true;
                    break;
                }
            }
        } else {
            throw new InvalidArgumentException('dateTimeUsed() PHP filter only support BusinessModel and Event');
        }

        return $result;
    }

    /**
     * Filters a string to transform it to its variable name. Converts 'table_names' to '$tableName'.
     *
     * @param mixed $obj
     */
    public function variableFilter($obj): string
    {
        $name = $this->getName($obj);
        if (is_string($name) && !empty($name)) {
            return '$'.parent::variableFilter($obj);
        }

        return $name;
    }

    /**
     * Filters a string to transform it to its member name. Converts 'table_names' to '$this->tableName'.
     *
     * @param mixed $obj
     */
    public function memberFilter($obj): string
    {
        $name = $this->getName($obj);
        if (is_string($name) && !empty($name)) {
            return '$this->'.parent::memberFilter($obj);
        }

        return $name;
    }

    /**
     * Filters a string to transform it to a namespace path. Converts 'Com.Folder.A' or 'Com/Folder/A' to 'Com\Folder\A'.
     *
     * @param array                 $context
     * @param string|BusinessBundle $obj
     */
    public function namespaceFilter($context, $obj, string $subpackage = null): string
    {
        $str = '';

        if ($obj instanceof BusinessBundle) {
            $str = $obj->getNamespace();
        } elseif (is_string($obj)) {
            $str = $obj;
        }

        if (null !== $subpackage) {
            if (!empty($subpackage)) {
                $str .= '\\'.$subpackage;
            }
        } elseif (!empty($context['subpackage'])) {
            $str .= '\\'.$context['subpackage'];
        }

        if (!empty($str)) {
            return str_replace(['.', '/', ' '], '\\', $str);
        }

        return '';
    }

    /**
     * @param string|Field|Data $obj
     * @param bool              $mandatory Whether the field is mandatory in this context
     */
    public function typeFilter(array $context, $obj, bool $mandatory = false): string
    {
        $helper = new FieldHelper();

        if ($obj instanceof Data) {
            $field = $obj->getField();
            if (($obj instanceof EventData) && !$mandatory) {
                $mandatory = $obj->isMandatory();
            }
        } else {
            $field = $obj;
        }

        $type = 'string';
        if ($field instanceof Field) {
            if ($field->isList()) {
                $type = 'array';
            } elseif ($helper->isBoolean($field)) {
                $type = 'bool';
            } elseif ($helper->isDate($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isDateTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isInteger($field)) {
                $type = 'int';
            } elseif ($helper->isLong($field)) {
                $type = 'int';
            } elseif ($helper->isFloat($field)) {
                $type = 'float';
            } elseif ($helper->isPrice($field)) {
                $type = 'float';
            } elseif ($helper->isDouble($field)) {
                $type = 'double';
            } elseif ($helper->isString($field)) {
                $type = 'string';
            } elseif (isset($context['package'])) {
                /** @var BusinessBundle $businessBundle */
                $businessBundle = $context['package'];
                if ($helper->isBusinessModel($field, $businessBundle)) {
                    $type = $field->getType();
                } elseif ($helper->isDataset($field, $businessBundle)) {
                    $type = $field->getType();
                }
            }

            if (!$mandatory && !$field->isMandatory()) {
                $type = '?'.$type;
            }
        }

        return $type;
    }

    /**
     * @param array             $context
     * @param string|Field|Data $obj
     */
    public function listTypeFilter($context, $obj): string
    {
        $helper = new FieldHelper();

        if ($obj instanceof Data) {
            $field = $obj->getField();
        } else {
            $field = $obj;
        }

        $type = 'string';
        if ($field instanceof Field) {
            if ($helper->isBoolean($field)) {
                $type = 'bool';
            } elseif ($helper->isDate($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isDateTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isInteger($field)) {
                $type = 'int';
            } elseif ($helper->isLong($field)) {
                $type = 'int';
            } elseif ($helper->isFloat($field)) {
                $type = 'float';
            } elseif ($helper->isPrice($field)) {
                $type = 'float';
            } elseif ($helper->isDouble($field)) {
                $type = 'double';
            } elseif ($helper->isString($field)) {
                $type = 'string';
            } elseif (isset($context['package'])) {
                /** @var BusinessBundle $businessBundle */
                $businessBundle = $context['package'];
                if ($helper->isBusinessModel($field, $businessBundle)) {
                    $type = $field->getType();
                } elseif ($helper->isDataset($field, $businessBundle)) {
                    $type = $field->getType();
                }
            }
        }

        return $type;
    }

    /**
     * @param string|Field|Data $obj
     * @param bool              $mandatory Whether the field is mandatory in this context
     */
    public function hintFilter(array $context, $obj, bool $mandatory = false): string
    {
        $helper = new FieldHelper();

        if ($obj instanceof Data) {
            $field = $obj->getField();
            if (($obj instanceof EventData) && !$mandatory) {
                $mandatory = $obj->isMandatory();
            }
        } else {
            $field = $obj;
        }

        $type = 'string';
        if ($field instanceof Field) {
            if ($helper->isBoolean($field)) {
                $type = 'bool';
            } elseif ($helper->isDate($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isDateTime($field)) {
                $type = 'DateTimeInterface';
            } elseif ($helper->isInteger($field)) {
                $type = 'int';
            } elseif ($helper->isLong($field)) {
                $type = 'int';
            } elseif ($helper->isFloat($field)) {
                $type = 'float';
            } elseif ($helper->isPrice($field)) {
                $type = 'float';
            } elseif ($helper->isDouble($field)) {
                $type = 'double';
            } elseif ($helper->isString($field)) {
                $type = 'string';
            } elseif (isset($context['package'])) {
                /** @var BusinessBundle $businessBundle */
                $businessBundle = $context['package'];
                if ($helper->isBusinessModel($field, $businessBundle)) {
                    $type = $field->getType();
                } elseif ($helper->isDataset($field, $businessBundle)) {
                    $type = $field->getType();
                }
            }

            if ($field->isList()) {
                $type .= '[]';
            }

            if (!$mandatory && !$field->isMandatory()) {
                $type .= '|null';
            }
        }

        return $type;
    }

    /**
     * Format a value in its PHP representation based on the Field this value is associated with.
     *
     * @param Field|Data $obj   The object associated with the value
     * @param mixed      $value The value to format
     */
    public function valueFilter(array $context, $obj, $value): string
    {
        $helper = new FieldHelper();

        if ($obj instanceof Data) {
            $field = $obj->getField();
        } else {
            $field = $obj;
        }

        if ($field instanceof Field) {
            if ($helper->isBoolean($field)) {
                $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($bool) {
                    $result = 'true';
                } else {
                    $result = 'false';
                }
            } elseif ($helper->isDate($field)) {
                if ($value instanceof \DateTimeInterface) {
                    $result = "new \DateTimeImmutable('{$value->format('Y-m-d')}')";
                } else {
                    $result = "new \DateTimeImmutable('$value')";
                }
            } elseif ($helper->isTime($field)) {
                if ($value instanceof \DateTimeInterface) {
                    $result = "new \DateTimeImmutable('{$value->format('H:i:s')}')";
                } else {
                    $result = "new \DateTimeImmutable('$value')";
                }
            } elseif ($helper->isDateTime($field)) {
                if ($value instanceof \DateTimeInterface) {
                    $result = "new \DateTimeImmutable('{$value->format('Y-m-d H:i:s')}')";
                } else {
                    $result = "new \DateTimeImmutable('$value')";
                }
            } elseif ($helper->isInteger($field)) {
                $result = $value;
            } elseif ($helper->isLong($field)) {
                $result = $value;
            } elseif ($helper->isFloat($field)) {
                $result = $value;
            } elseif ($helper->isPrice($field)) {
                if (is_numeric($value)) {
                    $result = $value;
                } else {
                    $priceHelper = new PriceHelper();
                    $result = $priceHelper->asFloat($value);
                }
            } elseif ($helper->isDouble($field)) {
                $result = $value;
            } elseif ($helper->isString($field)) {
                if (false !== strpos($value, "'")) {
                    $result = '"'.$value.'"';
                } else {
                    $result = "'".$value."'";
                }
            } else {
                /** @var BusinessBundle $businessBundle */
                $businessBundle = $context['package'];
                if ($helper->isBusinessModel($field, $businessBundle)) {
                    throw new InvalidArgumentException('Cannot render a value for Business Model: '.$field->getType());
                } elseif ($helper->isDataset($field, $businessBundle)) {
                    $dataset = $businessBundle->getDataset($field->getType());
                    $element = $dataset->getElement($value);
                    if (null === $element) {
                        throw new InvalidArgumentException("Cannot find element $value in Dataset {$dataset->getName()}");
                    }
                    $result = $this->classFilter($dataset).'::'.$this->constantFilter($element);
                } else {
                    throw new InvalidArgumentException('Cannot render a value for field type: '.$field->getType());
                }
            }

            if ($field->isList()) {
                $result = '['.$result.']';
            }
        } else {
            throw new InvalidArgumentException('Cannot render a value for class: '.get_class($field));
        }

        return $result;
    }
}
