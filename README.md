# 基于ThinkPHP5的个人博客系统

#### 项目介绍
- github：https://github.com/wojiaofeituan/blog
- 码云：https://gitee.com/zzf199407
- 基于ThinkPHP5框架开发的简单个人博客系统
- 前端基于layui框架，响应式前台
![前台前端大屏幕](http://xldadmin.kahoul.top/1.png "pc.jpg")
![前台前端小屏幕](http://xldadmin.kahoul.top/2.png "mobile.jpg")
- 加入了邮箱登录、GitHub账号登录和QQ登录（请自行配置）

#### 目录结构
~~~
www  WEB部署目录（或者子目录）
├─application            应用目录
│  ├─admin               后台模块目录
│  ├─common              公共模块目录
│  ├─extra              
│  ├─home                前台模块目录
│  ├─module_name         模块目录
│  │  ├─controller       控制器目录
│  │  ├─model            模型目录
│  │  ├─validate         验证器目录
│  │  ├─view             视图目录
│  │  ├─config.php       模块配置文件
│  │  └─ ...            
│  │
│  ├─ .htaccess         
│  ├─command.php        
│  ├─common.php         
│  ├─config.php          公共配置文件
│  ├─database.php        数据库配置文件
│  ├─route.php           路由配置文件
│  └─tags.php           
│
├─extend                 扩展类库目录
│  ├─aliyun-php-sdk-core 阿里云核心SDK
│  └─aliyun-php-sdk-dm   阿里云邮件推送SDK
│ 
├─public                 入口目录
│  ├─.htaccess           apache配置文件
│  ├─favicon.ico         图标
│  ├─index.php           入口文件
│  ├─robots.txt          爬虫协议
│  └─router.php            
│ 
├─thinkphp               thinkphp核心代码目录
├─vendor                 第三方类库目录（Composer依赖库）
├─.gitignore            
├─.travis.yml           
├─LICENSE.txt           
├─README.md             
├─build.php             
├─composer.json          composer 定义文件
└──think                
~~~


