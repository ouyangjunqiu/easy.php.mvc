<?php
/**
 * 默认控制器
 * @author Jack Chan
 * @since 2016-05-09
 */
class IndexController extends Controller
{
	public function index()
	{
		header('Location: ./?admin');
	}
}