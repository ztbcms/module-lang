
<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div class="filter-container">
            <h3>项目管理</h3>
        </div>

        <el-button class="filter-item" style="margin-left: 10px;margin-bottom: 15px;" size="small" type="primary" @click="addProject">
            添加项目
        </el-button>

        <el-table
            :key="tableKey"
            :data="list"
            highlight-current-row
            style="width: 100%;"
        >
            <el-table-column label="ID" align="center">
                <template slot-scope="{row}">
                    <span style="color:gray">{{row.id}}</span>
                </template>
            </el-table-column>
            <el-table-column label="名称" align="center">
                <template slot-scope="{row}">
                    <span style="color:gray">{{row.name}}</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="text" size="mini" @click="editProject(row.id, row.name)">编辑</el-button>
                    <el-button type="text" size="mini" @click="category(row.id)" style="color: #67C23A;">编辑文档</el-button>
                    <el-button type="text" size="mini" @click="exportProject(row.id)" style="color: #E6A23C;">导出文档翻译</el-button>
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
                    limit: 20,
                    total: 0,
                    keyword: ''
                },
                api_url: "{:api_url('/lang/admin.project/index')}"
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
                            that.listQuery.total = res.data.total_items;
                        }
                    });
                },
                addProject: function () {
                    var that = this;
                    that.$prompt('请输入项目名称', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        beforeClose: function(action, instance, done){
                            if(action == 'confirm'){
                                var data = {name: instance.inputValue};
                                data._action = 'addProject';
                                that.httpPost(that.api_url, data, function(res){
                                    if(res.status){
                                        layer.msg(res.msg, {time: 1000}, function(){
                                            that.getList();
                                        });
                                        done();
                                    }else{
                                        layer.msg(res.msg, {time: 1000});
                                    }
                                });
                            }else{
                                done();
                            }
                        }
                    }).then(function(e){}).catch(function(){});
                },
                editProject: function (id, name) {
                    var that = this;
                    that.$prompt('请输入项目名称', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        inputValue: name,
                        beforeClose: function(action, instance, done){
                            if(action == 'confirm'){
                                var data = {id: id, name: instance.inputValue};
                                data._action = 'editProject';
                                that.httpPost(that.api_url, data, function(res){
                                    if(res.status){
                                        layer.msg(res.msg, {time: 1000}, function(){
                                            that.getList();
                                        });
                                        done();
                                    }else{
                                        layer.msg(res.msg, {time: 1000});
                                    }
                                });
                            }else{
                                done();
                            }
                        }
                    }).then(function(e){}).catch(function(){});
                },
                category: function(id){
                    var url = "{:api_url('/lang/admin.category/index')}"+"?project_id="+id;
                    layer.open({
                        type: 2,
                        title: '编辑文档',
                        content: url,
                        area: ['90%', '90%']
                    });
                },
                exportProject: function(id){
                    var url = "{:api_url('/lang/admin.project/exportProject')}"+"?project_id="+id;
                    window.open(url);
                }
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>