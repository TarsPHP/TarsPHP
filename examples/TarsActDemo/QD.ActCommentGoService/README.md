#这是什么

这是QD.ActCommentServer的go语言版本，功能和QD.ActCommentServer一样，只要提供给大家做多语言互联的测试。

#如何使用

make && make tar

注意：如果是跨平台编译，需要额外加参数

生成tar包后在平台部署，相关参考TarsGo文档
https://github.com/TarsCloud/TarsGo/blob/master/docs/tars_go_quickstart.md

部署的服务名称是：QD.ActCommentGoService.CommentObj

#PHP 修改

需要修改QD.ActHttpServer服务中FestivalService.php使用ActCommentGoModel

