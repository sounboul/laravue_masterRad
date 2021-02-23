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
            <el-button type="primary" @click="onSubmit(form.category)">
              {{ $t('permission.confirm') }}
            </el-button>
          </el-form-item>
        </div>
      </el-form>
    </div>
    <div v-if="!active1">
      <el-button style="background-color: #f58938; color: #fff; border-radius: .428rem" @click="active1 = true; mail=[]; phone = [];">Nazad</el-button>
      <el-table
        v-loading="false"
        :data="list"
        style="width: 65%;padding: 15px; margin: 35px auto; text-align: center;"
      >
        <!-- <el-table-column width="70px">
          <template>
            <el-button v-model="mail" />
          </template>
        </el-table-column> -->
        <el-table-column :label="$t('customers.customer_name')" class-name="col">
          <template slot-scope="scope">
            <span v-if="scope.row.name != null && scope.row.name != '' ">{{ scope.row.name }}</span>
            <span v-else>{{ scope.row.first_name }} {{ scope.row.last_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('login.email')" class-name="col" align="center">
          <template slot-scope="scope">
            <span style="cursor: pointer;" @click="chooseEmail(scope.row)">{{ scope.row.email }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('user.phone')" class-name="col" align="center">
          <template slot-scope="scope">
            <span style="cursor: pointer;" @click="choosePhone(scope.row)">{{ scope.row.phone }}</span>
            <!-- <el-tag :type="scope.row && scope.row.status ">
              {{ scope.row && scope.row.status }}
            </el-tag> -->
          </template>
        </el-table-column>
      </el-table>
      <el-button style="background-color: #f58938; color: #fff; border-radius: .428rem" @click="send_mail">Pošalji mejl</el-button>
    </div>
  </div>
</template>

<script>

import { fetchList, fetch_customers_category } from '@/api/category';
import { fetchAllCustomers, send_mail } from '@/api/customer';
import { fetchArticles } from '@/api/article';

export default {
  data() {
    return {
      list: null,
      listQuery: {

      },
      form: {
        id: 0,
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
      articles: '',
      categories: this.getCategories(),
      mail: [],
      phone: [],
    };
  },
  /* created() {
    this.getList();
  },*/
  methods: {
    async getCategories() {
      this.listLoading = true;
      const { data } = await fetchList(this.form);
      this.categories = data.categories;
      // console.log(this.categories);
      this.listLoading = false;
    },
    async getArticles() {
      this.listLoading = true;
      const { data } = await fetchArticles();
      this.articles = data.products;
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
    async send_mail() {
      const res = await send_mail(this.mail);
      this.$notify({
        title: this.$t('table.success'),
        message: this.$t('table.created_successfully'),
        type: '',
        duration: 3000,
      });
      //console.log(res);
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
    clickParent(subregion){
      var countries = this.list.filter(country => country.subregion === subregion[0].subregion);
      if (countries.find(country => !country.checked)){
        countries.forEach(country => country.checked === true);
      } else {
        countries.forEach(country => country.checked === false);
      }
    },
    async onSubmit(category_id) {
      console.log(category_id);
      this.active1 = !this.active1;
      // this.executeQuery();
      const { data } = await fetch_customers_category(category_id);
      this.list = Object.values(data);
      this.listLoading = false;
    },
    select() {
      this.mail.check = !this.mail.check;
    },
    chooseEmail(row) {
      // console.log(row.id);
      var self = this;
      self.mail.push(row.email);
      console.log(self.mail);
    },
    choosePhone(row) {
      // console.log(row.id);
      var self = this;
      self.phone.push(row.phone);
      console.log(self.phone);
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
