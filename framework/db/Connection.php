<?php
/**
 * Connection.php
 * @author oShine <jqouyang@system.co>
 * @since 2017/4/16 12:15
 */

namespace system\db;


class Connection
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


    /**************** 属性 ****************/

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
     * @var string 表前缀
     */
    protected $prefix;

    /**
     * 构造方法
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $databaseName
     * @param string $charset
     * @param int $port
     * @param string $prefix
     */
    public function __construct($server = self::DEFAULT_SERVER,$port = self::DEFAULT_PORT, $username = self::DEFAULT_USERNAME, $password = self::DEFAULT_PASSWORD, $databaseName = self::DEFAULT_DATABASE, $charset = self::DEFAULT_CHARSET, $prefix = "")
    {
        $this->server		= $server;
        $this->username		= $username;
        $this->password		= $password;
        $this->databaseName	= $databaseName;
        $this->charset		= $charset;
        $this->port = $port;
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getTablePrefix(){
        return $this->prefix;
    }

    /**
     * 连接数据库
     * @return mixed 成功返回连接标识
     * @throws Exception
     */
    public function connect()
    {
        if (!$this->connection)
        {
            $this->connection = mysqli_connect($this->server, $this->username, $this->password, $this->databaseName, $this->port);

            if (mysqli_connect_errno())
            {
                throw new Exception("Can not connect to Database Server".mysqli_connect_error());
            }

            $this->query('SET NAMES \''.$this->charset.'\'');
        }

        return $this->connection;
    }

    /**
     * 查询
     * @param string $sql
     * @return mixed 成功返回结果标识，失败返回false。
     * @throws Exception
     */
    public function query($sql)
    {
        $this->connect();
        $this->parseSql($sql);
        $result = mysqli_query($this->connection, $sql);

        if (!$result)
        {
            throw new Exception("Database query failed:[$sql]".mysqli_error($this->connection));
        }

        return $result;
    }

    /**
     * 转义
     * @param string $string
     * @return string 返回转义后的字符串
     */
    public function escape($string)
    {
        $this->connect();
        return mysqli_real_escape_string($this->connection, $string);
    }

    /**
     * 获取一行记录
     * @param $sql
     * @return mixed 成功返回关联数组，失败返回false。
     */
    public function queryRow($sql)
    {
        $rs = $this->query($sql);
        $row = mysqli_fetch_assoc($rs);
        mysqli_free_result($rs);
        return empty($row)?false:$row;
    }

    /**
     * 获取所有的记录
     * @param $sql
     * @return array
     */
    public function queryAll($sql)
    {
        $rs = $this->query($sql);
        $list = array();
        while($row = mysqli_fetch_assoc($rs)){
            $list[] = $row;
        }
        mysqli_free_result($rs);
        return $list;
    }


    /**
     * 获取单个记录
     * @param $sql
     * @return string|false
     */
    public function queryScalar($sql)
    {
        $rs = $this->query($sql);
        $row = mysqli_fetch_array($rs,MYSQLI_NUM);
        mysqli_free_result($rs);
        return isset($row) && isset($row[0])?$row[0]:false;
    }

    /**
     * Returns the auto generated id used in the last query
     * @return int|string
     */
    public function getLastId(){
        $this->connect();
        return mysqli_insert_id($this->connection);
    }

    /**
     * @param $sql
     * @return mixed
     */
    protected function parseSql(&$sql){
        $sql = preg_replace("/{{(\w+)}}/",$this->getTablePrefix().'${1}' ,$sql);
        return $sql;
    }

    /**
     * 插入数据
     * @param mixed $table 表名
     * @param mixed $data 关联数组或Bean对象
     * @return mixed 成功返回插入的主键值，失败返回false。
     */
    public function insert($table,$data)
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
                $v = '\'' . $this->escape($v) . '\'';

            unset($data[$k]);
            $data['`' . $k . '`'] = $v;
        }

        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));
        $sql = 'INSERT INTO ' . $table . ' (' . $keys . ') VALUES (' . $values . ')';

        return $this->query($sql) ? true : false;
    }
}