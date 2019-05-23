$.ajaxSetup({
    dataType: 'json',
    timeout: 30000,
    beforeSend: function () {
        layer.load(2, {
            offset: '15px'
        });
    },
    complete: function (XMLHttpRequest, status) { //请求完成后最终执行参数
        layer.closeAll('loading');
    }
    , error: function (XMLHttpRequest, textStatus, errorThrown) {
        layer.closeAll('loading');
        layer.msg('服务出现异常，状态：' + textStatus, {offset: 't', icon: 5, anim: 6});
    }
});
var request = {
    send: function (options) {
        var config = $.extend(true, {
            type: "POST",
            data: {},
            url: window.location.href,
            dumpUrl: false,//请求成功后跳转的地址
            callback: false,//请求成功后的回调函数
            showSuccessMsg: true, //是否把返回的消息内容弹出
            callbackAfterShow: true, //是否先弹消息再执行回调,
            alertTime: 1
        }, options);
        $.ajax({
            type: config.type,
            data: config.data,
            url: config.url,
            success: function (data) {
                if (data.result.status == 1) {
                    if (config.showSuccessMsg) {
                        if (config.callbackAfterShow) {
                            layer.msg(data.msg, {
                                icon: 6,
                                time: config.alertTime * 1000
                            }, function () {
                                request.successDone(config.dumpUrl, config.callback, data.result.data)
                            });
                        } else {
                            request.successDone(config.dumpUrl, config.callback, data.result.data);
                            layer.msg(data.msg, {
                                icon: 6,
                                time: config.alertTime * 1000
                            });
                        }
                    } else {
                        request.successDone(config.dumpUrl, config.callback, data.result.data)
                    }
                } else {
                    if(data.msg == 'login'){
                        layer.msg('请先登录', {icon: 5, anim: 6, time: 1000},function () {
                            window.location.href = '/index/sign/login'
                        });
                    }else{
                        layer.msg(data.msg, {icon: 5, anim: 6});
                    }
                }
            }
        })
    },
    successDone(dumpUrl, callback, data) {
        if (dumpUrl) {
            if(dumpUrl == 'reload'){
                window.location.reload();
            }else{
                window.location.href = dumpUrl;
            }
        }
        if (callback) {
            callback(data);
        }
    }
};

layui.use('layer', function () {
    var layer = layui.layer;
    $("#login_out").click(function () {
        layer.confirm('您确定要退出吗?', function (index) {
            request.send({
                'url': '/Index/Sign/logout',
                'dumpUrl': 'reload'
            })
            layer.close(index);
        });
    })
});
