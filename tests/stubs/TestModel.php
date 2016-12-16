<?php

namespace sergeymakinen\tests\caching\stubs;

use yii\base\Model;

class TestModel extends Model
{
    public $foo;

    public $bar;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['foo'], 'required'],
            [['foo'], 'integer'],
            [['bar'], 'boolean'],
        ];
    }
}
