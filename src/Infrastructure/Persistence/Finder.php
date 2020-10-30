<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

class Finder
{
	public static $sql = '';
	public static $instance = NULL;
	public static $prefix = '';
	public static $where = array();
	public static $control = ['', ''];

	// $table == name of table
	// $cols = column names

	public static function select($table, $cols = NULL )
	{
		self::$instance = new Finder();
		if($cols) {
			self::$prefix = 'SELECT '.$cols.' FROM '.$table;
		} else {
			self::$prefix = 'SELECT * FROM '.$table;
		}
		return self::$instance;
	}

	public static function where($criteria = NULL)
	{
		self::$where[0] = ' WHERE ' . $criteria;
		return self::$instance;
	}

	public static function like($criteria, $like)
	{
		self::$where[] = trim($criteria . ' LIKE ' . $like);
		return self::$instance;
	}

	public static function and($criteria = NULL)
	{
		self::$where[] = trim('AND '.$criteria);
		return self::$instance;

	}

	public static function or($or = NULL)
	{
		self::$where[] = trim('OR '.$or);
		return self::$instance;
	}

	public static function not($a = NULL)
	{
		self::$where[] = trim('NOT ' . $a);
		return self::$instance;
	}

	public static function limit($limit)
	{
		self::$control[0] = ' LIMIT ' . $limit;
		return self::$instance;
	}

	public static function offset($offset)
	{
		self::$control[1]='OFFSET '.$offset;
		return self::$instance;
	}

	public static function getSql()
	{
		self::$sql = self::$prefix.implode(' ', self::$where).implode(' ',self::$control);
		preg_replace('/ /', ' ', self::$sql);
		return self::$sql;
	}

	public static function resetAll()
	{
		self::$sql = '';
		self::$instance = NULL;
		self::$prefix = '';
		self::$where = array();
		self::$control = ['', ''];
	}

}
