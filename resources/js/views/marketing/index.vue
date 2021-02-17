<template>
  <div class="app-container">
    <div v-if="active1" class="category">
      <el-form ref="dataForm" :model="form">
        <!-- <el-form-item label="Activity name">
          <el-input v-model="form.name" />
        </el-form-item> -->
        <div class="title">{{ $t('marketing.choose_category') }}</div>
        <el-form-item class="first_category">
          <el-select v-model="form.category" :placeholder="$t('articles.categories')" clearable style="margin-right: 4%; width: 100%" class="filter-item">
            <el-option v-for="item in categories" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
          </el-select>
        </el-form-item>
        <!-- </el-form> -->
        <!-- </div>
        <div> -->
        <!-- <el-form> -->
        <div class="title1">{{ $t('marketing.criteria') }}</div><br>
        <!-- <div class="title">{{ $t('marketing.newest') }} {{ $t('marketing.no_selled') }}</div> -->
        <el-form-item>
          <div style="display: inline-flex; width: 550px;">
            <div class="title">{{ $t('marketing.newest') }}</div>
            <el-radio-group v-model="form.resource">
              <el-radio label="Sponsor" />
              <el-radio label="Venue" />
            </el-radio-group>
            <div class="title">
              {{ $t('marketing.no_selled') }}
            </div>
          </div>
        </el-form-item>
        <div class="search_zone">
          <div class="title">{{ $t('marketing.criteria_date') }}</div>
          <el-form-item>
            <el-col :span="22">
              <el-date-picker v-model="form.date1" type="date" placeholder="Pick a date" style="width: 100%;" />
            </el-col>
          </el-form-item>
          <el-form-item>
            <!-- <el-button @click="onCancel">
              {{ $t('permission.cancel') }}
            </el-button> -->
            <el-button type="primary" @click="onSubmit">
              {{ $t('permission.confirm') }}
            </el-button>
          </el-form-item>
        </div>
      </el-form>
    </div>
    <div v-if="!active1">
      <!-- <span style="color: #eee;">{{ message }}</span> -->
      <el-table
        v-loading="false"
        :data="list"
        style="width: 85%;padding: 15px; margin: 35px auto; text-align: center;"
      >
        <el-table-column class-name="col-6">
          <template>
            <el-checkbox v-model="mail" />
          </template>
        </el-table-column>
        <el-table-column label="Ime kupca" class-name="col">
          <template slot-scope="scope">
            {{ scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column label="E-mail" class-name="col" align="center">
          <template slot-scope="scope">
            {{ scope.row.email }}
          </template>
        </el-table-column>
        <el-table-column label="Telefon" class-name="col" align="center">
          <template slot-scope="scope">
            {{ scope.row.mobile }}
            <!-- <el-tag :type="scope.row && scope.row.status ">
              {{ scope.row && scope.row.status }}
            </el-tag> -->
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>

import { getCategories, executeQuery } from '@/api/category';
import { fetchAllCustomers } from '@/api/customer';

export default {
  data() {
    return {
      list: null,
      listQuery: {

      },
      form: {
        name: '',
        date1: '',
        delivery: false,
        type: [],
        resource: '',
        category: '',
        desc: '',
      },
      active1: true,
      message: '',
      categories: this.getCategories(),
      mail: undefined,
    };
  },
  /* created() {
    this.getList();
  },*/
  methods: {
    async getCategories() {
      this.listLoading = true;
      const { data } = await getCategories(this.form);
      this.categories = data.items;
      this.listLoading = false;
    },
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
    resetTemp() {
      this.form = {
        id: undefined,
        name: '',
        date1: '',
        delivery: false,
        type: [],
        resource: '',
        category: '',
        desc: '',
      };
    },
    executeQuery() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.form.date1 = new Date(this.form.date1).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          executeQuery(this.form).then((response) => {
            if (response.customers.constructor !== Array) {
              this.$notify({
                title: this.$t('discounts.warning'),
                message: this.$t(response.customers),
                type: 'warning',
                duration: 5000,
              });
            } else {
              this.list = response.customers;
              console.log(this.list);
            }
            /* this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 3000,
            });*/
          });
        }
      });
    },
    /* async fetchData() {
      const { data } = await fetchList();
      this.list = data.items.slice(0, 8);
      this.loading = false;
    },*/
    onSubmit() {
      this.active1 = !this.active1;
      this.executeQuery();
      this.listLoading = false;
    },
    select() {
      this.mail.check = !this.mail.check;
    },
  },
};
</script>

<style lang="scss" scoped>
.line{
  text-align: center;
}
.category {
  text-align: center;
  margin: 10px 10px;
  .title {
    margin: 45px auto 10px auto;
    color: #6699ff;
  }
  .title1 {
    margin: 80px auto 10px auto;
    color: #6699ff;
  }
  .first_category {
    width: 230px;
    margin: auto;
  }
  .search_zone {
    margin: 0;
    display: inline-block;
    width: 500 px;
    text-align: center;
  }
  .radio1, .radio2{
    margin: 20px;
    display: inline-block;
    text-align: center;
  }
  .el-radio-group {
    text-align: center;
    width:400px;
    padding: 0;
    height: 50px;
  }
}
</style>
