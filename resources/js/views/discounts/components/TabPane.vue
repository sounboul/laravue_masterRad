<template>
  <div>
    <el-table :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        v-loading="loading"
        align="center"
        label=""
        width="80"
        element-loading-text="Please be patient！"
      >
        <span>
          {{ }}
        </span>
      </el-table-column>

      <el-table-column class="col" align="center" :label="$t('customers.total_points')">
        <template slot-scope="scope">
          <span>{{ $t('customers.from') }} {{ scope.row.from_point }} {{ $t('customers.to') }} {{ scope.row.to_point }} {{ $t('customers.total_points1') }}
          </span>
        </template>
      </el-table-column>

      <el-table-column class="col" align="center" :label="$t('discounts.customers_level')">
        <template slot-scope="scope">
          <span>
            {{ scope.row.level | uppercaseFirst }}
          </span>
        </template>
      </el-table-column>

      <el-table-column class="col" align="center" :label="$t('discounts.discount_percentage')">
        <template slot-scope="scope">
          <span>
            {{ scope.row.discount_percent }} %
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.actions')">
        <template slot-scope="{row}">
          <el-button v-if="checkRole(['admin','manager', 'editor'])" type="success" size="mini" @click="handleUpdate(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="checkRole(['admin','manager'])" type="danger" size="mini" @click="handleDelete(scope.row.id);">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>

    </el-table>

    <el-dialog :title="textMap[dialogStatus] = 'edit' ? $t('discounts.edit_discount') : $t('discounts.create_discount')" :visible.sync="dialogFormVisible">
      <h3 style="width:100%; margin: -35px 0 20px 0;text-align: center;">{{ $t('discounts.customers_level') + ': ' }} {{ type | uppercaseFirst }}</h3>
      <el-form ref="dataForm" :model="temp" label-width="100px">
        <span class="x">
          <div class="total_points">
            <!-- <span>{{  }}</span> -->
            <el-form-item :label="$t('customers.total_points') + ': ' + $t('customers.from')">
              <!-- <span>{{  }}</span> -->
              <el-input v-model="temp.from_point" />
            </el-form-item>
            <el-form-item :label="$t('customers.to')">
              <!-- <span>{{ $t('customers.to') }}</span> -->
              <el-input v-model="temp.to_point" />
            </el-form-item>
          </div>
        </span>
        <!-- <el-form-item :label="$t('table.status')">
          <el-select v-model="temp.status" class="filter-item" placeholder="Please select">
            <el-option v-for="item in statusOptions" :key="item" :label="item" :value="item" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('table.importance')">
          <el-rate v-model="temp.importance" :colors="['#99A9BF', '#F7BA2A', '#FF9900']" :max="3" style="margin-top:8px;" />
        </el-form-item> -->
        <span class="x">
          <el-form-item :label="$t('discounts.discount_percentage')" class="discount_percentage" style="word-break: break-word;">
            <el-input v-model="temp.discount_percent" />
          </el-form-item>
          <span>
            <el-form-item :label="$t('table.date')" class="timepick">
              <el-date-picker v-model="temp.timestamp1" type="datetime" placeholder="Please pick a date" />
            </el-form-item>
          </span>
          <span>
            <el-form-item :label="$t('table.date')" class="timepick">
              <el-date-picker v-model="temp.timestamp2" type="datetime" placeholder="Please pick a date" />
            </el-form-item>
          </span>
        </span>
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
  </div>
</template>

<script>
import { fetchList, updateLevel } from '@/api/discounts';
import checkRole from '@/utils/role';
import waves from '@/directive/waves';
// import { parseTime } from '@/utils';

export default {
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
  },
  props: {
    type: {
      type: String,
      default: 'regular',
    },
  },
  data() {
    return {
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type,
        sort: '+id',
      },
      temp: {
        id: undefined,
        from_point: 0,
        to_point: 0,
        timestamp1: new Date(),
        timestamp2: new Date(),
        type: '',
        discount_percent: '',
        created_at: '',
        updated_at: '',
      },
      loading: false,
      checkRole,
      dialogFormVisible: false,
      dialogStatus: '',
      activeTab: '',
      textMap: {
        update: 'Edit',
        create: 'Create',
      },
      rules: {
        type: [{ required: true, message: 'type_is_required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp_is_required', trigger: 'change' }],
        title: [{ required: true, message: 'title_is_required', trigger: 'blur' }],
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.loading = false;
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
          tempData.timestamp1 = new Date(tempData.timestamp1).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          tempData.timestamp2 = new Date(tempData.timestamp2).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          updateLevel(tempData).then((response) => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, this.temp);
                break;
              }
            }
            this.dialogFormVisible = false;
            this.$notify({
              title: this.$t(response.data.title),
              message: this.$t(response.data.message),
              type: response.data.type,
              duration: 3000,
            });
          });
        }
      });
    },
  },
};
</script>

<style scoped>
  .timepick {
    /*position: relative;
    right: 20px;
    width: 100%;*/
    border: 2px solid #000;
    box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
    padding: 10px 20px;
    margin: 25px 15px;
    border-radius: .45rem;
    /* float: right !important; */
    /*width: 30%;*/
  }
  .discount_percentage {
    /*position: relative;*/
    border: 2px solid #000;
    box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
    padding: 10px 20px;
    margin: 25px 15px;
    /*width: 30%;*/
    border-radius: .45rem;
    /*float:right;*/
  }
  .total_points {
    margin: 0 auto;
    width: 60%;
    border: 2px solid #000;
    box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
    padding: 25px 30px 5px 30px;
    border-radius: .45rem;
  }
  .total_points1 {
    /*display: list-item;
    list-style: none;
    position: relative;*/
    border: 2px solid #000;
    box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
    /*width: 330px;*/
    padding: 10px 150px 10px 50px;
    /*margin: 0 35px 25px 35px;*/
    margin: auto;
    border-radius: .45rem;
    /*width: 33%;
    float: left;*/
  }
  .total_points input {
      width: 25px;
  }

  span.x {
    display: inline-block;
  }
</style>
