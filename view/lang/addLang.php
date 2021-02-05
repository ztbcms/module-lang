<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="24" :md="9">
            <template>
                <div>
                    <el-form ref="elForm" :model="formData" size="medium" label-width="100px">
                        <el-form-item label="代码">
                            <el-input v-model="formData.lang" placeholder="请输入代码" clearable :style="{width: '200px'}"></el-input>
                        </el-form-item>
                        <el-form-item label="名称">
                            <el-input v-model="formData.name" placeholder="请输入名称" clearable :style="{width: '200px'}"></el-input>
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
                        _action: 'addLang',
                        lang: '',
                        name: ''
                    },
                    api_url: "{:api_url('/lang/Lang/addLang')}",
                    langList: []
                }
            },
            computed: {},
            watch: {},
            created: function() {},
            mounted: function() {
            },
            methods: {
                submitForm: function() {
                    var that = this;
                    that.httpPost(that.api_url, that.formData, function(res){
                        if (res.status) {
                            layer.msg('添加成功', {time: 1000}, function(){
                                parent.layer.closeAll();
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    })
                }
            }
        });
    });
</script>

<style>

</style>
