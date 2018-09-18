# swooleEloquent
在swoole中是laravel中的eloquent

# 安装
  composer require wydhit/swoole-eloquent

# 配置

$config=[];//配置参见laravel数据库配置

swooleEloquent\Db::init($config)

这就完成了数据库的配置

# 使用

swooleEloquent\Db::table('user')->limit(10)->get(); table方法之后的参见laravel文档

# model

用户model应该继承swooleEloquent\Model 而不是Eloquent本身的model

model具体用法参见laravel文档

# 注意
  请求结束时应该 调用  swooleEloquent\Db::disConnection(); 释放pdo连接
