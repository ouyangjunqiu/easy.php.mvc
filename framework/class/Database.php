<?php
/**
 * 数据库，默认实现MySQL数据库驱动
 *
 * @author Jack Chan
 * @since 2016-05-08
 * @deprecated
 * @see system\db\Database
 */
class Database
{
    /**************** 默认值 ****************/
    /**
     * 默认服务器
     */
    const DEFAULT_SERVER		= '172.22.110.252';

    /**
     * 默认用户名
     */
    const DEFAULT_USERNAME		= 'root';

    /**
     * 默认密码
     */
    const DEFAULT_PASSWORD		= 'root';

    /**
     * 默认端口号
     */
    const DEFAULT_PORT = 3306;

    /**
     * 默认数据库
     */
    const DEFAULT_DATABASE		= 'wxmark';

    /**
     * 默认连接字符集
     */
    const DEFAULT_CHARSET		= 'utf8';

    /**
     * 默认主键名
     */
    const DEFAULT_PRIMARY_KEY	= 'id';

    /**
     * 默认每页数量
     */
    const DEFAULT_PAGE_SIZE		= 20;

    /**************************** 错误消息 ****************************/
    /**
     * 连接数据库失败错误消息
     */
    const MESSAGE_CONNECT_FAILED		= 'Can not connect to Database Server';

    /**
     * 查询失败错误消息前缀
     */
    const MESSAGE_PREFIX_QUERY_FAILED	= 'Database query failed: ';

    /**************** 查询条件值标志 ****************/
    /**
     * 值为默认可解析值
     */
    const WHERE_VALUE_DEFAULT	= 0;

    /**
     * 值为字段
     */
    const WHERE_VALUE_FIELD		= 1;

    /**
     * 值为原生SQL
     */
    const WHERE_VALUE_RAW		= 2;

    /**************** 属性 ****************/
    /**
     * 错误消息
     */
    protected $errorMessage;

    /**
     * 连接
     */
    protected $connection;

    /**
     * 服务器
     */
    protected $server;

    /**
     * 端口号
     * @var
     */
    protected $port = 3306;

    /**
     * 用户名
     */
    protected $username;

    /**
     * 密码
     */
    protected $password;

    /**
     * 数据库名称
     */
    protected $databaseName;

    /**
     * 连接字符集
     */
    protected $charset;

    /**
     * 表名前缀
     */
    protected $tablePrefix;

    /**
     * 表名前缀是否启用
     */
    protected $tablePrefixEnabled = false;

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
     * 查询字符串
     */
    protected $sql;

    /**
     * 查询结果
     */
    protected $result;


    /**
     * 构造方法
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $databaseName
     * @param string $charset
     * @param int $port
     */
    public function __construct($server = self::DEFAULT_SERVER, $username = self::DEFAULT_USERNAME, $password = self::DEFAULT_PASSWORD, $databaseName = self::DEFAULT_DATABASE, $charset = self::DEFAULT_CHARSET, $port = self::DEFAULT_PORT)
    {
        $this->server		= $server;
        $this->username		= $username;
        $this->password		= $password;
        $this->databaseName	= $databaseName;
        $this->charset		= $charset;
        $this->port = $port;
    }

    /**
     * 获取错误消息
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * 获取表名
     * @param boolean $noPrefix true不返回表名前缀，false返回表名前缀。
     * @return string
     */
    public function getTableName($noPrefix = false)
    {
        return $noPrefix || !$this->tablePrefixEnabled ? $this->tableName : $this->tablePrefix.$this->tableName;
    }

    /**
     * 获取表名以及别名，表名会被反引号`包裹。
     * @param boolean $noPrefix true不返回表名前缀，false返回表名前缀。
     * @return string
     */
    public function getTableNameWithAlias($noPrefix = false)
    {
        return '`'.$this->getTableName($noPrefix).'`'.($this->tableAlias ? ' AS '.$this->tableAlias : null);
    }

    /**
     * 获取查询字符串
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * 连接数据库
     * @return mixed 成功返回连接标识，失败返回false。
     */
    public function connect()
    {
        if (!$this->connection)
        {
            $this->connection = mysqli_connect($this->server, $this->username, $this->password, $this->databaseName, $this->port);

            if (!$this->connection)
            {
                $this->errorMessage = self::MESSAGE_CONNECT_FAILED;
                return false;
            }

            $this->query('SET NAMES \''.$this->charset.'\'');
        }

        return $this->connection;
    }

    /**
     * 查询
     * @param string $sql
     * @return mixed 成功返回结果标识，失败返回false。
     */
    public function query($sql)
    {
        if (!$this->connect())
            return false;

        $this->sql = $sql;
        $this->result = mysqli_query($this->connection, $sql);

        if (!$this->result)
        {
            $this->errorMessage = self::MESSAGE_PREFIX_QUERY_FAILED.mysqli_error($this->connection);
            die($this->errorMessage);
            return false;
        }

        return $this->result;
    }

    /**
     * 转义
     * @param string $string
     * @return string 返回转义后的字符串
     */
    public function escape($string)
    {
        if (!$this->connect())
            return false;

        return mysqli_real_escape_string($this->connection, $string);
    }

    /**
     * 设置表名前缀
     * @param string $prefix
     * @return Database $this
     */
    public function prefix($prefix)
    {
        $this->tablePrefix = $prefix;
        return $this;
    }

    /**
     * 设置表名
     * @param string $name 表名
     * @param bool $noPrefix 是否禁用前缀
     * @return Database $this
     */
    public function table($name, $noPrefix = false)
    {
        $this->tableName = $name;
        $this->tablePrefixEnabled = !$noPrefix;
        return $this;
    }

    /**
     * 设置表名，禁用前缀
     * @param string $name 表名
     * @return Database $this
     */
    public function tableNoPrefix($name)
    {
        return $this->table($name, true);
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
        $this->fields	= null;
        $this->join		= null;
        $this->where	= null;
        $this->group	= null;
        $this->order	= null;
        $this->limit	= null;
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

        $this->join[] = $mode.' JOIN '.(strpos($tableName, ' ') !== false ? $tableName : '`'.$tableName.'`').($alias ? ' AS '.$alias : null).' ON '.$clause;
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
     * @param mixed $where 可以为Bean对象，数组，整型（作为主键值）、字符串或空值。如果键为整型值为数组则该数组为条件数组，并且该数组第一个元素可以为and或or来指定条件模式。
     * @param bool $orMode true为OR模式，false为AND模式
     * @return string 返回查询条件字符串
     */
    public function parseWhere($where, $orMode = false)
    {
        if (is_numeric($where))
            $where = '`'.$this->primaryKey.'` = '.$where;
        else if (empty($where))
            $where = null;
        else if (is_array($where))
        {
            if (isset($where[0]) && is_string($where[0]))
            {
                $type = strtolower($where[0]);

                if ($type == 'and' || $type == 'or')
                {
                    array_shift($where);

                    if (empty($where))
                        return null;

                    if (count($where) == 1)
                        $where = $where[0];

                    return $this->parseWhere($where, $type == 'or');
                }
            }

            $arr = array();

            foreach ($where as $k => $v)
            {
                if (is_int($k))
                {
                    if (is_array($v))
                    {
                        $v = $this->parseWhere($v);

                        if (!empty($v))
                            $arr[] = '('.$v.')';
                    }
                    else $arr[] = $v;
                }
                else
                {
                    $exp = null;
                    $isField = false;
                    $isRaw = false;

                    if (is_array($v))
                    {
                        $isField = count($v) > 2 && $v[2];
                        $isRaw = count($v) > 2 && $v[2] == self::WHERE_VALUE_RAW;
                        $exp = strtoupper($v[0]);
                        $v = $v[1];
                    }

                    if ($isField)
                    {
                        if (!$isRaw)
                            $v = '`'.$v.'`';
                    }
                    else if ($exp == 'IN' || $exp == 'NOT IN')
                    {
                        if (is_array($v))
                        {
                            foreach ($v as $vk => $vv)
                            {
                                if (is_numeric($vv))
                                    $vv = '\''.$vv.'\'';
                                else if (is_bool($vv))
                                    $vv = intval($vv);
                                else if (is_null($vv))
                                    $vv = 'NULL';
                                else
                                    $vv = '\''.$this->escape($vv).'\'';

                                $v[$vk] = $vv;
                            }

                            $v = '('.implode(',', $v).')';
                        }
                        else if (strpos($v, '(') !== 0)
                            $v = '('.$v.')';
                    }
                    else
                    {
                        if (is_numeric($v))
                            $v = '\''.$v.'\'';
                        else if (is_bool($v))
                            $v = intval($v);
                        else if (is_null($v))
                        {
                            if (!$exp) $exp = 'IS';
                            $v = 'NULL';
                        }
                        else $v = '\''.$this->escape($v).'\'';
                    }

                    if (!$exp) $exp = '=';

                    if ($exp == 'UNIX_TIMESTAMP<')
                    {
                        $arr[] = (strpos($k, '.') !== false ? $k : "UNIX_TIMESTAMP($k)").' < '.$v;
                    }
                    elseif($exp == 'UNIX_TIMESTAMP>')
                    {
                        $arr[] = (strpos($k, '.') !== false ? $k : "UNIX_TIMESTAMP($k)").' > '.$v;
                    }
                    else
                        $arr[] = (strpos($k, '.') !== false ? $k : '`'.$k.'`').' '.$exp.' '.$v;
                }
            }

            $where = !empty($arr) ? implode($orMode ? ' OR ' : ' AND ', $arr) : null;
        }
        else $where = strval($where);

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
        $this->order = $this->primaryKey.' ASC';
        return $this;
    }

    /**
     * 设置排序为ID降序
     * @return Database
     */
    public function idDesc()
    {
        $this->order = $this->primaryKey.' DESC';
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
     * 分页限制
     * @param Page|int $page 可以为页号，也可以为Page对象。
     * @param int $pageSize
     * @return Database
     */
    public function page($page, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        if ($page instanceof Page)
        {
            $pageId		= $page->getPageId();
            $pageSize	= $page->getPageSize();
        }
        else $pageId = $page;

        $this->limit = (($pageId - 1) * $pageSize).','.$pageSize;
        return $this;
    }

    /**
     * SELECT查询
     * @return mixed 成功返回结果标识，失败返回flase。
     */
    public function select()
    {
        $table	= $this->getTableNameWithAlias();
        $join	= !empty($this->join) ? ' '.implode(' ', $this->join) : null;
        $fields	= $this->fields ? $this->fields : '*';
        $where	= $this->where ? $this->where : '1';
        $group	= $this->group ? ' GROUP BY '.$this->group : null;
        $order	= $this->order ? ' ORDER BY '.$this->order : null;
        $limit	= $this->limit ? ' LIMIT '.$this->limit : null;
        $sql	= 'SELECT '.$fields.' FROM '.$table.$join.' WHERE '.$where.$group.$order.$limit;

        $this->reset();
        return $this->query($sql);
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

        if (!$this->select())
            return false;

        $list = array();

        while ($row = mysqli_fetch_assoc($this->result))
            $list[] = $row;

        return $list;
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

        return $this->limit(1)->select() ? mysqli_fetch_assoc($this->result) : false;
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
            $where[] = $this->primaryKey.' != '.$primaryKey;

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

        foreach ($data as $k => $v)
        {
            if (is_numeric($v))
                $v = '\''.$v.'\'';
            else if (is_bool($v))
                $v = intval($v);
            else if (is_null($v))
                $v = 'NULL';
            else
                $v = '\''.$this->escape($v).'\'';

            unset($data[$k]);
            $data['`'.$k.'`'] = $v;
        }

        $table	= $this->getTableNameWithAlias();
        $keys	= implode(', ', array_keys($data));
        $values	= implode(', ', array_values($data));
        $sql	= 'INSERT INTO '.$table.' ('.$keys.') VALUES ('.$values.')';

        return $this->query($sql) ? true : false;
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

        foreach ($data as $k => $v)
        {
            if (is_numeric($v))
                $v = '\''.$v.'\'';
            else if (is_bool($v))
                $v = intval($v);
            else if (is_null($v))
                $v = 'NULL';
            else
                $v = '\''.$this->escape($v).'\'';

            unset($data[$k]);
            $data['`'.$k.'`'] = $v;
        }

        $table	= $this->getTableNameWithAlias();
        $keys	= implode(', ', array_keys($data));
        $values	= implode(', ', array_values($data));
        $sql	= 'INSERT INTO '.$table.' ('.$keys.') VALUES ('.$values.')';

        return $this->query($sql) ? mysqli_insert_id($this->connection) : false;
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

        foreach ($data as $k => $v)
        {
            if (is_int($k))
                $dataList[] = $v;
            else
            {
                if (is_numeric($v))
                    $v = '\''.$v.'\'';
                else if (is_bool($v))
                    $v = intval($v);
                else if (is_null($v))
                    $v = 'NULL';
                else
                    $v = '\''.$this->escape($v).'\'';

                $dataList[] = '`'.$k.'` = '.$v;
            }
        }

        $table	= $this->getTableNameWithAlias();
        $data	= implode(', ', $dataList);
        $where	= $this->where ? $this->where : '1';
        $sql	= 'UPDATE '.$table.' SET '.$data.' WHERE '.$where;

        $this->reset();
        return $this->query($sql) ? true : false;
    }

    /**
     * 自增某个字段的值
     * @param string $fieldName
     * @return bool
     */
    public function increase($fieldName)
    {
        return $this->update(array('`'.$fieldName.'` = `'.$fieldName.'` + 1'));
    }

    /**
     * 自减某个字段的值
     * @param string $fieldName
     * @return bool
     */
    public function decrease($fieldName)
    {
        return $this->update(array('`'.$fieldName.'` = `'.$fieldName.'` - 1'));
    }

    /**
     * 保存数据，如果设置了where则更新，没有则插入。
     * @param array|Bean $data 关联数组或Bean对象
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

        $table	= $this->getTableNameWithAlias();
        $where	= $this->where ? $this->where : '1';
        $sql	= 'DELETE FROM '.$table.' WHERE '.$where;

        $this->reset();
        return $this->query($sql) ? true : false;
    }

    /**
     * 清空表，谨慎调用。
     * @return boolean
     */
    public function truncate()
    {
        $table	= $this->getTableNameWithAlias();
        $sql	= 'TRUNCATE TABLE '.$table;

        $this->reset();
        return $this->query($sql) ? true : false;
    }
}