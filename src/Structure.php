<?php
namespace linzening\devtools;
/**
 * 数据库生成数据库文档
 */
use think\Db;
use think\facade\Config;
use think\Controller;
class Structure extends Controller
{
    /**
     * [array_group_by ph] 获取数据列表导出文档
     * @param  [database] 数据库名称
     * @param  [type] 输出格式 [array,markdown]
     * @param  [table] 表名称，支持LIKE null,like
     * @param  [query] 查询模式 null,like
     * @param  [simple] 简单模式 只返回列名和注释
     * @return [type]      [新的二维数组]
     */
    public static function all_tables($database = '', $type = 'array',$table = '', $query = '', $simple = '')
    {
        $type = $type == '' ? 'array' : $type;
        if(empty($database)){
            $database = Config::get('database.database','');
        }

        if(empty($database)){
            exit("数据库名为空");
        }

        $sql = "SELECT TABLE_NAME,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA ='{$database}' LIMIT 200";

        $tables = Db::query($sql);
        $tablearr = array_column($tables,'TABLE_NAME');
        $tabletxt = implode('\',\'',$tablearr);

        $where = '';
        if(in_array($query , ['LIKE','like']) && !empty($table) ){
            $where = ' AND TABLE_NAME LIKE "%'.$table.'%"';
        }

        $sql = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_TYPE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,IS_NULLABLE,COLUMN_DEFAULT,COLUMN_COMMENT
        FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA ='{$database}' AND TABLE_NAME  IN ('{$tabletxt}') {$where} ORDER BY ORDINAL_POSITION ASC";
        $tablelist = Db::query($sql);
        $tablelist = self::array_group_by($tablelist,'TABLE_NAME');
        $ntables = [];
        foreach ($tables as $key => $value) {
            $value['column'] = $tablelist[$value['TABLE_NAME']] ?? [];
            $value['id'] = $key + 1;
            if(!empty($value['column'])){
                $value['fields'] = implode(',',array_column($tablelist[$value['TABLE_NAME']],'COLUMN_NAME'));
                $ntables[] = $value;
            }
        }

        if($type == 'markdown'){
            $mark = '';
            foreach ($ntables as $key => $table) {
                $mark .= PHP_EOL;
                $mark .= '**'.$table['id'].'.&nbsp;'.$table['TABLE_NAME'].'&nbsp;'.($table['TABLE_COMMENT']?:'-').'**'. PHP_EOL. PHP_EOL;

                if( $simple == 1 ){
                    $mark .= '|  字段名  | 说明  |' . PHP_EOL;
                    $mark .= '| ------ | ------ |' . PHP_EOL;
                    foreach ($table['column'] as $key1 => $data) {
                        $mark .= '| ' . $data['COLUMN_NAME'] . ' | ' . $data['COLUMN_COMMENT'] . ' | ' . PHP_EOL;
                    }
                }elseif( $simple == 2 ){
                    $mark .= '|  字段名  | 必填 | 类型 | 说明  |' . PHP_EOL;
                    $mark .= '| ------ | --- | --- |------ |' . PHP_EOL;
                    foreach ($table['column'] as $key1 => $data) {
                        $mark .= '| ' . $data['COLUMN_NAME'] . ' | ' . ($data['IS_NULLABLE']=='YES'?'否':'是') . ' | ' . $data['DATA_TYPE'] . ' | ' . $data['COLUMN_COMMENT'] . ' | ' . PHP_EOL;
                    }
                }else{
                    $mark .= '|  字段名  |  数据类型  | 字段类型 |长度|是否为空| 默认值|说明  |' . PHP_EOL;
                    $mark .= '| ------ | ------ | ------ |------ | ------ | ------ | ------ |' . PHP_EOL;
                    foreach ($table['column'] as $key1 => $data) {
                        $mark .= '| ' . $data['COLUMN_NAME'] . ' | ' . $data['COLUMN_TYPE'] . ' | ' . $data['DATA_TYPE'] . ' | ' . $data['CHARACTER_MAXIMUM_LENGTH'] . ' | '
                        . $data['IS_NULLABLE'] . ' | ' . $data['COLUMN_DEFAULT'] . ' | '. $data['COLUMN_COMMENT'] . ' | ' . PHP_EOL;
                    }
                }
            }
            return $mark;
        }

        return $ntables;
    }

    /**
     * [tableinfo 单个表]
     * @param  [type] $arr [二维数组]
     * @param  [type] $key [键名]
     * @return [type]      [新的二维数组]
     */
    public function tableinfo($table = '')
    {
        $sql = "SELECT COLUMN_NAME,COLUMN_TYPE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,IS_NULLABLE,COLUMN_DEFAULT,COLUMN_COMMENT
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA ='' AND TABLE_NAME = '{$table}' ";
        return Db::query($sql);
    }

    /**
     * [array_group_by ph]
     * @param  [type] $arr [二维数组]
     * @param  [type] $key [键名]
     * @return [type]      [新的二维数组]
     */
    public static function array_group_by($arr, $key){
        $grouped = array();
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge($value, array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }
}
