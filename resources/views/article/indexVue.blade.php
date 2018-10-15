<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>羊绒大衣列表</title>
    <script src="/privateVendor/iview/styles/js/vue.min.js"></script>
    <link rel="stylesheet" href="/privateVendor/iview/styles/iview.css">
    <script src="/privateVendor/iview/styles/js/iview.min.js"></script>
    <script src="/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
</head>
<body>
<div id="app">
    <Col span="11">
        <Card style="width:320px" v-for="(item,index) in list">
            <div style="text-align:center">
                <a :href="item.articleLink" target="_blank">
                    <img :src="item.imageSrc" v-src="item.imageSrc" width="200px">
                </a>
                <h3 v-html="item.title"></h3>
            </div>
        </Card>
    </Col>

    <BackTop></BackTop>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            visible: false,
            list:[
                {
                    title:'加载中，请稍候...',
                    content:''
                }
            ],
            rToken:'{{csrf_token()}}',
        },
        created:function(){
            var _this = this;
            _this.getData()
        },
        methods: {
            getData () {
                var _this = this;
                this.$Loading.start();
                var paramData = {
                    _token:_this.rToken
                };
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