
<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div class="filter-container">
            <h3>字典管理</h3>
        </div>

        <el-button class="filter-item" style="margin-left: 10px;margin-bottom: 15px;" size="small" type="primary" @click="addDictionary">
            添加数据
        </el-button>

        <el-table
            :key="tableKey"
            :data="list"
            highlight-current-row
            style="width: 100%;"
        >
            <el-table-column label="KEY" align="center">
                <template slot-scope="scope">
                    <span>{{ scope.row.key }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center">
                <template slot="header" slot-scope="scope">
                    翻译
                    <el-button size="mini" type="success" v-if="allow_edit == 0" @click="allow_edit = 1">启用编辑</el-button>
                    <el-button size="mini" type="danger" v-if="allow_edit == 1" @click="allow_edit = 0">关闭编辑</el-button>
                </template>
                <template slot-scope="{row}">
                    <template v-for="item in row.values">
                        <el-input placeholder="" v-model="item.value" :readonly="allow_edit == 0" @change="editValue(row.key, item.lang, item.value)">
                            <template slot="prepend">
                                <div style="width: 80px;">{{item.name}}：</div>
                            </template>
                        </el-input>
                    </template>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="text" size="mini" @click="delDictionary(row.key)" style="color: #e74c3c;">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="pagination-container">
            <el-pagination
                layout="prev, pager, next, jumper"
                :total="listQuery.total"
                v-show="listQuery.total > 0"
                :current-page.sync="listQuery.page"
                :page-size.sync="listQuery.limit"
                @current-change="getList"
            >
            </el-pagination>
        </div>

    </el-card>
</div>

<style>
    .filter-container {
        padding-bottom: 10px;
    }

    .pagination-container {
        padding: 32px 16px;
    }
</style>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                tableKey: 0,
                list: [],
                total: 0,
                listQuery: {
                    page: 1,
                    limit: 10,
                    total: 0,
                    keyword: ''
                },
                api_url: "{:api_url('/lang/Lang/dictionary')}",
                allow_edit: 0
            },
            watch: {},
            filters: {},
            methods: {
                getList: function () {
                    var that = this;
                    var data = that.listQuery;
                    data._action = 'getList';
                    that.httpGet(that.api_url, data, function(res){
                        if (res.status) {
                            that.list = res.data.items;
                            that.listQuery.page = res.data.page;
                            that.listQuery.limit = res.data.limit;
                            that.listQuery.total = res.data.total_items;
                        }
                    });
                },
                addDictionary: function () {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加数据',
                        content: "{:api_url('lang/Lang/addDictionary')}",
                        area: ['720px', '550px'],
                        end: function(){
                            that.getList();
                        }
                    });
                },
                delDictionary: function (key) {
                    var that = this;
                    that.$confirm('是否确定删除?', {title: '提示'}).then(function(e){
                        var data = {key: key};
                        data._action = 'delDictionary';
                        that.httpPost(that.api_url, data, function(res){
                            if(res.status){
                                that.getList();
                            }
                        });
                    });
                },
                editValue: function(key, lang, value){
                    var that = this;
                    var data = {key: key, lang: lang, value: value};
                    data._action = 'editValue';
                    that.httpPost(that.api_url, data, function(res){

                    });
                }
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>