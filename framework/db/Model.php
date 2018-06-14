<?php
namespace system\db;
/**
 * 数据模型，与数据表对应。
 *
 * @author oshine
 * @since 2017-04-16
 */
abstract class Model extends Database
{
    protected $errorMessage = null;

    public function __construct()
    {
        $table = $this->tableName();
        $this->table($table);
    }

    /**
     * @return mixed
     */
    abstract  public function tableName();


    /**
     * @return null
     */
    public function getErrorMessage(){
        return $this->errorMessage;
    }


}