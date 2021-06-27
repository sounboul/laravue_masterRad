<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button type="danger" style="float:left;" @click="customersTico">Refresh</el-button>
      <div style="float: right;">
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
      <el-table-column label="ID" align="center" width="120">
        <template slot-scope="{row}">
          <el-button size="mini" style="background-color: #f58938; color: #fff; border-radius: .428rem">{{ row.customer_id }}</el-button>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.customer_name')" class="col" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.name != null && scope.row.name != '' ">{{ scope.row.name }}</span>
          <span v-else>{{ scope.row.first_name }} {{ scope.row.last_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.total_points')" class="col" align="center">
        <template slot-scope="{row}">
          <span v-show="row.total_points != null">{{ row.total_points }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('discounts.customers_level')" class="col" align="center">
        <template slot-scope="{row}">
          <el-tag :type="row.level | statusFilter" style="width:80px;">
            {{ $t('articles.' + row.level) }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('login.email')" class="col" align="center">
        <template slot-scope="{row}">
          <span v-show="row.email != null">{{ row.email }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('user.phone')" class="col" align="center">
        <template slot-scope="{row}">
          <span v-show="row.phone != null">{{ row.phone }}</span>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="fetchListTico" />
  </div>
</template>

<script>
import { fetchPv, createCustomer, updateCustomer, deleteCustomer, fetchAllCustomers, customersTico, fetchListTico } from '@/api/customer';
import waves from '@/directive/waves'; // Waves directive
import { parseTime } from '@/utils';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

export default {
  name: 'CustomerList',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(active) {
      const statusMap = {
        regular: 'success',
        silver: 'info',
        gold: 'warning',
        premium: 'danger',
      };
      return statusMap[active];
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      list1: null,
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
        current_page: 1,
        showActiveCustomers: false,
        showPendingCustomers: true,
        showDeletedCustomers: true,
      },
      importanceOptions: [1, 2, 3],
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
      downloadLoading: false,
    };
  },
  created() {
    this.fetchListTico();
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
      // const { limit, page } = this.listQuery;
      await customersTico(this.listQuery);
      this.fetchListTico();
      /* this.list1 = data.data;
      this.total = data.total;
      this.list1.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });*/
      this.listLoading = false;
    },
    async fetchListTico() {
      this.listLoading = true;
      const { limit, page } = this.listQuery;
      const { data } = await fetchListTico(this.listQuery);
      this.list = data.data;
      this.total = data.total;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
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
  .testClass {
    width: 100px;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
