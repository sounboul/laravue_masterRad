<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.native="handleFilter" />
      <!-- <el-select v-model="listQuery.importance" :placeholder="$t('table.importance')" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select> -->
      <!-- <el-select v-model="listQuery.type" :placeholder="$t('table.type')" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name+'('+item.key+')'" :value="item.key" />
      </el-select> -->
      <el-select v-model="listQuery.sort" style="width: 150px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="$t('table.'+item.label)" :value="item.key" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-checkbox class="filter-item" style="margin-left:15px;" @change="showActiveCustomers === false ? activeCustomers() : getAllCustomers()">{{ $t('customers.active_customers') }}
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
      <el-table-column label="" prop="id" align="center" width="40">
        <template>
          <span>{{ }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.customer_name')" min-width="120px">
        <template slot-scope="{row}">
          <span class="" style="font-weight: bold;cursor: pointer;" @click="previewCustomer(row)">{{ row.name }}</span>
          <!-- <el-tag>{{ row.title }}</el-tag> -->
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.total_points')" width="100px" align="center">
        <template slot-scope="{row}">
          <span>{{ row.total_points }}</span>
          <!-- <el-tag>{{ row.title }}</el-tag> -->
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.member_since')" width="130px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.member_since | parseTime('{d}.{m}.{y}.') }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('customers.last_change')" width="160px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.updated_at | parseTime('{d}.{m}.{y}.') }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column :label="$t('table.author')" width="120px" align="center">
        <template slot-scope="scope">
          <span v-for="(n, index) in scope.row.store" :key="index">{{ scope.row.store[index].address }}<br></span>
        </template>
      </el-table-column> -->
      <!-- <el-table-column v-if="showReviewer" :label="$t('table.reviewer')" width="110px" align="center">
        <template slot-scope="scope">
          <span style="color:red;">{{ scope.row.reviewer }}</span>
        </template>
      </el-table-column> -->
      <!-- <el-table-column :label="$t('table.importance')" width="80px">
        <template slot-scope="scope">
          <svg-icon v-for="n in +scope.row.rating" :key="n" icon-class="star" class="meta-item__icon" />
        </template>
      </el-table-column> -->
      <el-table-column :label="$t('table.readings')" align="center" width="95">
        <template slot-scope="{row}">
          <span v-if="row.pageviews" class="link-type" @click="handleFetchPv(row.pageviews)">{{ row.pageviews }}</span>
          <span v-else>0</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.status')" class-name="status-col" width="100">
        <template slot-scope="{row}">
          <el-tag :type="row.active | statusFilter">
            {{ row.active=='active' ? $t('customers.active') : $t('customers.deleted') }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="250" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button v-if="checkRole(['admin','manager','editor']) && row.active!='deleted'" type="success" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="checkRole(['admin','manager']) && row.active!='deleted'" type="danger" size="mini" @click="handleDelete(row.id)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus] == 'Create' ? $t('customers.create_new_customer') : $t('customers.edit_customer')" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="200px" style="width: 500px; margin-left:50px; word-break: break-word;">
        <el-form-item :label="$t('customers.customer_name')" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item :label="$t('login.email')" type="email" prop="email">
          <el-input v-model="temp.email" />
        </el-form-item>
        <el-form-item :label="$t('customers.mobile')" prop="mobile">
          <el-input v-model="temp.mobile" />
        </el-form-item>
        <el-form-item :label="$t('customers.dob')" prop="dob">
          <el-date-picker v-model="temp.dob" type="date" :placeholder="$t('customers.pick_a_date')" />
        </el-form-item>
        <el-form-item :label="$t('customers.ID_number')" prop="ID_number">
          <el-input v-model="temp.ID_number" />
        </el-form-item>
        <el-form-item :label="$t('customers.street')" prop="street">
          <el-input v-model="temp.street" />
        </el-form-item>
        <el-form-item :label="$t('customers.number')" prop="number">
          <el-input v-model="temp.number" />
        </el-form-item>
        <el-form-item :label="$t('customers.city')" prop="city">
          <el-input v-model="temp.city" />
        </el-form-item>
        <el-form-item :label="$t('customers.postal_code')" prop="postal_code">
          <el-input v-model="temp.postal_code" />
        </el-form-item>
        <el-form-item :label="$t('customers.country')" prop="country">
          <el-input v-model="temp.country" />
        </el-form-item>
        <!-- <el-form-item :label="$t('table.status')">
          <el-select v-model="temp.status" class="filter-item" placeholder="Please select">
            <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('table.importance')">
          <el-rate v-model="temp.importance" :colors="['#99A9BF', '#F7BA2A', '#FF9900']" :max="3" style="margin-top:8px;" />
        </el-form-item> -->
        <!-- <el-form-item :label="$t('table.remark')">
          <el-input v-model="temp.remark" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" :placeholder="$t('customers.please_input')" />
        </el-form-item> -->
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

    <el-dialog :title="$t('customers.customerDetails')" :visible.sync="modalCustomerPreview">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="200px" style="width: 400px; margin-left:50px; word-break: break-word;">
        <el-form-item :label="$t('customers.customer_name')" prop="name">
          <el-input v-model="temp.name" />
        </el-form-item>
        <el-form-item :label="$t('login.email')" type="email" prop="email">
          <el-input v-model="temp.email" />
        </el-form-item>
        <el-form-item :label="$t('customers.mobile')" prop="mobile">
          <el-input v-model="temp.mobile" />
        </el-form-item>
        <el-form-item :label="$t('customers.dob')" prop="dob">
          <el-date-picker v-model="temp.dob" type="date" :placeholder="$t('customers.pick_a_date')" />
        </el-form-item>
        <el-form-item :label="$t('customers.ID_number')" prop="ID_number">
          <el-input v-model="temp.ID_number" />
        </el-form-item>
        <el-form-item :label="$t('customers.street')" prop="street">
          <el-input v-model="temp.street" />
        </el-form-item>
        <el-form-item :label="$t('customers.number')" prop="number">
          <el-input v-model="temp.number" />
        </el-form-item>
        <el-form-item :label="$t('customers.city')" prop="city">
          <el-input v-model="temp.city" />
        </el-form-item>
        <el-form-item :label="$t('customers.postal_code')" prop="postal_code">
          <el-input v-model="temp.postal_code" />
        </el-form-item>
        <el-form-item :label="$t('customers.country')" prop="country">
          <el-input v-model="temp.country" />
        </el-form-item>
        <!-- <el-form-item :label="$t('table.status')">
          <el-select v-model="temp.status" class="filter-item" placeholder="Please select">
            <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('table.importance')">
          <el-rate v-model="temp.importance" :colors="['#99A9BF', '#F7BA2A', '#FF9900']" :max="3" style="margin-top:8px;" />
        </el-form-item> -->
        <!-- <el-form-item :label="$t('table.remark')">
          <el-input v-model="temp.remark" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" :placeholder="$t('customers.please_input')" />
        </el-form-item> -->
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="modalCustomerPreview = false">
          {{ $t('tagsView.close') }}
        </el-button>
        <!-- <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          {{ $t('table.confirm') }}
        </el-button> -->
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogPvVisible" title="Reading statistics">
      <el-table :data="pvData" fit highlight-current-row style="width: 100%">
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
import { fetchList, fetchPv, fetchActiveCustomers, fetchCustomer, createCustomer, updateCustomer, deleteCustomer, fetchAllCustomers } from '@/api/customer';
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
        active: 'success',
        draft: 'info',
        deleted: 'danger',
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
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        importance: undefined,
        title: undefined,
        name: undefined,
        type: undefined,
        member_since: '',
        updated_at: '',
        sort: '+id',
        keyword: '',
        showActiveCustomers: !this.showActiveCustomers,
      },
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      checkRole,
      sortOptions: [{ label: 'ascending', key: '+id' }, { label: 'descending', key: '-id' }],
      statusOptions: ['active', 'draft', 'deleted'],
      showActiveCustomers: false,
      temp: {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        email: '',
        mobile: '',
        dob: '',
        ID_number: '',
        street: '',
        number: '',
        city: '',
        postal_code: '',
        country: '',
        type: '',
        code: '',
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
    this.activeCustomers();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.total = data.total;
      this.listLoading = false;
    },
    async activeCustomers() {
      this.listLoading = true;
      this.listQuery.showActiveCustomers = !this.showActiveCustomers;
      console.log(this.listQuery.showActiveCustomers);
      const { data } = await fetchActiveCustomers(this.listQuery);
      this.showActiveCustomers = !this.showActiveCustomers;
      this.list = data.items;
      this.total = data.total;
      this.listLoading = false;
    },
    async getAllCustomers() {
      this.listLoading = true;
      this.listQuery.showActiveCustomers = !this.showActiveCustomers;
      console.log(this.listQuery.showActiveCustomers);
      const { data } = await fetchAllCustomers(this.listQuery);
      this.showActiveCustomers = !this.showActiveCustomers;
      this.list = data.items;
      this.total = data.total;
      this.listLoading = false;
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
          this.temp.id = parseInt(Math.random() * 100) + 1024; // mock a id
          this.temp.author = 'laravue';
          createCustomer(this.temp).then(() => {
            this.list.unshift(this.temp);
            this.dialogFormVisible = true;
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 2000,
            });
          });
        }
      });
    },
    previewCustomer(row) {
      fetchCustomer(row.id);
      this.modalCustomerPreview = true;
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
          tempData.timestamp = +new Date(tempData.timestamp); // change Thu Nov 30 2017 16:41:05 GMT+0800 (CST) to 1512031311464
          updateCustomer(tempData).then(() => {
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
    handleDelete(id) {
      deleteCustomer(id).then(() => {
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
<style scoped></style>
