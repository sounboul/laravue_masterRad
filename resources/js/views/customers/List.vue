<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 250px;" class="filter-item" @keyup.native="handleFilter" />
      <!-- <el-select v-model="listQuery.importance" :placeholder="$t('table.importance')" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select> -->
      <!-- <el-select v-model="listQuery.type" :placeholder="$t('table.type')" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name+'('+item.key+')'" :value="item.key" />
      </el-select> -->
      <el-select v-model="listQuery.sort" style="width: 150px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="$t('table.'+item.label)" :value="item.key" />
      </el-select>
      <!-- <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button> -->
      <el-checkbox class="filter-item check1" style="margin-left: 15px;" @change="ActiveCustomers">{{ $t('customers.active_customers') }}
      </el-checkbox>
      <el-checkbox class="filter-item check2" style="margin-left: 15px;" @change="PendingCustomers">{{ $t('customers.pending_customers') }}
      </el-checkbox>
      <el-checkbox class="filter-item check3" style="margin-left: 15px;" @change="DeletedCustomers">{{ $t('customers.deleted_customers') }}
      </el-checkbox>
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
      style="width: 100%;border-radius: .428rem;"
      @sort-change="sortChange"
    >
      <el-table-column :label="$t('table.code')" prop="code" align="center" width="120">
        <template slot-scope="{row}">
          <router-link :to="'/selling/index/'+row.id">
            <el-button v-show="row.active=='active'" size="mini" style="background-color: #f58938; color: #fff; border-radius: .428rem">{{ row.code }}</el-button>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.customer_name')" min-width="120px">
        <template slot-scope="{row}">
          <span v-if="row.customer.name != null">{{ row.customer.name }}</span>
          <span v-else>{{ row.customer.first_name }} {{ row.customer.last_name }}</span>
          <!-- <el-tag>{{ row.title }}</el-tag> -->
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.total_points')" class="col" align="center">
        <template slot-scope="{row}">
          <span v-show="row.customer.email != null">{{ row.customer.email }}</span>
          <!-- <el-tag>{{ row.title }}</el-tag> -->
        </template>
      </el-table-column>
      <el-table-column :label="$t('discounts.customers_level')" class="col" align="center">
        <template slot-scope="{row}">
          <span v-show="row.customer.address != null">{{ row.customer.address }}</span>
          <!-- <el-tag>{{ row.title }}</el-tag> -->
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.member_since')" class="col" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.customer.city }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.last_change')" class="col" align="center">
        <template slot-scope="scope">
          <span v-show="scope.row.customer.phone != null">{{ scope.row.customer.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.readings')" align="center" class="col">
        <template slot-scope="{row}">
          <span v-if="row.pageviews" class="link-type" @click="handleFetchPv(row.pageviews)">{{ row.pageviews }}</span>
          <span v-else>0</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.status')" class-name="status-col" width="100">
        <template>
          <el-tag :type="'gold' | statusFilter">
            {{ $t('articles.gold') }}
          </el-tag>
        </template>
      </el-table-column>
      <!-- <el-table-column v-if="checkRole(['admin','manager', 'editor'])" :label="$t('table.actions')" align="center" width="250" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-if="checkRole(['admin','manager','editor']) && row.active!='deleted'" type="success" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="checkRole(['admin','manager']) && row.active!='deleted'" type="danger" size="mini" @click="handleDelete(row.id)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column> -->
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="customersTico" />
  </div>
</template>

<script>
import { fetchPv, createCustomer, updateCustomer, deleteCustomer, fetchAllCustomers, customersTico } from '@/api/customer';
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
  name: 'CustomerList',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(active) {
      const statusMap = {
        regular: 'info',
        silver: 'default',
        gold: 'warning',
        premium: 'danger',
      };
      return statusMap[active];
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type];
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        title: undefined,
        name: undefined,
        type: undefined,
        sort: '+id',
        keyword: '',
        showActiveCustomers: false,
        showPendingCustomers: true,
        showDeletedCustomers: true,
      },
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      checkRole,
      sortOptions: [{ label: 'ascending', key: '+id' }, { label: 'descending', key: '-id' }],
      statusOptions: ['active', 'pending', 'deleted'],
      showActiveCustomers: false,
      temp: {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        email: '',
        mobile: '',
        date: new Date(),
        ID_number: '',
        street: '',
        number: '',
        city: '',
        postal_code: '',
        country: '',
        type: '',
        code: '',
        facebook_account: '',
        instagram_account: '',
        twitter_account: '',
        status: 'published',
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit',
        create: 'Create',
      },
      dialogPvVisible: false,
      modalCustomerPreview: false,
      pvData: [],
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        name: [{ required: true, message: this.$t('customers.name_required'), trigger: 'blur' }],
        email: [{ required: true, message: this.$t('customers.email_required'), trigger: 'blur' }],
        mobile: [{ required: true, message: this.$t('customers.mobile_required'), trigger: 'blur' }],
      },
      downloadLoading: false,
    };
  },
  created() {
    // this.getList();
    this.customersTico();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { limit, page } = this.listQuery;
      const { data, meta } = await fetchAllCustomers(this.listQuery);
      this.list = data;
      this.level = data[0].level;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.listLoading = false;
    },
    async customersTico() {
      this.listLoading = true;
      const { limit, page } = this.listQuery;
      const { data } = await customersTico(this.listQuery);
      this.list = data.orders;
      // console.log(this.list);
      // this.level = data[0].level;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      // this.total = meta.total;
      this.listLoading = false;
    },
    async ActiveCustomers() {
      this.listQuery.showActiveCustomers = !this.listQuery.showActiveCustomers;
      this.getList();
    },
    async PendingCustomers() {
      this.listQuery.showActiveCustomers = !this.listQuery.showActiveCustomers;
      this.listQuery.showPendingCustomers = !this.listQuery.showPendingCustomers;
      this.getList();
    },
    async DeletedCustomers() {
      this.listQuery.showActiveCustomers = !this.listQuery.showActiveCustomers;
      this.listQuery.showDeletedCustomers = !this.listQuery.showDeletedCustomers;
      this.getList();
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
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
      if (prop === 'member_since') {
        this.sortByDate(order);
      }
      if (prop === 'updated_at') {
        this.sortByBuy(order);
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
    sortByDate(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id';
      } else {
        this.listQuery.sort = '-id';
      }
      this.handleFilter();
    },
    sortByBuy(order) {
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
          /* this.temp.id = parseInt(Math.random() * 100) + 1024; // mock a id
          this.temp.author = 'laravue';*/
          createCustomer(this.temp).then(() => {
            this.list.unshift(this.temp);
            this.dialogFormVisible = true;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 3000,
            });
            this.dialogFormVisible = false;
            this.getList();
          });
        }
      });
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row); // copy obj
      this.temp.timestamp = new Date(this.temp).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
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
          tempData.date = new Date(tempData.date).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          updateCustomer(tempData).then(() => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, this.temp);
                break;
              }
            }
            this.getList();
            this.dialogFormVisible = false;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.updated_successfully'),
              type: 'success',
              duration: 3000,
            });
          });
        }
      });
    },
    handleDelete(id) {
      this.$confirm(this.$t('table.sure'), this.$t('discounts.warning'), {
        confirmButtonText: this.$t('permission.confirm'),
        cancelButtonText: this.$t('permission.cancel'),
        type: 'warning',
      })
        .then(async() => {
          await deleteCustomer(id);
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
        const tHeader = ['timestamp', 'title', 'type', 'importance', 'status'];
        const filterVal = ['timestamp', 'title', 'type', 'importance', 'status'];
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
  },
};
</script>
<style lang="scss" scoped>
  .filter-container {
    // margin-bottom: 5px;
  }
  .dialog0 {
    display: inline-flex;
    text-align: center;
  }
  .dialog1 {
    width: 370px;
    margin: 0 90px 0 0;
  }
  .dialog2 {
    width: 370px;
    margin: 0 0 0 10px;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
