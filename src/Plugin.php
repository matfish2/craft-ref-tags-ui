<?php

namespace matfish\RefTags;

use craft\base\Element;
use craft\base\Model;
use matfish\RefTags\models\Settings;
use craft\base\Plugin as BasePlugin;
use Craft;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use matfish\RefTags\elementfields\AssetFields;
use matfish\RefTags\elementfields\CategoryFields;
use matfish\RefTags\elementfields\EntryFields;
use matfish\RefTags\elementfields\TagFields;
use matfish\RefTags\elementfields\UserFields;
use yii\base\Event;

class Plugin extends BasePlugin
{
    public static array $elementClasses = [
        'entry' => Entry::class,
        'category' => Category::class,
        'tag' => Tag::class,
        'asset' => Asset::class,
        'user' => User::class
    ];

    public static array $elementFields = [
        'entry' => EntryFields::class,
        'category' => CategoryFields::class,
        'tag' => TagFields::class,
        'asset' => AssetFields::class,
        'user' => UserFields::class,
    ];

    public function init()
    {
        parent::init();

        if (Craft::$app->request->isCpRequest) {
            $this->controllerNamespace = 'matfish\\RefTags\\controllers';
        }

        // check we have a cp request as we don't want to this js to run anywhere but the cp
        // and while we're at it check for a logged in user as well
        if (Craft::$app->request->getIsCpRequest() && Craft::$app->getUser()) {
            $hooks = [
                'cp.entries.edit.meta',
                'cp.assets.edit.meta',
                'cp.users.edit.details',
                'cp.categories.edit.details'
            ];

            foreach ($hooks as $hook) {
                Craft::$app->view->hook($hook, function (array &$context) {
                    // Get view
                    $view = Craft::$app->getView();

                    // Load JS file
                    $view->registerAssetBundle(RefTagsAssetBundle::class);

                    $trigger = $this->settings->trigger;
                    return '<div id="ref-tags-app"><ref-tags-modal :on="isOn" @close="isOn=false"></ref-tags-modal></div><script>window.refTagsUiTrigger ="' . $trigger . '";</script></script>';
                });
            }

            Event::on(
                Element::class,
                Element::EVENT_DEFINE_SIDEBAR_HTML,
                function ($event) {


                }
            );
        }
    }

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }
}
