<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 250px;" class="filter-item" @keyup.native="handleFilter" />
      <el-select v-model="listQuery.sort" style="width: 150px" class="filter-item" @change="handleFilter">
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
      <el-table-column :label="$t('table.code')" prop="id" sortable="custom" align="center" width="90">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.name')" min-width="120px">
        <template slot-scope="{row}">
          <span style="font-size: 12pt; margin-top: 2px; cursor: pointer;" @click="previewArticle(row)">{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.price') + ' (' + $t('articles.currency') + ')'" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ currencyFormatEU(scope.row.price/100, 2) }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.discount') + ' (%) '" width="80px" align="center">
        <template slot-scope="scope">
          <span>{{ currencyFormatEU(scope.row.discount/10, 1) }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.in_stock') + ' (' + $t('articles.pieces') + ')'" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.amount }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('articles.category')" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.categories.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('stores.location')" width="180px" align="center">
        <template slot-scope="scope">
          <div v-show="scope.row.amount > 0">
            <div v-if="scope.row.store.length > 0">
              <div class="hasTooltip" @mouseover="test">{{ scope.row.store.length }}
                <span>
                  <div v-for="(n, index) in scope.row.store" :key="index" style="padding: 2px 5px;">
                    {{ scope.row.store[index].address }}
                  </div>
                </span>
              </div>
            </div>
            <div v-else>-</div>
          </div>
        </template>
      </el-table-column>
      <el-table-column v-if="checkRole(['admin','manager', 'editor'])" :label="$t('table.actions')" align="center" width="200" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-if="checkRole(['admin','manager', 'editor'])" type="success" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="checkRole(['admin','manager']) && row.id!='deleted'" size="mini" type="danger" @click="handleDelete(row.id)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus] != null ? $t('articles.' + textMap[dialogStatus]) : ''" :visible.sync="dialogFormVisible" style="margin-top: -85px;">
      <el-form ref="dataForm" :rules="rules" :model="temp">
        <div class="mainForm">
          <div class="formLeft">
            <el-form-item v-show="textMap[dialogStatus]=='Edit_Article'" :label="$t('table.code')" prop="code">
              <span> {{ temp.code }} </span>
            </el-form-item>
            <el-form-item :label="$t('table.title')">
              <el-input v-model="temp.title" />
            </el-form-item>
            <el-form-item :label="$t('articles.categories')" width="180px" align="center">
              <el-select v-model="temp.category" :placeholder="$t('articles.categories')" clearable style="margin-right: 4%; width: 100%" class="filter-item">
                <el-option v-for="item in categories" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
              </el-select>
            </el-form-item>
            <el-form-item :label="$t('articles.price')">
              <el-input v-model="temp.price1" />
            </el-form-item>
            <el-form-item :label="$t('suppliers.supplier')" width="180px" align="center">
              <el-select v-model="temp.supplier" :placeholder="$t('suppliers.supplier')" clearable style="margin-right: 4%; width: 100%" class="filter-item">
                <el-option v-for="item in suppliers" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
              </el-select>
            </el-form-item>
            <el-form-item :label="$t('articles.amount')">
              <el-input v-model="temp.amount" />
            </el-form-item>
            <el-form-item :label="$t('discounts.discount_percentage') + ' (%)'">
              <el-input v-model="temp.discount" />
            </el-form-item>
          </div>
          <div class="formRight">
            <el-form-item :label="$t('articles.brand')">
              <el-input v-model="temp.brand" />
            </el-form-item>
            <el-form-item :label="$t('table.tags')">
              <el-input v-model="temp.tags" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" />
            </el-form-item>
            <el-form-item :label="$t('table.description')">
              <el-input v-model="temp.description" :autosize="{ minRows: 4, maxRows: 8}" type="textarea" />
            </el-form-item>
            <el-form-item :label="$t('table.short_description')">
              <el-input v-model="temp.short_description" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" />
            </el-form-item>
          </div>
        </div>
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

    <el-dialog :title="$t('route.articleDetails')" :visible.sync="modalArticlePreview">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="70px" style="width: 400px; margin-left:50px;">
        <el-form-item :label="$t('table.code')" prop="code">
          <el-input v-model="temp.code" />
        </el-form-item>
        <el-form-item :label="$t('table.title')" prop="title">
          <el-input v-model="temp.title" />
        </el-form-item>
        <el-table-column :label="$t('stores.location')" width="180px" align="center">
          <template slot-scope="scope">
            <span v-for="(n, index) in scope.row.store" :key="index">{{ scope.row.store[index].address }}<br></span>
          </template>
        </el-table-column>
        <el-form-item :label="$t('table.remark')">
          <el-input v-model="temp.remark" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Please input" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="modalArticlePreview = false">
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
import { fetchList, fetchPv, fetchArticle, createArticle, updateArticle, deleteArticle } from '@/api/article';
import { fetchStores } from '@/api/stores';
import { getCategories } from '@/api/category';
import { getSuppliers } from '@/api/supplier';
import waves from '@/directive/waves'; // Waves directive
import { parseTime } from '@/utils';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

/* const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JA', display_name: 'Japan' },
  { key: 'VI', display_name: 'Vietnam' },
];*/

// arr to obj ,such as { CN : "China", US : "USA" }
/* const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name;
  return acc;
}, {});*/

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
      // return calendarTypeKeyValue[type];
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      // test: [],
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        title: undefined,
        type: undefined,
        sort: '+id',
        keyword: '',
      },
      importanceOptions: [1, 2, 3],
      checkRole,
      sortOptions: [{ label: 'ascending', key: '+id' }, { label: 'descending', key: '-id' }],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        remark: '',
        type: '',
        code: '',
        category: '',
        supplier: '',
        price1: this.price / 100,
        discount: this.discount / 10,
      },
      price: 0,
      discount: 0,
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit_Article',
        create: 'Create_Article',
      },
      categories: this.getCategories(),
      suppliers: this.getSuppliers(),
      dialogPvVisible: false,
      modalArticlePreview: false,
      pvData: [],
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        title: [{ required: true, message: 'title is required', trigger: 'blur' }],
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
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
    },
    async getStores() {
      this.listLoading = true;
      const { data } = await fetchStores();
      this.stores = data.stores;
      this.listLoading = false;
    },
    async getCategories() {
      this.listLoading = true;
      const { data } = await getCategories();
      this.categories = data.items;
      this.listLoading = false;
    },
    async getSuppliers() {
      this.listLoading = true;
      const { data } = await getSuppliers();
      this.suppliers = data.items;
      this.listLoading = false;
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
        code: 0,
        title: '',
        category: 0,
        price1: 0,
        supplier: 0,
        amount: 0,
        discount: 0,
        brand: '',
        tags: '',
        description: '',
        short_description: '',
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
          createArticle(this.temp).then(() => {
            this.list.unshift(this.temp);
            this.dialogFormVisible = true;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 4000,
            });
            this.getList();
            this.getCategories();
            this.dialogFormVisible = false;
          });
        }
      });
    },
    previewArticle(row) {
      fetchArticle(row.id);
      this.modalArticlePreview = true;
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row); // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp);
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
          tempData.timestamp = +new Date(tempData.timestamp);
          updateArticle(tempData).then(() => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, this.temp);
                break;
              }
            }
            this.dialogFormVisible = false;
            this.getList();
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
    handleDelete(id) {
      deleteArticle(id).then(() => {
        this.$notify({
          title: this.$t('table.success'),
          message: this.$t('table.deleted_successfully'),
          type: 'success',
          duration: 2000,
        });
        this.getList();
      });
    },
    handleFetchPv(pv) {
      fetchPv(pv).then(response => {
        this.pvData = response.data.pvData;
        this.dialogPvVisible = true;
      });
    },
    pom1(categories_id) {
      return this.list.categories.name;
    },
    test() {
    },
    handleDownload() {
      this.downloadLoading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['ID', this.$t('table.code'), this.$t('articles.name'), this.$t('articles.category'), this.$t('articles.price') + ' (' + this.$t('articles.currency') + ')', this.$t('articles.discount') + ' (%)'];
        const filterVal = ['id', 'code', 'title', 'categories_id', 'price', 'discount'];
        const pom = this.list;
        for (var i = 0; i < pom.length; i++) {
          this.list[i].categories_id = pom[i].categories.name;
        }
        // console.log(this.list);
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: this.$t('route.articlesList'),
        });
        this.downloadLoading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j]);
        }
        if (j === 'price') {
          return this.currencyFormatEU(v[j] / 100, 2);
        }
        if (j === 'discount') {
          return this.currencyFormatEU(v[j] / 10, 1);
        }
        if (j === 'discount_silver' || j === 'discount_gold' || j === 'discount_premium') {
          return this.currencyFormatEU(((1 - this.currencyFormatEU(v[j] / 10, 1) / 100) * v['price']) / 100, 2);
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
    discountPrice(discount, price){
      return this.currencyFormatEU(((1 - this.currencyFormatEU(discount / 10, 1) / 100) * price) / 100, 2);
    },
    /* async pom() {
      const test = await pom();
      console.log(test);
    },*/
  },
};
</script>
<style lang="scss" scoped>
  .pagination-container {
    width: 100%;
    border-radius: 15px;
  }
  .dialog-footer {
    .el-button {
      width: 100px;
      margin: 20px 0;
    }
    padding-right: 50px;
  }
  .formLeft {
    label{
      float: right;
      width: 170px;
      display:block;
    }
    display: inline-block;
    width: 350px;
    margin-left: 50px;
  }
  .formRight {
    display: inline-block;
    width: 350px;
    justify-content: top;
    float:right;
    margin-right: 50px;
  }
  .mainForm {
    margin-top: -65px !important;
    padding-top: 35px;
  }
  .hasTooltip span {
    display: none;
    color: #000;
    text-decoration: none;
    padding: 3px;
  }
  .hasTooltip:hover span {
    display: block;
    z-index: 1;
    position: absolute;
    top: 0;
    right: 75%;
    color: #fff;
    background-color: #283046;
    border-radius: .428rem;
    box-shadow: 5px 10px 10px #001133, -5px -5px 10px #001133 !important;
    margin: 2px 10px;
    width: 200px;
    //visibility: visible;
    overflow: visible;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
