<?php


namespace matfish\RefTags\elementfields;


use Craft;
use craft\base\Field;
use craft\models\FieldLayout;
use matfish\RefTags\elementfields\traits\CustomFieldsTrait;

class ProductFields extends BaseElementFields
{
    use CustomFieldsTrait;

    protected function getElement(): Product
    {
        return new Product($this->qualifiers);
    }

    protected function nativeFields(): array
    {
        return [
            [
                'label' => Craft::t('commerce', 'Free Shipping'),
                'value' => 'product:freeShipping',
            ],
            [
                'label' => Craft::t('commerce', 'Promotable'),
                'value' => 'product:promotable',
            ],
            [
                'label' => Craft::t('commerce', 'Available for purchase'),
                'value' => 'product:availableForPurchase',
            ],
            [
                'label' => Craft::t('commerce', 'Tax Category'),
                'value' => 'product:taxCategoryId',
            ],
            [
                'label' => Craft::t('commerce', 'Shipping Category'),
                'value' => 'product:shippingCategoryId',
            ],
            [
                'label' => Craft::t('commerce', 'SKU'),
                'value' => 'variant:sku',
            ],
            [
                'label' => Craft::t('commerce', 'Stock'),
                'value' => 'variant:stock',
            ],
            [
                'label' => Craft::t('commerce', 'Length'),
                'value' => 'variant:length',
            ],
            [
                'label' => Craft::t('commerce', 'Width'),
                'value' => 'variant:width',
            ],
            [
                'label' => Craft::t('commerce', 'Height'),
                'value' => 'variant:height',
            ],
            [
                'label' => Craft::t('commerce', 'Weight'),
                'value' => 'variant:weight',
            ],
            [
                'label' => Craft::t('commerce', 'Price'),
                'value' => 'variant:price',
            ],
        ];
    }

    protected function getAdditionalCustomFields(): array
    {
        $type = ProductType::find()->where("[[id]]={$this->qualifiers['typeId']}")->one();

        if ($type->hasVariants) {
            $fl = $type->getVariantFieldLayout()->one();
            $fieldLayout = new FieldLayout([
                'id' => $fl->id,
                'type' => $fl->type,
                'uid' => $fl->uid
            ]);


            if ($fieldLayout === null) {
                return [];
            }

            $fields = [];

            /** @var Field $field */
            foreach ($fieldLayout->getCustomFields() as $field) {
                $fieldConfig = $this->createFieldConfig($field, 'variant:');
                $fields[] = array_merge($fieldConfig, ['type' => 'custom']);
            }

            return $fields;
        }

        return [];
    }
}