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
        <span />
      </el-table-column>

      <el-table-column
        class="col"
        align="center"
        :label="$t('customers.total_points')"
      >
        <template slot-scope="scope">
          <span>{{ $t('customers.from') }} {{ scope.row.from_point }}
            {{ $t('customers.to') }} {{ scope.row.to_point }}
            {{ $t('customers.total_points1') }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        class="col"
        align="center"
        :label="$t('discounts.customers_level')"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.level | uppercaseFirst }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        class="col"
        align="center"
        :label="$t('discounts.discount_percentage')"
      >
        <template slot-scope="scope">
          <span> {{ scope.row.discount_percent }} % </span>
        </template>
      </el-table-column>

      <el-table-column
        class="col"
        align="center"
        :label="$t('discounts.discount_start_date')"
      >
        <template slot-scope="scope">
          <span v-if="scope.row.discount_start_date !== null">
            {{ scope.row.discount_start_date | parseTime('{d}.{m}.{y}.') }}
          </span>
          <span v-else>{{ '-' }}</span>
        </template>
      </el-table-column>

      <el-table-column
        class="col"
        align="center"
        :label="$t('discounts.discount_end_date')"
      >
        <template slot-scope="scope">
          <span v-if="scope.row.discount_end_date !== null">
            {{ scope.row.discount_end_date | parseTime('{d}.{m}.{y}.') }}
          </span>
          <span v-else>{{ '-' }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.actions')">
        <template slot-scope="{ row }">
          <el-button
            v-if="checkRole(['admin', 'manager', 'editor'])"
            type="success"
            size="mini"
            @click="handleUpdate(row)"
          >
            {{ $t('table.edit') }}
          </el-button>
          <!-- <el-button
            v-if="checkRole(['admin', 'manager'])"
            type="danger"
            size="mini"
            @click="handleDelete(scope.row.id)"
          >
            {{ $t('table.delete') }}
          </el-button> -->
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 35px; display: flex;">
      <el-form ref="dataForm1" :model="values" label-width="300px">
        <el-form-item
          :label="
            $t('discounts.def_point_value')"
          style=" width: 500px; text-align : center; white-space: pre-line;"
        >
          <div style="display: flex;">
            <el-input v-model="values.value" :placeholder="currencyFormatEU(values.value_point / 100, 2)" />
            <span style=" margin-left: 5px;">{{ $t('articles.currency') }}</span>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" style="float: right;" @click="value_of_point">
            {{ $t('permission.confirm') }}
          </el-button>
        </el-form-item>
      </el-form>
      <el-form ref="dataForm2" :model="points" label-width="300px">
        <el-form-item
          :label="
            $t('discounts.def_value_of_point')"
          style=" width: 500px; text-align : center; white-space: pre-line;"
        >
          <div style="display: flex;">
            <el-input v-model="points.point" :placeholder="currencyFormatEU(values.point_value / 100, 2)" />
            <span style=" margin-left: 5px;">{{ $t('articles.currency') }}</span>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" style="float: right;" @click="points_value">
            {{ $t('permission.confirm') }}
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <el-dialog
      :title="
        (textMap[dialogStatus] = 'edit'
          ? $t('discounts.edit_discount')
          : $t('discounts.create_discount'))
      "
      style="width: 60%; margin: 0 auto"
      :visible.sync="dialogFormVisible"
    >
      <h3 style="width:100%; text-align: center; white-space: pre-line;margin-top: -30px;">
        {{ $t('discounts.customers_level') + ' : ' }}
        {{ type | uppercaseFirst }}
      </h3>
      <el-form ref="dataForm" :model="temp" label-width="180px">
        <div class="x">
          <div class="total_points">
            <el-form-item
              :label="
                $t('customers.total_points') + ' : ' + $t('customers.from')
              "
              style=" width: 400px; text-align : center; white-space: pre-line;"
            >
              <el-input v-model="temp.from_point" />
            </el-form-item>
            <el-form-item
              :label="$t('customers.to')"
              style="width: 400px; text-align : center"
            >
              <el-input v-model="temp.to_point" />
            </el-form-item>
          </div>
        </div>
        <div class="x">
          <el-form-item
            :label="$t('discounts.discount_percentage')"
            class="discount_percentage"
            style="word-break: break-word; width: 400px; text-align : center"
          >
            <el-input v-model="temp.discount_percent" />
          </el-form-item>
          <span class="x">
            <el-form-item :label="$t('table.date') + ' od : '" class="timepick">
              <el-date-picker
                v-model="temp.timestamp1"
                type="datetime"
                :placeholder="$t('discounts.pick_date')"
              />
            </el-form-item>
          </span>
          <span class="x">
            <el-form-item :label="$t('table.date') + ' do : '" class="timepick">
              <el-date-picker
                v-model="temp.timestamp2"
                type="datetime"
                :placeholder="$t('discounts.pick_date')"
              />
            </el-form-item>
          </span>
          <span class="x">
            <el-form-item
              :label="$t('discounts.no_time_limit')"
              class="discount_percentage"
              style="word-break:;"
            >
              <el-switch
                v-model="temp.no_switch"
                :active-value="1"
                :inactive-value="0"
                style="padding-right: 60px;"
              />
            </el-form-item>
          </span>
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
        <el-button
          type="primary"
          @click="dialogStatus === 'create' ? createData() : updateData()"
        >
          {{ $t('table.confirm') }}
        </el-button>
      </div>
    </el-dialog>
    <el-dialog
      :title="
        (textMap[dialogStatus] = 'edit'
          ? $t('discounts.edit_discount')
          : $t('discounts.create_discount'))
      "
      style="width: 60%; margin: 0 auto"
      :visible.sync="points_definitions"
    >
      <h3 style="width:100%; text-align: center; white-space: pre-line;margin-top: -30px;">
        {{ $t('discounts.customers_level') + ' : ' }}
        {{ type | uppercaseFirst }}
      </h3>
      <el-form ref="dataForm" :model="temp" label-width="180px">
        <div class="x">
          <div class="total_points">
            <el-form-item
              :label="
                $t('customers.total_points') + ' : ' + $t('customers.from')
              "
              style=" width: 400px; text-align : center; white-space: pre-line;"
            >
              <el-input v-model="temp.from_point" />
            </el-form-item>
            <el-form-item
              :label="$t('customers.to')"
              style="width: 400px; text-align : center"
            >
              <el-input v-model="temp.to_point" />
            </el-form-item>
          </div>
        </div>
        <div class="x">
          <el-form-item
            :label="$t('discounts.discount_percentage')"
            class="discount_percentage"
            style="word-break: break-word; width: 400px; text-align : center"
          >
            <el-input v-model="temp.discount_percent" />
          </el-form-item>
          <span class="x">
            <el-form-item :label="$t('table.date') + ' od : '" class="timepick">
              <el-date-picker
                v-model="temp.timestamp1"
                type="datetime"
                :placeholder="$t('discounts.pick_date')"
              />
            </el-form-item>
          </span>
          <span class="x">
            <el-form-item :label="$t('table.date') + ' do : '" class="timepick">
              <el-date-picker
                v-model="temp.timestamp2"
                type="datetime"
                :placeholder="$t('discounts.pick_date')"
              />
            </el-form-item>
          </span>
          <span class="x">
            <el-form-item
              :label="$t('discounts.no_time_limit')"
              class="discount_percentage"
              style="word-break:;"
            >
              <el-switch
                v-model="temp.no_switch"
                :active-value="1"
                :inactive-value="0"
                style="padding-right: 60px;"
              />
            </el-form-item>
          </span>
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          {{ $t('table.cancel') }}
        </el-button>
        <el-button
          type="primary"
          @click="dialogStatus === 'create' ? createData() : updateData()"
        >
          {{ $t('table.confirm') }}
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, updateLevel, getPoints, updateValue, updatePoint } from '@/api/discounts';
import { parseTime } from '@/utils';
import checkRole from '@/utils/role';
import waves from '@/directive/waves';

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
      points: {},
      values: {},
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
        timestamp: new Date(),
        timestamp1: new Date(),
        timestamp2: new Date(),
        type: '',
        discount_percent: '',
        discount_start_date: '',
        discount_end_date: '',
        created_at: '',
        updated_at: '',
      },
      loading: false,
      checkRole,
      parseTime,
      dialogFormVisible: false,
      points_definitions: false,
      dialogStatus: '',
      // value: [1, 3],
      // activeTab: '',
      textMap: {
        update: 'Edit',
        create: 'Create',
      },
      rules: {
        type: [
          {
            required: true,
            message: this.$t('discounts.type_is_required'),
            trigger: 'change',
          },
        ],
        timestamp: [
          {
            type: 'date',
            required: true,
            message: this.$t('discounts.timestamp_is_required'),
            trigger: 'change',
          },
        ],
        title: [
          {
            required: true,
            message: this.$t('discounts.title_is_required'),
            trigger: 'blur',
          },
        ],
      },
    };
  },
  created() {
    this.getList();
    this.getPoints();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.loading = false;
    },
    async getPoints() {
      const { data } = await getPoints();
      this.values = data;
      this.points = data;
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
      this.$refs['dataForm'].validate(valid => {
        if (valid) {
          const tempData = Object.assign({}, this.temp);
          tempData.timestamp1 = new Date(
            tempData.timestamp1
          ).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          tempData.timestamp2 = new Date(
            tempData.timestamp2
          ).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          updateLevel(tempData).then(response => {
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
    value_of_point() {
      this.$refs['dataForm1'].validate(valid => {
        if (valid) {
          const values = Object.assign({}, this.values);
          updateValue(values).then(response => {
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
    points_value() {
      this.$refs['dataForm2'].validate(valid => {
        if (valid) {
          const points = Object.assign({}, this.points);
          updatePoint(points).then(response => {
            // this.dialogFormVisible = false;
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
.x {
  display: flex !important;
  flex-wrap: wrap;
  flex-direction: column;
  align-content: stretch;
  justify-content: space-around;
  padding: 10px;
}

span.x {
  margin: 0 auto !important;
  word-break: break-word;
}

.timepick {
  border-radius: 0.45rem;
  margin-bottom: -10px !important;
  padding: 10px !important;
  background-color: #ffffff09;
}
.discount_percentage {
  background-color: #ffffff09;
  border-radius: 0.45rem;
  margin: 0 auto 10px auto;
}
.total_points {
  background-color: #ffffff09;
  margin: 0 auto 30px auto;
  border-radius: 0.45rem;
  padding: 10px !important;
}
.dialog-footer {
  display: flex;
  justify-content: center;
}
</style>
