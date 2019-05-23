## 基于ngnix_swoole的分布式电商系统 [http://www.questionfans.com](http://www.questionfans.com "http://www.questionfans.com")

### 服务器环境
- 1核1G + 30G硬盘 * 2台韩国区域服务器
- centos7.6 + php7.2.18 + swoole4.4 + easyswoole3.2.1

此项目使用最新的easyswoole(基于swoole的http应用框架)实现的常驻内存型的电商系统，总耗时14天。项目初衷是为了本人在今年面试过程中的展示，所以部分业务逻辑只是场景模拟，并未真正实现（例如调用第三方短信接口是直接在代码中sleep5秒），如需商用，请自行进行二次开发。如您愿意提供一次面试邀请，请发邮件给我：chenleib5@126.com。不胜感激！！！


### 实现的功能特性     
- [x] 基于nginx实现2台服务器简单**负载均衡**，服务器1部署ng+swoole承担40%的流量，服务器2部署swoole承担60%的流量    
- [x] 基于easyswoole的**计划任务**定时生成**首页静态API**，让首页无数据库访问压力；方便后期根据用户画像推荐商品（目前用首页商品每分钟更新一次来模拟智能推荐商品功能）
- [x] 基于swoole的**异步任务**发送短信验证码，用户无需等待第三方短信平台请求的时间 
- [x] 基于swoole的**协程连接池**封装mysql连接池和redis连接池
- [x] 利用**websocketServer**实现购物车数量实时更新
- [x] 利用**定时器+异步进程**，实现对订单状态的超时监控
- [x] 利用**redis的有序集合**进行访问数统计   
- [x] 利用**redis的hash结构**存储购物车数据   
- [x] 基于**ElasticSearch**实现商品搜索
- [x] 面向**切面编程**，高度封装验证类，异常处理类。使业务层只需要处理业务逻辑，不需要编写额外其他不相关业务。
- [x] 代码目录分为：http(负责VC) | pool | model(数据层,包括ES,REDIS,DATABASE) | crontab | task | websocket | process
