#### PHP开发工具集 version 1.1.8

* Request类
* Spread类
* Lock进程锁
* 数据库结构


```php
use linzening\devtools\CacheLock; //Lock进程锁

$lock = new CacheLock('id_5');

$lock->lock(); //加锁
$lock->unlock(); //解锁
```

#### composer使用方法

* git tag v1.1.8 #新建tag
* git push --tag #推送tag

> composer地址：

[https://packagist.org/packages/linzening/devtools](https://packagist.org/packages/linzening/devtools)

## Excel表格使用说明

### 多个表格导出

```php
$fileName = "高尔夫球对阵表导出";
$Excel['fileName'] = $fileName.date('Y年m月d日-His',time());//or $xlsTitle
$Excel['cellName'] = 9;
$Excel['H'] = [8,12,18,12,18,18,36,12,14];//横向水平宽度

$Excel['sheetTitle'] = $fileName.'个人详细';//大标题，自定义
$Excel['sheetName']='个人详细';//大标题，自定义
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
$Excel['expTableData'] = model('core')->every_user($part,$year);

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

$fileName = "高尔夫球对阵表导出";
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

\linzening\devtools\Spread::excelPuts($Excels);
```

+ 导出格式预览

![合并导出](https://cdn.xinyunan.cn/uploads/2020/31199_mergecell.png)

## 模板使用说明

```php
// 输出模板
public function html($page='deny'){
    // $page in deny,deving,holiday,notice,starting,temporary,upgrade-browser
    return \linzening\devtools\Template::template($page);
}
```
