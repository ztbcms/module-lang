### 多语言模块

##### 用法: 可查看TestController

```php
//实例化调用
$this->langApi = new LangApiService('zh_cn');
echo $this->langApi->getText('test');
$res = $this->langApi->getTextArr('test.user');
```

```php
//静态调用
LangApiService::addValue('zh-cn', 'test.test', '二级测试');
echo LangApiService::getValue('zh-cn', 'test.test');
```       