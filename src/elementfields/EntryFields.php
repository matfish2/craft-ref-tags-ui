<?php


namespace matfish\RefTags\elementfields;

use Craft;
use craft\base\ElementInterface;
use craft\elements\Entry;
use matfish\RefTags\elementfields\traits\CustomFieldsTrait;

class EntryFields extends BaseElementFields
{

    use CustomFieldsTrait;

    protected function nativeFields(): array
    {
        return [
            [
                'label' => Craft::t('app', 'Post Date'),
                'value' => 'postDate',
            ],
            [
                'label' => Craft::t('app', 'Author'),
                'value' => 'authorId'
            ]
        ];
    }

    /**
     * @return ElementInterface
     */
    protected function getElement(): ElementInterface
    {
        return new Entry($this->qualifiers);
    }
}