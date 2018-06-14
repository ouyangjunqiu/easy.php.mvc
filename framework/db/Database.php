<?php
/**
 * Database.php
 * @author oShine <jqouyang@system.co>
 * @since 2017/4/16 14:48
 */

namespace system\db;


use system\util\App;

class Database
{
    /**************** 默认值 ****************/

    /**
     * 默认主键名
     */
    const DEFAULT_PRIMARY_KEY = 'id';

    /**************** 查询条件值标志 ****************/

    /**
     * 值为原生SQL
     */
    const WHERE_VALUE_RAW = 2;

    /**************** 属性 ****************/

    /**
     * 连接
     * @var Connection $connection
     */
    protected $connection;

    /**
     * 表名
     */
    protected $tableName;

    /**
     * 别名
     */
    protected $tableAlias;

    /**
     * 主键名
     */
    protected $primaryKey = self::DEFAULT_PRIMARY_KEY;

    /**
     * 字段
     */
    protected $fields;

    /**
     * 连接
     */
    protected $join;

    /**
     * 查询条件
     */
    protected $where;

    /**
     * 分组
     */
    protected $group;

    /**
     * 排序
     */
    protected $order;

    /**
     * 限制数量
     */
    protected $limit;

    /**
     * 最后一条查询语句
     */
    protected $lastSql;

    /**
     * @param Connection $connect
     */
    public function setConnection($connect)
    {
        $this->connection = $connect;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        if ($this->connection == null) {
            $this->connection = App::getDb();
        }
        return $this->connection;
    }

    /**
     * 获取表名
     * @return string
     */
    public function getTableName()
    {
        $tableName = preg_replace("/{{(\w+)}}/", $this->getConnection()->getTablePrefix() . '${1}', $this->tableName);
        return $tableName;
    }

    /**
     * 获取表名以及别名，表名会被反引号`包裹。
     * @return string
     */
    public function getTableNameWithAlias()
    {
        return '`' . $this->getTableName() . '`' . ($this->tableAlias ? ' AS ' . $this->tableAlias : null);
    }

    /**
     * 设置表名
     * @param string $name 表名
     * @return Database $this
     */
    public function table($name)
    {
        $this->tableName = $name;
        return $this;
    }

    /**
     * 设置别名
     * @param string $alias
     * @return Database $this
     */
    public function alias($alias)
    {
        $this->tableAlias = $alias;
        return $this;
    }

    /**
     * 设置主键名
     * @param string $name
     * @return $this
     */
    public function primary($name)
    {
        $this->primaryKey = $name;
        return $this;
    }

    /**
     * 重置查询属性
     */
    public function reset()
    {
        $this->fields = null;
        $this->join = null;
        $this->where = null;
        $this->group = null;
        $this->order = null;
        $this->limit = null;
    }

    /**
     * 设置字段
     * @param array|string $fields
     * @return $this
     */
    public function field($fields)
    {
        $this->fields = is_array($fields) ? implode(',', $fields) : $fields;
        return $this;
    }

    /**
     * 连接表
     * @param string $mode 连接方式
     * @param string $tableName 连接表名
     * @param string $alias 别名
     * @param string $clause 连接条件
     * @return Database $this
     */
    public function join($mode, $tableName, $alias, $clause)
    {
        if (!$this->join)
            $this->join = array();

        $this->join[] = $mode . ' JOIN ' . (strpos($tableName, ' ') !== false ? $tableName : '`' . $tableName . '`') . ($alias ? ' AS ' . $alias : null) . ' ON ' . $clause;
        return $this;
    }

    /**
     * 左连接
     * @param string $tableName 连接表名
     * @param string $alias 别名
     * @param string $clause 连接条件
     * @return Database $this
     */
    public function leftJoin($tableName, $alias, $clause)
    {
        return $this->join('LEFT', $tableName, $alias, $clause);
    }

    /**
     * 右连接
     * @param string $tableName 连接表名
     * @param string $alias 别名
     * @param string $clause 连接条件
     * @return Database $this
     */
    public function rightJoin($tableName, $alias, $clause)
    {
        return $this->join('RIGHT', $tableName, $alias, $clause);
    }

    /**
     * 内连接
     * @param string $tableName 连接表名
     * @param string $alias 别名
     * @param string $clause 连接条件
     * @return Database $this
     */
    public function innerJoin($tableName, $alias, $clause)
    {
        return $this->join('INNER', $tableName, $alias, $clause);
    }

    /**
     * 解析查询条件
     * @param mixed $where 数组，整型（作为主键值）、字符串或空值。如果键为整型值为数组则该数组为条件数组，并且该数组第一个元素可以为and或or来指定条件模式。
     * @param bool $orMode true为OR模式，false为AND模式
     * @return string 返回查询条件字符串
     */
    public function parseWhere($where, $orMode = false)
    {
        if (is_numeric($where))
            $where = '`' . $this->primaryKey . '` = ' . $where;
        else if (empty($where))
            $where = null;
        else if (is_array($where)) {
            if (isset($where[0]) && is_string($where[0])) {
                $type = strtolower($where[0]);

                if ($type == 'and' || $type == 'or') {
                    array_shift($where);

                    if (empty($where))
                        return null;

                    if (count($where) == 1)
                        $where = $where[0];

                    return $this->parseWhere($where, $type == 'or');
                }
            }

            $arr = array();

            foreach ($where as $k => $v) {
                if (is_int($k)) {
                    if (is_array($v)) {
                        $v = $this->parseWhere($v);

                        if (!empty($v))
                            $arr[] = '(' . $v . ')';
                    } else $arr[] = $v;
                } else {
                    $exp = null;
                    $isField = false;
                    $isRaw = false;

                    if (is_array($v)) {
                        $isField = count($v) > 2 && $v[2];
                        $isRaw = count($v) > 2 && $v[2] == self::WHERE_VALUE_RAW;
                        $exp = strtoupper($v[0]);
                        $v = $v[1];
                    }

                    if ($isField) {
                        if (!$isRaw)
                            $v = '`' . $v . '`';
                    } else if ($exp == 'IN' || $exp == 'NOT IN') {
                        if (is_array($v)) {
                            foreach ($v as $vk => $vv) {
                                if (is_numeric($vv))
                                    $vv = '\'' . $vv . '\'';
                                else if (is_bool($vv))
                                    $vv = intval($vv);
                                else if (is_null($vv))
                                    $vv = 'NULL';
                                else
                                    $vv = '\'' . $this->getConnection()->escape($vv) . '\'';

                                $v[$vk] = $vv;
                            }

                            $v = '(' . implode(',', $v) . ')';
                        } else if (strpos($v, '(') !== 0)
                            $v = '(' . $v . ')';
                    } else {
                        if (is_numeric($v))
                            $v = '\'' . $v . '\'';
                        else if (is_bool($v))
                            $v = intval($v);
                        else if (is_null($v)) {
                            if (!$exp) $exp = 'IS';
                            $v = 'NULL';
                        } else $v = '\'' . $this->getConnection()->escape($v) . '\'';
                    }

                    if (!$exp) $exp = '=';

                    if ($exp == 'UNIX_TIMESTAMP<') {
                        $arr[] = (strpos($k, '.') !== false ? $k : "UNIX_TIMESTAMP($k)") . ' < ' . $v;
                    } elseif ($exp == 'UNIX_TIMESTAMP>') {
                        $arr[] = (strpos($k, '.') !== false ? $k : "UNIX_TIMESTAMP($k)") . ' > ' . $v;
                    } else
                        $arr[] = (strpos($k, '.') !== false ? $k : '`' . $k . '`') . ' ' . $exp . ' ' . $v;
                }
            }

            $where = !empty($arr) ? implode($orMode ? ' OR ' : ' AND ', $arr) : null;
        } else $where = strval($where);

        return $where;
    }

    /**
     * 设置查询条件
     * @param mixed $where 可以为Bean对象，数组，整型（作为主键值）、字符串或空值。如果键为整型值为数组则该数组为条件数组，并且该数组第一个元素可以为and或or来指定条件模式。
     * @param bool $orMode true为OR模式，false为AND模式
     * @return Database
     */
    public function where($where, $orMode = false)
    {
        $this->where = $this->parseWhere($where, $orMode);

        return $this;
    }

    /**
     * 设置查询条件，模式为OR模式
     * @param mixed $where 可以为Bean对象，数组，整型（作为主键值）、字符串或空值。如果键为整型值为数组则该数组为条件数组，并且该数组第一个元素可以为and或or来指定条件模式。
     * @return Database $this
     */
    public function orWhere($where)
    {
        return $this->where($where, true);
    }

    /**
     * 设置分组
     * @param string $group
     * @return Database $this
     */
    public function group($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * 设置排序
     * @param string $order
     * @return Database
     */
    public function order($order)
    {
        $this->order = is_array($order) ? implode(',', $order) : $order;
        return $this;
    }

    /**
     * 设置排序为ID升序
     * @return Database
     */
    public function idAsc()
    {
        $this->order = $this->primaryKey . ' ASC';
        return $this;
    }

    /**
     * 设置排序为ID降序
     * @return Database
     */
    public function idDesc()
    {
        $this->order = $this->primaryKey . ' DESC';
        return $this;
    }

    /**
     * 设置排序为随机排序
     * @return Database
     */
    public function randOrder()
    {
        $this->order = 'rand()';
        return $this;
    }

    /**
     * 设置限制数量
     * @param string $limit
     * @return Database
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * SELECT查询
     * @return mixed 成功返回结果标识，失败返回flase。
     */
    public function select()
    {
        $sql = $this->buildSelectSql();
        $this->reset();
        $this->lastSql = $sql;
        return $this->getConnection()->query($sql);
    }

    /**
     * 构建查询sql
     */
    protected function buildSelectSql()
    {
        $table = $this->getTableNameWithAlias();
        $join = !empty($this->join) ? ' ' . implode(' ', $this->join) : null;
        $fields = $this->fields ? $this->fields : '*';
        $where = $this->where ? $this->where : '1';
        $group = $this->group ? ' GROUP BY ' . $this->group : null;
        $order = $this->order ? ' ORDER BY ' . $this->order : null;
        $limit = $this->limit ? ' LIMIT ' . $this->limit : null;
        $sql = 'SELECT ' . $fields . ' FROM ' . $table . $join . ' WHERE ' . $where . $group . $order . $limit;
        return $sql;
    }

    /**
     * 获取所有行记录
     * @param mixed $where
     * @return mixed 成功返回所有行记录数组，失败返回false。
     */
    public function getList($where = null)
    {
        if ($where)
            $this->where($where);

        $sql = $this->buildSelectSql();
        $this->lastSql = $sql;
        $this->reset();
        return $this->getConnection()->queryAll($sql);
    }


    /**
     * 获取一行记录
     * @param mixed $where
     * @return mixed 成功返回关联数组，失败返回false。
     */
    public function getRow($where = null)
    {
        if ($where)
            $this->where($where);
        $this->limit(1);
        $sql = $this->buildSelectSql();
        $this->lastSql = $sql;
        $this->reset();

        return $this->getConnection()->queryRow($sql);
    }

    /**
     * 获取一行记录中某个字段的值
     * @param string $fieldName
     * @return mixed 成功返回字段值，失败返回false。
     */
    public function getValue($fieldName)
    {
        if (!$this->fields)
            $this->fields = $fieldName;

        $row = $this->getRow();
        return $row && isset($row[$fieldName]) ? $row[$fieldName] : false;
    }

    /**
     * 获取主键值
     */
    public function getId()
    {
        return $this->getValue($this->primaryKey);
    }

    /**
     * 判断某个字段的值是否存在
     * @param string $fieldName
     * @param string $fieldValue
     * @param integer $primaryKey 如果传主键值则排除该主键
     * @return bool
     */
    public function isValueExists($fieldName, $fieldValue, $primaryKey = 0)
    {
        $where = array($fieldName => $fieldValue);

        if ($primaryKey)
            $where[] = $this->primaryKey . ' != ' . $primaryKey;

        return $this->where($where)->count() > 0;
    }

    /**
     * 获取数量
     * @return mixed 成功返回数量，失败返回false。
     */
    public function count()
    {
        return $this->field('COUNT(*) AS total')->getValue('total');
    }

    /**
     * 插入数据
     * @param mixed $data 关联数组或Bean对象
     * @return mixed 成功返回插入的主键值，失败返回false。
     */
    public function insert($data)
    {
        if (!is_array($data) || empty($data))
            return false;

        foreach ($data as $k => $v) {
            if (is_numeric($v))
                $v = '\'' . $v . '\'';
            else if (is_bool($v))
                $v = intval($v);
            else if (is_null($v))
                $v = 'NULL';
            else
                $v = '\'' . $this->getConnection()->escape($v) . '\'';

            unset($data[$k]);
            $data['`' . $k . '`'] = $v;
        }

        $table = $this->getTableNameWithAlias();
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));
        $sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' . $values . ')';

        $this->lastSql = $sql;

        return $this->getConnection()->query($sql) ? true : false;
    }


    /**
     * 插入数据
     * @param mixed $data 关联数组或Bean对象
     * @return mixed 成功返回插入的主键值，失败返回false。
     */
    public function idinsert($data)
    {

        if (!is_array($data) || empty($data))
            return false;

        foreach ($data as $k => $v) {
            if (is_numeric($v))
                $v = '\'' . $v . '\'';
            else if (is_bool($v))
                $v = intval($v);
            else if (is_null($v))
                $v = 'NULL';
            else
                $v = '\'' . $this->getConnection()->escape($v) . '\'';

            unset($data[$k]);
            $data['`' . $k . '`'] = $v;
        }

        $table = $this->getTableNameWithAlias();
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));
        $sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' . $values . ')';

        return $this->getConnection()->query($sql) ? $this->getConnection()->getLastId() : false;
    }

    /**
     * 更新数据
     * @param mixed $data 关联数组或Bean对象
     * @return boolean 成功返回true，失败返回false。
     */
    public function update($data)
    {
        if (!is_array($data) || empty($data))
            return false;

        $dataList = array();

        foreach ($data as $k => $v) {
            if (is_int($k))
                $dataList[] = $v;
            else {
                if (is_numeric($v))
                    $v = '\'' . $v . '\'';
                else if (is_bool($v))
                    $v = intval($v);
                else if (is_null($v))
                    $v = 'NULL';
                else
                    $v = '\'' . $this->getConnection()->escape($v) . '\'';

                $dataList[] = '`' . $k . '` = ' . $v;
            }
        }

        $table = $this->getTableNameWithAlias();
        $data = implode(', ', $dataList);
        $where = $this->where ? $this->where : '1';
        $sql = 'UPDATE ' . $table . ' SET ' . $data . ' WHERE ' . $where;

        $this->reset();
        return $this->getConnection()->query($sql) ? true : false;
    }

    /**
     * 自增某个字段的值
     * @param string $fieldName
     * @return bool
     */
    public function increase($fieldName)
    {
        return $this->update(array('`' . $fieldName . '` = `' . $fieldName . '` + 1'));
    }

    /**
     * 自减某个字段的值
     * @param string $fieldName
     * @return bool
     */
    public function decrease($fieldName)
    {
        return $this->update(array('`' . $fieldName . '` = `' . $fieldName . '` - 1'));
    }

    /**
     * 保存数据，如果设置了where则更新，没有则插入。
     * @param array $data 关联数组或Bean对象
     * @return bool|mixed
     */
    public function save($data)
    {
        if ($this->where)
            return $this->update($data);
        else
            return $this->insert($data);
    }

    /**
     * 保存数据，如果设置了where则更新，没有则插入。
     * @param mixed $data 关联数组或Bean对象
     * @return bool|mixed
     */
    public function idsave($data)
    {
        if ($this->where)
            return $this->update($data);
        else
            return $this->idinsert($data);
    }


    /**
     * 删除记录，为防止意外清空表，如果where条件为空则返回false。
     * @param mixed $where
     * @return boolean 成功返回true，失败返回false。
     */
    public function delete($where = null)
    {
        if ($where)
            $this->where($where);

        if (!$this->where)
            return false;

        $table = $this->getTableNameWithAlias();
        $where = $this->where ? $this->where : '1';
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;

        $this->reset();
        return $this->getConnection()->query($sql) ? true : false;
    }

    /**
     * 清空表，谨慎调用。
     * @return boolean
     */
    public function truncate()
    {
        $table = $this->getTableNameWithAlias();
        $sql = 'TRUNCATE TABLE ' . $table;

        $this->reset();
        return $this->getConnection()->query($sql) ? true : false;
    }

    /**
     * @param \Page $page
     * @return $this
     */
    public function page($page)
    {
        if ($page instanceof \Page)
            $this->limit($page->getLimit());
        return $this;
    }

    /**
     * @param $sql
     * @return mixed 成功返回结果标识，失败返回false。
     * @throws Exception
     * @deprecated
     * @see Connection::query($sql)
     */
    public function query($sql)
    {
        return $this->getConnection()->query($sql);
    }

    /**
     * @param $sql
     * @return array
     * @see Connection::queryAll($sql)
     */
    public function queryAll($sql)
    {
        return $this->getConnection()->queryAll($sql);
    }

    /**
     * @param $sql
     * @return array
     * @see Connection::queryRow($sql)
     */
    public function queryRow($sql)
    {
        return $this->getConnection()->queryRow($sql);
    }

    /**
     * @param $sql
     * @return array
     * @see Connection::queryScalar($sql)
     */
    public function queryScalar($sql)
    {
        return $this->getConnection()->queryScalar($sql);
    }

    public function getLastSql()
    {
        return $this->lastSql;
    }

}