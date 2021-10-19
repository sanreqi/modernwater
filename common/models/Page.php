<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $template_id
 * @property int $is_delete
 * @property int $created_at
 * @property int $updated_at
 */
class Page extends \yii\db\ActiveRecord
{
    const IS_DELETE_YES = 1;
    const IS_DELETE_NO = 0;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'template_id' => 'Template ID',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function search($params, $count = false) {
        $query = new Query();
        $query->from('page')->where(['is_delete' => Constants::IS_DELETE_NO]);
        if (isset($params['name']) && !empty($params['name'])) {
            $name = trim($params['name']);
            $query->andWhere(['like', 'name', $name]);
        }

        if ($count) {
            return $query->count();
        }

        $offset = 0;
        $limit = 10;
        //判断正整数 写在function里
        if (isset($params['per-page']) && !empty($params['per-page'])) {
            $limit = $params['per-page'];
        }
        if (isset($params['page']) && !empty($params['page'])) {
            $offset = ($params['page'] - 1) * $limit;
        }

        return $query->orderBy(['id' => SORT_ASC])->offset($offset)->limit($limit)->all();
    }
}
