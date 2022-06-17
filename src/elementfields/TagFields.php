<?php


namespace matfish\RefTags\elementfields;


use craft\base\ElementInterface;
use craft\elements\Tag;
use matfish\RefTags\elementfields\traits\CustomFieldsTrait;

class TagFields extends BaseElementFields
{
    use CustomFieldsTrait;

    protected function getElement(): ElementInterface
    {
        return new Tag($this->qualifiers);
    }

    protected function nativeFields(): array
    {
        return [];
    }
}