
# 这是什么

这是一个用Tars部署的弹幕活动项目，其中包含3个服务。
涵盖了Http，Tcp服务，使用了Mysql，Redis存储，包含了用户登录、session校验、用户信息获取、弹幕创建、弹幕获取等功能。已经涵盖了平常业务开发的很多方面。

QD.ActHttpServer.obj是一个http服务，对外提供固定端口，这是业务服务，调用后端的User和Comment服务完成业务。
QD.ActCommentServer.CommentObj 是一个tcp服务，主要提供评论的写入和获取，存储在Redis中。
QD.UserService.UserObj 是一个tcp，主要提供用户信息获取，用户登录，用户Session校验。相关数据保存在Mysql中。

index.html是前端页面，连接http服务，实现弹幕展示。
act.tars.local.conf是nginx配置
user_info.sql是QD.UserServer服务的用户信息表


# 如何部署

1.需要在tars平台上创建db配置，在tars平台上，找到QD.UserService 点击服务配置，添加配置，文件名db.json，内容：

~~~json
[
  {
    "host": "mysql.tarsActDemo.local //这是是你的mysql地址",
    "port": 3306,
    "username": "root",
    "password": "password",
    "db": "tars_test",
    "charset": "utf-8",
    "instanceName": "default"
  }
]
~~~

2.导入user_info.sql到你的mysql中。

3.修改QD.ActCommentServer中src/ENVConf.php 中的redis 配置，设置你们自己的redis ip、port

4.依次cd 到各个服务的 src 目录，执行 composer install，安装composer 依赖

5.依次cd 到各个服务的 src 目录，执行 composer run-script deploy 打包代码

6.部署三个服务，QD.ActHttpServer是http服务，需要固定一个端口，选 非tars服务，其他两个是tcp 服务，端口随意，选 tars服务。
(注意部署的时候在平台上配置的ServantName要和我上面写的全称一样，如果你需要修改，请同步修改tars.proto.php，actComment.proto.php和userInfo.proto.php，并从新生成protocol中代码)
7.配置你的nginx，proxy_pass 修改为你的 QD.ActHttpServer服务的ip地址和端口

8.拷贝index.html 到你的/data/website/tarsact/中

9.打开act.tars.local看一下是否可以访问

10.输入一个用户名 yong密码123456执行以下登录

11.发个弹幕试试

12.刷新一下，你发的弹幕出来了吗？恭喜你 O(∩_∩)O哈哈~

#	其他

1. 没有登出功能，如果你需要登出 可以清一下cookie
2. 页面有点丑，大家将就看看
3. tars文件在每个服务的tars目录