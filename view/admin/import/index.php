<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <el-col :sm="24" :md="9">
            <template>
                <div>
                    <el-form ref="elForm" :model="formData" size="medium" label-width="100px">
                        <el-form-item label="项目名称">
                            <el-select v-model="formData.project_id" @change="changeProject">
                                <template v-for="item in projectList">
                                    <el-option :value="item.id" :label="item.name"></el-option>
                                </template>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="文档名称">
                            <el-select v-model="formData.category_id">
                                <template v-for="item in categoryList">
                                    <el-option :value="item.id" :label="item.pre+item.name"></el-option>
                                </template>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="语言">
                            <el-select v-model="formData.lang">
                                <template v-for="item in langList">
                                    <el-option :value="item.lang" :label="item.name"></el-option>
                                </template>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="翻译文件">
                            <el-input v-model="formData.value" type="textarea" rows="20" placeholder='格式：{"key1": "value1", "key2": "value2"}'></el-input>
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
                        _action: 'doImport',
                        project_id: '',
                        category_id: '',
                        lang: '',
                        value: ''
                    },
                    api_url: "{:api_url('/lang/admin.import/index')}",
                    projectList: [],
                    categoryList: [],
                    langList: []
                }
            },
            computed: {},
            watch: {},
            created: function() {},
            mounted: function() {
                this.getProjectList();
                this.getLangList();
            },
            methods: {
                changeProject: function(){
                    var that = this;
                    that.formData.category_id = '';
                    that.categoryList = [];
                    that.getCategoryList();
                },
                submitForm: function() {
                    var that = this;
                    that.httpPost(that.api_url, that.formData, function(res){
                        if (res.status) {
                            layer.alert(res.msg, {title: '提示'});
                        }else{
                            layer.msg(res.msg);
                        }
                    })
                },
                getProjectList: function() {
                    var that = this;
                    var data = {
                        '_action': 'getProjectList'
                    };
                    that.httpGet(that.api_url, data, function (res) {
                        if (res.status) {
                            that.projectList = res.data
                        }
                    })
                },
                getCategoryList: function() {
                    var that = this;
                    var data = {
                        '_action': 'getCategoryList',
                        project_id: that.formData.project_id
                    };
                    that.httpGet(that.api_url, data, function (res) {
                        if (res.status) {
                            that.categoryList = res.data
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
