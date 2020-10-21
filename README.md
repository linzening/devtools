#### PHP开发工具集 version 1.1.3

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

* git tag v1.1.3 #新建tag
* git push --tag #推送tag

> composer地址：

[https://packagist.org/packages/linzening/devtools](https://packagist.org/packages/linzening/devtools)
