<?php

namespace matfish\RefTags\elementfields\traits;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Assets;
use craft\fields\BaseOptionsField;
use craft\fields\BaseRelationField;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Date;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Matrix;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\Table;
use craft\fields\Tags;
use craft\fields\Time;
use matfish\Tablecloth\enums\DataTypes;
use matfish\Tablecloth\enums\Fields;
use phpDocumentor\Reflection\Types\Boolean;

trait CustomFieldsTrait
{
    protected function customFields(): array
    {
        return $this->getCustomFieldsForElement($this->getElement());
    }

    /**
     * @param ElementInterface $element
     * @return array
     */
    private function getCustomFieldsForElement(ElementInterface $element): array
    {
        $fieldLayout = $element->getFieldLayout();

        if (!$element::hasContent() || $fieldLayout === null) {
            return [];
        }

        $fields = [];

        /** @var Field $field */
        foreach ($fieldLayout->getCustomFields() as $field) {
            if (!in_array(get_class($field), [
                Matrix::class,
                Table::class,
                Assets::class,
                MultiSelect::class,
                Entries::class,
                Tags::class,
                Categories::class,
                Checkboxes::class,
                Lightswitch::class,
                Date::class
            ])) {
                $fields[] = [
                    'value' => $field->handle,
                    'label' => $field->name
                ];
            }

        }

        return array_merge($fields, $this->getAdditionalCustomFields());

    }
}