<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-31 15:39:06
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-31 16:02:58
 */

//创建内存表,词表总共有1024行
$table = new swoole_table(1024);

//内存表增加列
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,3);
$table->create();

//往内存表中增加内容
$table->set('test',['id'=>1,'name'=>'handsome','age'=>18]);

//自增
$table->incr('test','age',2);

print_r($table->get('test'));
