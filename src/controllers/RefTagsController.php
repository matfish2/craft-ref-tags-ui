<?php

namespace matfish\RefTags\controllers;

use Craft;
use craft\models\Section;
use craft\records\Site;
use matfish\RefTags\Plugin;
use yii\web\Response;

class RefTagsController extends \craft\web\Controller
{
    public function actionInitialData(): Response
    {
        $gs = \craft\elements\GlobalSet::findAll();

        $globalsets = [];

        foreach ($gs as $g) {
            $fields = [];

            foreach ($g->getFields() as $field) {
                $fields[] = [
                    'value' => $field->handle,
                    'label' => $field->name
                ];
            }

            $globalsets[] = [
                'value' => $g->id,
                'label' => $g->name,
                'fields' => $fields
            ];
        }
        return $this->asJson([
                'sites' => Site::find()->select(['id', 'name'])->all(),
                'currentSiteId' => \Craft::$app->sites->currentSite->id,
                'globalSets' => $globalsets,
            ]
        );
    }

    public function actionPropertiesList(): Response
    {
        $elementType = \Craft::$app->request->getQueryParam('elementType');
        $qualifiers = json_decode(\Craft::$app->request->getQueryParam('qualifiers'), true);
        $fieldsClass = Plugin::$elementFields[$elementType];
        $fields = (new $fieldsClass($qualifiers))->getFields();

        return $this->asJson($fields);
    }

    public function actionVolumes(): Response
    {
        $volumes = Craft::$app->getVolumes()->allVolumes;

        $res = array_map(static function ($volume) {
            return [
                'value' => $volume->id,
                'label' => $volume->name
            ];
        }, $volumes);

        return $this->asJson($res);
    }

    public function actionSections(): Response
    {
        $channel = Craft::$app->sections->getSectionsByType(Section::TYPE_CHANNEL);
        $structure = Craft::$app->sections->getSectionsByType(Section::TYPE_STRUCTURE);

        $res = array_map(static function ($section) {
            return [
                'label' => $section->name,
                'value' => $section->id,
                'entryTypes' => array_map(static function ($entryType) {
                    return [
                        'label' => $entryType->name,
                        'value' => $entryType->id
                    ];
                }, $section->getEntryTypes())
            ];
        }, array_merge($channel, $structure));

        return $this->asJson($res);
    }

    public function actionCategoryGroups(): Response
    {
        $groups = Craft::$app->categories->getAllGroups();
        $res = array_map(static function ($group) {
            return [
                'value' => $group->id,
                'label' => $group->name
            ];
        }, $groups);

        return $this->asJson($res);
    }

    public function actionTagGroups(): Response
    {
        $groups = Craft::$app->tags->getAllTagGroups();
        $res = array_map(static function ($group) {
            return [
                'value' => $group->id,
                'label' => $group->name
            ];
        }, $groups);

        return $this->asJson($res);
    }

    public function actionElements(): Response
    {
        $q = \Craft::$app->request->getQueryParam('q');
        $elType = \Craft::$app->request->getQueryParam('elementType');
        $siteId = \Craft::$app->request->getQueryParam('siteId');
        $qualifiers = json_decode(\Craft::$app->request->getQueryParam('qualifiers'), true);


        $field = $elType === 'user' ? 'fullname' : 'title';
        $res = Plugin::$elementClasses[$elType]::find()->siteId($siteId);

        if ($elType === 'entry') {
            $res->sectionId($qualifiers['sectionId']);
        } elseif (in_array($elType, ['category', 'tag'])) {
            $res->groupId($qualifiers['groupId']);
        } else if ($elType === 'asset') {
            $res->volumeId($qualifiers['volumeId']);
        }

        $res = $res->search($field . ':' . $q)->limit(10)->all();
        $res = array_map(static function ($item) use ($field) {
            return [
                'name' => $item->{$field},
                'value' => $item->id
            ];
        }, $res);
        return $this->asJson($res);
    }
}