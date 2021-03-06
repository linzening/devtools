<?php
namespace linzening\devtools;
/**
 * 网页模板
 */
class Template
{
    /**
     * [template 获取模板路径]
     * @param  [type] $arr [二维数组]
     * @param  [type] $key [键名]
     * @return [type]      [新的二维数组]
     */
    public static function template($page = 'deny'){
        return file_get_contents( __DIR__ . '/html/'.$page.'.html' );
    }
}