<?php

namespace matfish\RefTags\elementfields;

use Craft;
use craft\base\ElementInterface;
use matfish\Tablecloth\enums\DataTypes;

abstract class BaseElementFields
{
    protected array $qualifiers = [];

    /**
     * BaseElementFields constructor.
     * @param array $qualifiers
     */
    public function __construct(array $qualifiers)
    {
        $this->qualifiers = $qualifiers;
    }


    public function getFields(): array
    {
        return array_merge($this->commonFields(), $this->nativeFields(), $this->customFields());
    }

    public function getBuiltInFields() : array {
        return array_merge($this->commonFields(), $this->nativeFields());
    }

    abstract protected function getElement(): ElementInterface;

    protected function commonFields(): array
    {
        return [
            [
                'label' => Craft::t('app', 'ID'),
                'value' => 'id',
            ],
            [
                'label' => Craft::t('app', 'Title'),
                'value' => 'title',
            ],
            [
                'label' => Craft::t('app', 'Slug'),
                'value' => 'slug',
            ],
            [
                'label' => Craft::t('app', 'Date Created'),
                'value' => 'dateCreated',
            ],
            [
                'label' => Craft::t('app', 'Date Updated'),
                'value' => 'dateUpdated',
            ],
        ];
    }

    abstract protected function nativeFields(): array;

    protected function customFields(): array
    {
        return [];
    }

    protected function getAdditionalCustomFields() : array {
        return [];
    }
}