<div id="app" style="padding: 8px;" v-cloak>
    <div style="display: flex;padding: 10px;">
         <span style="text-align: left;flex: 1;">
             <span style="font-size: 22px;line-height: 40px;">翻译对照表</span>
         </span>
        <el-button type="primary" @click="addConstant">添加翻译</el-button>
        <el-button type="success" @click="exportConstant">导出翻译</el-button>
    </div>
    <div style="padding: 10px;">
        <el-input v-model="listQuery.key" placeholder="KEY" style="width: 200px;"></el-input>
        <el-input v-model="listQuery.key_name" placeholder="KEY名称" style="width: 200px;"></el-input>
        <el-button class="filter-item" type="primary" @click="search">
            搜索
        </el-button>
    </div>
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
        <el-table-column label="KEY名称" align="center">
            <template slot-scope="scope">
                <span>{{ scope.row.key_name }}</span>
            </template>
        </el-table-column>
        <el-table-column align="center" min-width="300">
            <template slot="header" slot-scope="scope">
                翻译
            </template>
            <template slot-scope="{row}">
                <template v-for="item in row.values">
                    <el-input placeholder="" v-model="item.value" @change="editValue(row.key, item.lang, item.value)">
                        <template slot="prepend">
                            <div style="width: 80px;">{{item.name}}：</div>
                        </template>
                    </el-input>
                </template>
            </template>
        </el-table-column>

        <el-table-column label="操作" align="center" width="100" class-name="small-padding fixed-width">
            <template slot-scope="{row}">
                <?php if(\app\admin\service\AdminUserService::getInstance()->isAdministrator()){
                    echo '<el-button type="text" size="mini" @click="delConstant(row.key)" style="color: #e74c3c;">删除</el-button>';
                } ?>
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
                api_url: "{:api_url('/lang/admin.constant/index')}",
                tableKey: 0,
                list: [],
                listQuery: {
                    page: 1,
                    limit: 10,
                    category_id: "{:input('category_id')}",
                    key: '',
                    key_name: ''
                }
            },
            watch: {},
            filters: {},
            methods: {
                search: function(){
                    var that = this;
                    that.page = 1;
                    that.getList();
                },
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
                exportConstant: function(){
                    var that = this;
                    var url = "{:api_url('lang/admin.constant/exportConstant')}"+"?category_id="+that.listQuery.category_id;
                    window.open(url);
                },
                addConstant: function () {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加翻译',
                        content: "{:api_url('lang/admin.constant/addConstant')}"+"?category_id="+that.listQuery.category_id,
                        area: ['720px', '550px'],
                        end: function(){
                            that.getList();
                        }
                    });
                },
                delConstant: function (key) {
                    var that = this;
                    that.$confirm('是否确定删除?', {title: '提示'}).then(function(e){
                        var data = {key: key};
                        data._action = 'delConstant';
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