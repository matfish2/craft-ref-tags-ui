<?php


namespace matfish\RefTags\elementfields;

use Craft;
use craft\base\ElementInterface;
use craft\elements\User;
use matfish\RefTags\elementfields\traits\CustomFieldsTrait;

class UserFields extends BaseElementFields
{

    use CustomFieldsTrait;

    protected function getElement(): ElementInterface
    {
        return new User($this->qualifiers);
    }

    protected function nativeFields(): array
    {
        return [
            [
                'label' => Craft::t('app', 'Username'),
                'value'=> 'username',
            ],
            [
                'label' => Craft::t('app', 'Email'),
                'value'=> 'email',
            ],
            [
                'label' => Craft::t('app', 'First Name'),
                'value'=> 'firstName',
            ],
            [
                'label' => Craft::t('app', 'Last Name'),
                'value'=> 'lastName',
            ],
            [
                'label' => Craft::t('app', 'Full Name'),
                'value'=> 'fullName',
            ]
        ];
    }
}