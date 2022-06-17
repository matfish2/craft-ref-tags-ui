<?php namespace matfish\RefTags\models;

use craft\base\Model;

class Settings extends Model
{
    public string $trigger = 'Ctrl+Alt+R';

    public function rules() : array
    {
        return [
            [['trigger'], 'string']
        ];
    }
}
