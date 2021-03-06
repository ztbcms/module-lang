
<div id="app" style="padding: 8px;" v-cloak>
    <el-card>
        <div class="filter-container">
            <h3>语言管理</h3>
        </div>

        <el-button class="filter-item" style="margin-left: 10px;margin-bottom: 15px;" size="small" type="primary" @click="addLang">
            添加语言
        </el-button>

        <el-table
            :key="tableKey"
            :data="list"
            highlight-current-row
            style="width: 100%;"
        >
            <el-table-column label="代码" align="center">
                <template slot-scope="{row}">
                    <span style="color:gray">{{row.lang}}</span>
                </template>
            </el-table-column>
            <el-table-column label="名称" align="center">
                <template slot-scope="{row}">
                    <span style="color:gray">{{row.name}}</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                    <el-button type="text" size="mini" @click="delLang(row.id)" style="color: #e74c3c;">删除</el-button>
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
                api_url: "{:api_url('/lang/Lang/index')}"
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
                            that.list = res.data;
                        }
                    });
                },
                addLang: function () {
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加语言',
                        content: "{:api_url('lang/Lang/addLang')}",
                        area: ['720px', '550px'],
                        end: function(){
                            that.getList();
                        }
                    });
                },
                delLang: function (id) {
                    var that = this;
                    that.$confirm('是否确定删除?', {title: '提示'}).then(function(e){
                        var data = {id: id};
                        data._action = 'delLang';
                        that.httpPost(that.api_url, data, function(res){
                            if(res.status){
                                that.getList();
                            }
                        });
                    });
                }
            },
            mounted: function () {
                this.getList();
            }
        })
    })
</script>