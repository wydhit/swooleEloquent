# swooleEloquent
在swoole中是laravel中的eloquent

# 配置

$config=[];//配置参见laravel数据库配置

swooleEloquent\Db::init($config)

这就完成了数据库的配置

# 使用

swooleEloquent\Db::table('user')->limit(10)->get(); table方法之后的参见laravel文档

# model

用户model应该继承swooleEloquent\Db 而不是Eloquent本身的model

model具体用法参见laravel文档
