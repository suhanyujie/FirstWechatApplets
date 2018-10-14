<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>列表</title>
    <script src="//vuejs.org/js/vue.min.js"></script>
    <link rel="stylesheet" href="//unpkg.com/iview/dist/styles/iview.css">
    <script src="//unpkg.com/iview/dist/iview.min.js"></script>
    <script src="/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
</head>
<body>
<div id="app">
    <i-button @click="show">Click me!</i-button>
    <Col span="11">
        <Card style="width:320px" v-for="(item,index) in list">
            <div style="text-align:center">
                <img src="http://suhanyu.qianbin.vip/static/upload/20181014/20181014093024-153950942427095bc30cb042252.jpg" width="200px">
                <h3 v-html="item.title"></h3>
            </div>
        </Card>
    </Col>

</div>
<div class="container">

</div>
<script>
    new Vue({
        el: '#app',
        data: {
            visible: false,
            list:[
                {
                    title:'title1',
                    content:'content1111'
                }
            ],
            rToken:'{{csrf_token()}}',
        },
        methods: {
            getData () {
                var _this = this;
                this.$Loading.start();
                var paramData = {
                    _token:_this.rToken
                };
                console.log(_this.rToken)
                $.ajax({
                    url: '/article/index',
                    type: 'post',
                    data:paramData,
                    dataType:'json',
                    success: (response) => {
                        this.$Loading.finish();
                        if (response.status != 1) {
                            this.$Notice.open({
                                title: '请求异常',
                                desc: response.message
                            });
                            return;
                        }
                        _this.list = response.data;
                        this.$Notice.open({
                            title: '请求成功',
                            desc: response.message
                        });
                    },
                    error: (response) => {
                        this.$Notice.open({
                            title: '请求异常',
                            desc: response.message
                        });
                        this.$Loading.error();
                    }
                });
            },
            show: function () {
                var _this = this;
                _this.getData()
                this.visible = !this.visible;
                console.log(this.visible);
            }
        }
    })
</script>
</body>
</html>