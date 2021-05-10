<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="24" :md="9">
            <template>
                <div>
                    <el-form ref="elForm" :model="formData" size="medium" label-width="100px">
                        <el-form-item label="KEY">
                            <el-tooltip class="item" effect="dark" content="例如：user.login" placement="right" open-delay="500">
                                <el-input v-model="formData.key" placeholder="请输入KEY" clearable :style="{width: '200px'}"></el-input>
                            </el-tooltip>
                        </el-form-item>
                        <el-form-item label="KEY名称">
                            <el-input v-model="formData.key_name" placeholder="请输入KEY名称" clearable :style="{width: '200px'}"></el-input>
                        </el-form-item>
                        <el-form-item label="翻译">
                            <template v-for="item in langList">
                                <el-input :placeholder="'请输入'+item.name" v-model="formData.values[item.lang]" :style="{width: '380px'}">
                                    <template slot="prepend">
                                        <div style="width: 80px;">{{item.name}}：</div>
                                    </template>
                                </el-input>
                            </template>
                            <template v-if="langList.length == 0"><span style="color: #AAAAAA;">暂无数据</span></template>
                        </el-form-item>

                        <el-form-item size="large">
                            <el-button type="primary" @click="submitForm">提交</el-button>
                        </el-form-item>

                    </el-form>
                </div>
            </template>
        </el-col>
    </el-card>
</div>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: function() {
                return {
                    formData: {
                        _action: 'addConstant',
                        category_id: "{:input('category_id')}",
                        key: '',
                        key_name: '',
                        values: {}
                    },
                    api_url: "{:api_url('/lang/admin.constant/addConstant')}",
                    langList: []
                }
            },
            computed: {},
            watch: {},
            created: function() {},
            mounted: function() {
                this.getLangList();
            },
            methods: {
                submitForm: function() {
                    var that = this;
                    that.httpPost(that.api_url, that.formData, function(res){
                        if (res.status) {
                            layer.msg('添加成功', {time: 1000}, function(){
                                that.formData.key = '';
                                that.formData.key_name = '';
                                that.formData.values = {};
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    })
                },
                getLangList: function() {
                    var that = this;
                    var data = {
                        '_action': 'getLangList'
                    };
                    that.httpGet(that.api_url, data, function (res) {
                        if (res.status) {
                            that.langList = res.data
                        }
                    })
                }
            }
        });
    });
</script>

<style>

</style>
