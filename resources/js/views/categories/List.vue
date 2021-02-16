<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 250px;" class="filter-item" @keyup.native="handleFilter" />
      <el-select v-model="listQuery.sort" style="width: 150px;" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="$t('table.'+item.label)" :value="item.key" />
      </el-select>
      <div style="float: right;">
        <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
          {{ $t('table.add') }}
        </el-button>
        <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
          {{ $t('table.export') }}
        </el-button>
      </div>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      fit
      highlight-current-row
      style="width: 100%;border-radius: .428rem; word-break: break-word;"
      @sort-change="sortChange"
    >
      <el-table-column label="" prop="id" align="center" width="40">
        <template>
          <span>{{ }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.name')" width="350px">
        <template slot-scope="{row}">
          <span style="font-size: 12pt;">{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.description')" align="left">
        <template slot-scope="{row}">
          <span>{{ row.description }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="400" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="success" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="checkRole(['admin','manager']) && row.id!='deleted'" size="mini" type="danger" @click="handleDelete(row.id)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="right" label-width="170px" style="width: 650px; margin-left:50px;">
        <el-form-item :label="$t('categories.name')" :placeholder="temp.name" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item :label="$t('categories.description')">
          <el-input v-model="temp.description" :autosize="{ minRows: 2, maxRos: 4}" type="textarea" :placeholder="categories.description" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          {{ $t('table.confirm') }}
        </el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('route.articleDetails')" :visible.sync="modalCategoryPreview">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="170px" style="width: 400px; margin-left:50px;">
        <el-form-item :label="$t('table.code')" prop="code">
          <el-input v-model="temp.code" />
        </el-form-item>
        <el-form-item :label="$t('table.title')" prop="title">
          <el-input v-model="temp.title" />
        </el-form-item>
        <el-form-item :label="$t('table.remark')">
          <el-input v-model="temp.remark" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Please input" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="modalCategoryPreview = false">
          {{ $t('table.cancel') }}
        </el-button>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogPvVisible" title="Reading statistics">
      <el-table :data="pvData" border fit highlight-current-row style="width: 100%">
        <el-table-column prop="key" label="Channel" />
        <el-table-column prop="pv" label="Pv" />
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogPvVisible = false">{{ $t('table.confirm') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, fetchPv, showCategory, createCategory, updateCategory, deleteCategory } from '@/api/category';
import waves from '@/directive/waves'; // Waves directive
import { parseTime } from '@/utils';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JA', display_name: 'Japan' },
  { key: 'VI', display_name: 'Vietnam' },
];

// arr to obj ,such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name;
  return acc;
}, {});

export default {
  name: 'ComplexTable',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type];
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        title: undefined,
        type: undefined,
        sort: '+id',
        keyword: '',
      },
      categories: '',
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      checkRole,
      sortOptions: [{ label: 'ascending', key: '+id' }, { label: 'descending', key: '-id' }],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        remark: '',
        title: '',
        type: '',
        code: '',
        last_code: '',
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit',
        create: 'Create',
      },
      dialogPvVisible: false,
      modalCategoryPreview: false,
      pvData: [],
      rules: {
        name: [{ required: true, message: this.$t('discounts.title_is_required'), trigger: 'blur' }],
      },
      downloadLoading: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await fetchList(this.listQuery);
      this.list = data.items.data;
      this.total = data.items.total;
      var maxId = Math.max(...this.list.map(el => el.code)); // racuna poslednji code iz tabele
      this.last_code = (maxId || this.last_code);
      var newWatcherId = parseInt(this.last_code) + 1;
      this.last_code = newWatcherId;
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
    },
    async getCodes() {
      // test
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: 'Successful operation',
        type: 'success',
      });
      row.status = status;
    },
    sortChange(data) {
      const { prop, order } = data;
      if (prop === 'id') {
        this.sortByID(order);
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id';
      } else {
        this.listQuery.sort = '-id';
      }
      this.handleFilter();
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        code: '',
        status: 'published',
        type: '',
      };
    },
    handleCreate() {
      this.resetTemp();
      this.dialogStatus = 'create';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createCategory(this.temp).then(() => {
            this.list.unshift(this.temp);
            this.dialogFormVisible = true;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 3000,
            });
            this.dialogFormVisible = false;
          });
        }
      });
    },
    previewArticle(row) {
      showCategory(row.id);
      this.modalCategoryPreview = true;
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row); // copy obj
      this.dialogStatus = 'update';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp);
          updateCategory(tempData).then(() => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, this.temp);
                break;
              }
            }
            this.dialogFormVisible = false;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.updated_successfully'),
              type: 'success',
              duration: 2000,
            });
          });
        }
      });
    },
    /* handleDelete(id) {
      deleteCategory(id).then(() => {
        this.$notify({
          title: this.$t('table.success'),
          message: this.$t('table.deleted_successfully'),
          type: 'success',
          duration: 2000,
        });
        this.getList();
      });
    },*/
    handleDelete(id) {
      this.$confirm(this.$t('table.sure'), this.$t('discounts.warning'), {
        confirmButtonText: this.$t('permission.confirm'),
        cancelButtonText: this.$t('permission.cancel'),
        type: 'warning',
      })
        .then(async() => {
          await deleteCategory(id);
          this.$notify({
            title: this.$t('table.success'),
            message: this.$t('table.deleted_successfully'),
            type: 'success',
            duration: 3000,
          });
          this.getList();
        })
        .catch(err => {
          console.log(err);
        });
    },
    handleFetchPv(pv) {
      fetchPv(pv).then(response => {
        this.pvData = response.data.pvData;
        this.dialogPvVisible = true;
      });
    },
    handleDownload() {
      this.downloadLoading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', this.$t('categories.code'), this.$t('articles.categories'), this.$t('table.description')];
        const filterVal = ['id', 'code', 'name', 'description'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list',
        });
        this.downloadLoading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j]);
        } else {
          return v[j];
        }
      }));
    },
    currencyFormatEU(num, fixed) {
      if (fixed !== 1){
        return (
          num
            .toFixed(fixed) // always 'fixed' decimal digits
            .replace('.', ',') // replace decimal point character with ,
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
        ); // use . as a separator
      }
      return num;
    },
  },
};
</script>
<style lang="scss" scoped>
  .pagination-container {
    width: 100%;
    border-radius: 15px;
  }
  .categoryList{
    width: 40% !important;
    float: left;
  }
  .categoryList2{
    width: 40% !important;
    float: right;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
