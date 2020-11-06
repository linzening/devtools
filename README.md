#### PHP开发工具集 version 1.1.6

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

* git tag v1.1.6 #新建tag
* git push --tag #推送tag

> composer地址：

[https://packagist.org/packages/linzening/devtools](https://packagist.org/packages/linzening/devtools)

## Excel表格使用说明

### 表格合并导出

```php
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
$Excel['cellName'] = 5;
$Excel['bArray'] = [2,3];
$Excel['H'] = ['A'=>22,'B'=>20,'C'=>30,'D'=>40,'E'=>30];//横向水平宽度
$Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
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

\linzening\devtools\Spread::mergePuts($Excels);
```

+ 导出格式预览

![合并导出](/src/assets/mergecell.png)