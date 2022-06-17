<?php


namespace matfish\RefTags\elementfields;


use Craft;
use craft\base\ElementInterface;
use craft\elements\Asset;
use matfish\RefTags\elementfields\traits\CustomFieldsTrait;

class AssetFields extends BaseElementFields
{
    use CustomFieldsTrait;

    protected function getElement(): ElementInterface
    {
        return new Asset($this->qualifiers);
    }

    protected function nativeFields(): array
    {
        return [
            [
                'label' => Craft::t('app', 'Filename'),
                'value' => 'filename',
            ],
            [
                'label' => Craft::t('app', 'File Kind'),
                'value' => 'kind',
            ],
            [
                'label' => Craft::t('app', 'Image Width'),
                'value' => 'width'
            ],
            [
                'label' => Craft::t('app', 'Image Height'),
                'value' => 'height'
            ],
            [
                'label' => Craft::t('app', 'File Size'),
                'value' => 'size',
            ],
            [
                'label' => Craft::t('app', 'Uploaded by'),
                'value' => 'uploaderId'
            ]
        ];
    }

    protected function customFields(): array
    {
        return $this->getCustomFieldsForElement($this->getElement());
    }
}