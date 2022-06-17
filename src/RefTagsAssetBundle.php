<?php


namespace matfish\RefTags;


use craft\web\AssetBundle;

class RefTagsAssetBundle extends AssetBundle
{
    public function init()
    {
        parent::init();

        // define the path that your publishable resources live
        $this->sourcePath = '@matfish/RefTags/assets/compiled';

        // define the dependencies
        $this->depends = [
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'ref-tags-ui.min.js',
        ];

        $this->css = [
            'ref-tags-ui.min.css'
        ];
    }

}