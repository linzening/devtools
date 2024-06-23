#### PHP开发工具集 version 1.2.0

> 请看使用方法

```
composer require linzening/devtools
git@github.com:linzening/devtools.git
```

* Request类
* Spread类
* Lock进程锁
* 数据库结构
* 获取复杂的IP地址


```php
use linzening\devtools\CacheLock; //Lock进程锁

$lock = new CacheLock('id_5');

$lock->lock(); //加锁
$lock->unlock(); //解锁
```

#### composer使用方法

* git tag v1.2.0 #新建tag
* git push --tag #推送tag

> composer地址：

[https://packagist.org/packages/linzening/devtools](https://packagist.org/packages/linzening/devtools)

## Excel表格使用说明

| 参数名称 | 必选 | 类型 | 说明 |
|:---|:--------|:-----|:--------|
| fileName | 否 | 39  | 文件名称 |
| sheetTitle  | 否 | 文字 | 表格大标题 |
| sheetName  | 否 | 文字 | 表格名称 |
| xlsCell  | 否 | 数组 | 字典 |
| expTableData  | 是 | 数组 | 表格数据 |
| H | 否  | 数组或数字  | 表格宽度 |
| local | 否 | bool  | 导出到服务器 |
| cvs  | 否 | bool | 是否导出为cvs |

### 多个表格导出

```php
$fileName = "某某项目数据合并导出";
$Excel['fileName'] = $fileName.date('Y年m月d日-His',time());//文件名称
//$Excel['cellName'] = 9; //可省略
$Excel['H'] = [8,12,18,12,18,18,36,12,14];//如果是数组，即为每一列的宽度
$Excel['H'] = 2; //如果是数字，则为增加的宽度

$Excel['sheetTitle'] = $fileName.'个人详细';//表格大标题 自定义
$Excel['sheetName']='个人详细';//表格名称 自定义
$Excel['xlsCell']=[
    ['i','序号'],
    ['grade','年级'],
    ['classfull','班级'],
    ['realname','姓名'],
    ['studentid','学号'],
    ['datetime','時間'],
    ['content','內容'],
    ['setcount','次數'],
    ['admin_name','管理員'],
];
$Excel['expTableData'] = model('core')->every_user($part,$year); //二维数组，对应`xlsCell`参数

$Excels[] = $Excel;

$parts = array_group_by($list,'partid');

$list0 = [];
foreach ($parts as $key => $value) {
    $item0['partid'] = $value[0]['partid'];
    $item0['partname'] = $value[0]['name'];
    $item0['items'] = [];
    foreach ($value as $key1 => $value1) {
        $item0['items'][] = [
            'major_id' => $value1['major_id'],
            'major_name' => $value1['major_name'],
            'short' => $value1['short'],
        ];
    }
    $list0[] = $item0;
}

$fileName = "某某项目数据不合并导出2";
$Excel['fileName'] = $fileName.date('Y年m月d日-His',time());//or $xlsTitle
$Excel['H'] = [22,20,30,40,30];//横向水平宽度

$Excel['sheetTitle'] = $fileName;//大标题，自定义
$Excel['sheetName'] = '扣除';//表格名称
$Excel['xlsCell']=[
    ['partid','学院ID'],
    ['partname','学院名称'],
    ['major_id','专业ID'],
    ['major_name','专业名称'],
    ['short','专业简称']
];
$Excel['expTableData'] = $list0;
$Excels[] = $Excel;

if($Excels){
    // 导出复杂的Excel
    \linzening\devtools\Spread::excelPuts($Excels);
}
exit('暂无数据导出');

// 导出精简的Excel
\linzening\devtools\Spread::excelPuts($list);
// 导出CVS文件
\linzening\devtools\Spread::cvsPuts($list);
```

+ 导出格式预览

![合并导出](https://cdn.fe80.cn/uploads/2024/472344_excel-put.png)

## 数据库结构导出

```php
echo \linzening\devtools\Structure6::all_tables('system','markdown','','',2);
```

## 模板使用说明

```php
// 输出模板
public function html($page='deny'){
    // $page in deny,deving,holiday,notice,starting,temporary,upgrade-browser
    return \linzening\devtools\Template::template($page);
}
```

## 获取复杂的IP地址

```php
$r = new \linzening\devtools\Request();
$ip = $r->get_client_ip(0,true,'HTTP_X_FORWARDED_FOR');
```