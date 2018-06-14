<?php

/**
 * 分页
 *
 * 可定义常量：
 * PAGE_PARAM:            页号参数名称
 * DEFAULT_PAGE_SIZE:    默认每页数量
 *
 * @author Jack Chan
 * @since 2016-05-18
 */
class Page
{
    /**
     * 页号参数名称
     */
    const PAGE_PARAM = 'p';

    /**
     * 默认每页显示数量
     */
    const DEFAULT_PAGE_SIZE = 20;

    /**
     * 页号
     */
    protected $pageId;

    /**
     * 每页数量
     */
    protected $pageSize;

    /**
     * 总数量
     */
    protected $count;

    /**
     * 总页数
     */
    protected $pageCount;

    /**
     * 构造方法
     * @param int $pageId 页号，如果为null则从GPC参数中初始化。
     * @param int $pageSize 每页显示数量，如果为null则从默认值初始化。
     */
    public function __construct($pageId = null, $pageSize = null)
    {
        // 定义页号参数名称
        defined('PAGE_PARAM') or define('PAGE_PARAM', self::PAGE_PARAM);

        // 定义默认每页显示数量
        defined('DEFAULT_PAGE_SIZE') or define('DEFAULT_PAGE_SIZE', self::DEFAULT_PAGE_SIZE);

        // 初始化页号
        if ($pageId == null && isset($_REQUEST[PAGE_PARAM]))
            $pageId = $_REQUEST[PAGE_PARAM];

        $pageId = intval($pageId);

        if ($pageId < 1)
            $pageId = 1;



        // 初始化每页数量
        if ($pageSize == null && isset($_REQUEST['page_size'])){
            $pageSize = $_REQUEST['page_size'];
        }else if($pageSize == null){
            $pageSize = DEFAULT_PAGE_SIZE;
        }

        $pageSize = intval($pageSize);

        if ($pageSize < 1)
            $pageSize = DEFAULT_PAGE_SIZE;

        $this->pageId = $pageId;
        $this->pageSize = $pageSize;
    }

    /**
     * 获取页号
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * 获取每页数量
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * 设置总数量，总页数同时也自动初始化，页号超出也会自动修正。
     * @param int $count
     */
    public function setCount($count)
    {
        $count = intval($count);

        if ($count < 0)
            $count = 0;

        $this->count = $count;
        $this->pageCount = ceil($count / $this->pageSize);

        if ($this->pageId > $this->pageCount && $this->pageCount > 0)
            $this->pageId = $this->pageCount;
    }

    /**
     * 获取总数量
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * 获取总页数
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * 显示
     * @return string 返回分页HTML
     */
    public function display()
    {
        if ($this->count <= 0)
            return '';

        $pageId = $this->pageId;
        $pageCount = $this->pageCount;

        // 当前页数量
        $currentCount = $pageId == $pageCount ? $this->count - $this->pageSize * ($pageCount - 1) : $this->pageSize;

        $left = $pageId > 1 ? '<a href="' . self::getBaseUrl(1,$this->pageSize) . '">首页</a> <a href="' . self::getBaseUrl($pageId - 1,$this->pageSize) .'">上一页</a>' : '';

        $middle = "";
        for ($i = ($pageId - 5) >= 1 ? ($pageId - 5) : 1; $i < $pageId; $i++) {
            $middle .= '<a href="' . self::getBaseUrl($i,$this->pageSize) . '">' . $i . '</a>';
        }

        for ($i = $pageId; $i <= (($pageId + 5) < $pageCount ? $pageId + 5 : $pageCount); $i++) {
            if ($i == $pageId) {
                $middle .= '<a href="' . self::getBaseUrl($i,$this->pageSize) . '" class="current">' . $i . '</a>';
            } else {
                $middle .= '<a href="' . self::getBaseUrl($i,$this->pageSize) . '">' . $i . '</a>';
            }
        }

        $right = $pageId < $pageCount ? '<a href="' . self::getBaseUrl($pageId+1,$this->pageSize). '">下一页</a> <a href="' . self::getBaseUrl($pageCount,$this->pageSize). '">尾页</a>' : '';
        $pagesizeHtml = "<select onchange='javascript:window.location.href=\"".self::getBaseUrl($pageId)."&page_size=\"".'+this.value'.";'>";
        foreach(array("20","50","100") as $v){
            if($v == $this->pageSize){
                $pagesizeHtml.= "<option value='$v' selected='selected'>$v</option>";
            }else{
                $pagesizeHtml.= "<option value='$v'>$v</option>";
            }
        }
        $pagesizeHtml.="</select>";

        return '每页：'.$pagesizeHtml.'&nbsp;&nbsp;共计：' . $currentCount . '/' . $this->count . '&nbsp;&nbsp;页数：' . $pageId . '/' . $pageCount . '&nbsp;&nbsp;' . ($left && $right ? $left . ' ' . $middle . $right : $left . $middle . $right);
    }

    /**
     * 转化成字符串，调用显示方法。
     */
    public function __toString()
    {
        return $this->display();
    }

    /**
     * 获取页面基地址，最后的参数是页号参数（参数后有等号但没有值，以便加上页号值）。
     * @param bool|false|int $page
     * @param bool|int $page_size
     * @return string
     */
    protected static function getBaseUrl($page = false,$page_size = false)
    {
        $url = UrlUtil::getFullPath();
        $params = array_merge($_GET, $_POST);
        $pairs = array();

        $i = 0;

        foreach ($params as $k => $v) {
            if ($k == PAGE_PARAM || $k == "page_size")
                continue;

            // 第一个参数为控制器参数，如果定义了控制器分组分隔符则需要还原控制器参数名（控制器分组分隔符可能会被替换成下划线）。
            if ($i++ == 0 && defined('CONTROLLER_SEPARATOR'))
                $k = str_replace('_', CONTROLLER_SEPARATOR, $k);

            if ($v === null || $v === '')
                $pairs[] = $k;
            else
                $pairs[] = $k . '=' . $v;
        }

        if(!empty($page))
            $pairs[] = PAGE_PARAM . '='.$page;
        if(!empty($page_size))
            $pairs[] =  "page_size". '='.$page_size;

        $url .= '?' . implode('&', $pairs);
        return $url;
    }

    public function getLimit()
    {
        return ($this->getPageId() - 1) * $this->getPageSize() . "," . $this->getPageSize();
    }
}