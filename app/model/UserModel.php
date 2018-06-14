<?php
namespace app\model;
/**
 * 用户模型
 *
 * @author Jack Chan
 * @since 2016-06-10
 */
use system\db\Model;

class UserModel extends Model
{
    /**
     * 表名
     */
    const TABLE_NAME = 'user';


    public function tableName()
    {
        // TODO: Implement tableName() method.
        return self::TABLE_NAME;
    }
}