<?php /*a:1:{s:71:"F:\phpStudy\PHPTutorial\WWW\cmpay\application\admin\view\pay\index.html";i:1552315554;}*/ ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>小红帽科技|草帽聚合支付 - 后台管理 - 支付产品列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/style/admin.css" media="all">
</head>
<body>

<div class="layui-fluid">
    <div class="layui-card">
        <!--头部-->
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">通道代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="code" placeholder="请输入通道代码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">产品名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" placeholder="请输入产品名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-order" lay-submit lay-filter="app-pay-code-list-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>
        <!--数据表-->
        <div class="layui-card-body">
            <div class="app-channel-btns" style="margin-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-useradmin layui-btn-normal layui-btn-sm" data-events="add">添加</button>
                <button class="layui-btn layuiadmin-btn-useradmin layui-btn-danger layui-btn-sm" data-type="all" data-events="batchdel">删除</button>
            </div>
            <table id="app-pay-code-list" lay-filter="app-pay-code-list"></table>

            <script type="text/html" id="buttonTpl">
                {{#  if(d.status == '1'){ }}
                <button class="layui-btn layui-btn layui-btn-xs">启用</button>
                {{#  } else{ }}
                <button class="layui-btn layui-btn-danger layui-btn-xs">禁用</button>
                {{#  } }}
            </script>
            <script type="text/html" id="table-pay-code">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
            </script>
        </div>
    </div>
</div>

<script src="/static/admin/layui/layui.js"></script>
<script>
    layui.config({
        base: '/static/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index','pay', 'table'], function(){
        var $ = layui.$
            ,form = layui.form
            ,table = layui.table;

        //监听搜索
        form.on('submit(app-pay-code-list-search)', function(data){
            var field = data.field;

            //执行重载
            table.reload('app-pay-code-list', {
                where: field
            });
        });

        //事件
        var active = {
            batchdel: function(){
                var checkStatus = table.checkStatus('app-pay-code-list')
                    ,checkData = checkStatus.data; //得到选中的数据

                if(checkData.length === 0){
                    return layer.msg('请选择数据');
                }
                layer.prompt({
                    formType: 1
                    ,title: '敏感操作，请验证口令'
                }, function(value, index){
                    layer.close(index);
                    layer.confirm('确定删除吗？', function(index) {

                        table.reload('app-pay-code-list');
                        layer.msg('等待开发...');
                    });
                });
            }
            ,add: function(){
                layer.open({
                    type: 2
                    ,title: '新增支付方式'
                    ,content: 'addCode'
                    ,maxmin: true
                    ,area: ['80%','60%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'app-pay-code-submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);

                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var l = data.field; //获取提交的字段
                            //提交 Ajax 成功后，静态更新表格中的数据
                            $.ajax({
                                url:'addCode',
                                type:'post',
                                data: l,
                                success:function (res) {
                                    if (res.code == 200) {
                                        //更新数据表
                                        table.reload('app-pay-code-list'); //数据刷新
                                        layer.close(index); //关闭弹层
                                    }
                                    layer.msg(res.msg, {icon: res.code == 200 ? 1: 2,time: 1500});
                                }
                            });
                        });

                        submit.trigger('click');
                    }
                });
            }
        };

        $('.layui-btn.layuiadmin-btn-useradmin').on('click', function(){
            var events = $(this).data('events');
            active[events] ? active[events].call(this) : '';
        });
    });
</script>
</body>
</htm