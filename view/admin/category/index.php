
<div id="app" style="padding: 8px;" v-cloak>
    <el-container style="height: 790px; border: 1px solid #eee">
        <el-aside style="background-color: rgb(238, 241, 246);">
            <div style="display: flex;padding: 10px;">
                    <span style="text-align: center;flex: 1;">
                        <span style="font-size: 22px;line-height: 40px;">目录</span>
                    </span>
                <el-button type="success" @click="addCategory">添加文档</el-button>
            </div>
            <el-tree :data="treeData" :props="defaultTree" @node-click="treeClick" :expand-on-click-node="false">
                <div class="custom-tree-node" slot-scope="{ node, data }">
                    <span>{{ node.label }}</span>
                </div>
            </el-tree>
        </el-aside>
        <el-main>
            <div style="display: flex;padding: 10px;">
                <template v-if="selectedItem.id">
                        <span style="text-align: center;flex: 1;">
                            <span style="font-size: 22px;line-height: 40px;">{{ selectedItem.name }}</span>
                        </span>
                    <el-button type="danger" @click="delCategory">删除文档</el-button>
                </template>
            </div>

            <div class="" style="width: 98%;height: 90%;float: left;">
                <iframe :src="iframe_src" width="100%" height="100%" style="border: 0px solid gainsboro;"></iframe>
            </div>
        </el-main>
    </el-container>
</div>

<style>
    .el-header {
        background-color: #B3C0D1;
        color: #333;
        line-height: 60px;
        padding: 0;
    }
    .el-aside {
        color: #333;
    }
    .el-main {
        padding: 0;
    }

    .custom-tree-node {
        display: flex;
        flex: 1;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
</style>

<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                project_id: "{:input('project_id')}",
                api_url: "{:api_url('/lang/admin.category/index')}",
                treeData: [],
                defaultTree: {},
                selectedItem: {},
                iframe_src: ''
            },
            watch: {},
            filters: {},
            methods: {
                getTreeData: function(is_first){
                    var that = this;
                    var data = {
                        project_id: that.project_id
                    };
                    data._action = 'getCategoryList';
                    that.httpGet(that.api_url, data, function(res){
                        if (res.status) {
                            that.treeData = res.data.items;

                            if(is_first && that.treeData.length > 0){
                                that.selectedItem = {
                                    id: that.treeData[0].id,
                                    name: that.treeData[0].label
                                };
                                that.iframe_src = "{:api_url('/lang/admin.constant/index')}"+"?category_id="+that.treeData[0].id;
                            }
                        }
                    });
                },
                treeClick: function(e){
                    console.log(e);
                    this.selectedItem = {
                        id: e.id,
                        name: e.label
                    };
                    this.iframe_src = "{:api_url('/lang/admin.constant/index')}"+"?category_id="+e.id;
                },
                addCategory: function(){
                    var that = this;
                    layer.open({
                        type: 2,
                        title: '添加文档',
                        content: "{:api_url('lang/admin.category/addCategory')}"+"?project_id="+that.project_id,
                        area: ['720px', '550px'],
                        end: function(){
                            that.getTreeData(false);
                        }
                    });
                },
                delCategory: function(){
                    var that = this;
                    that.$confirm('是否确定删除文档?', {title: '提示'}).then(function(e){
                        var data = {
                            category_id: that.selectedItem.id,
                            _action: 'delCategory'
                        };
                        that.httpPost(that.api_url, data, function(res){
                            if (res.status) {
                                that.selectedItem = {};
                                that.iframe_src = "";
                                that.getTreeData(false);
                            } else {
                                layer.msg(res.msg);
                            }
                        });
                    });
                }
            },
            mounted: function () {
                this.getTreeData(true)
            }
        })
    })
</script>