<template>
  <div class="app-container">
    <div v-if="active1" class="category">
      <el-form ref="dataForm" :model="form">
        <div class="title">{{ $t('marketing.choose_category') }}</div>
        <el-form-item class="first_category">
          <el-select v-model="form.category" :placeholder="$t('articles.categories')" clearable style="margin-right: 4%; width: 100%" class="filter-item">
            <el-option v-for="item in categories" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
          </el-select>
        </el-form-item>
        <div class="search_zone">
          <div class="title">{{ $t('marketing.criteria_date') }}</div>
          <el-form-item>
            <el-col :span="22">
              <el-date-picker v-model="form.date1" type="date" placeholder="Pick a date" style="width: 100%;" />
            </el-col>
          </el-form-item>
          <el-form-item>
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
        v-loading="listLoading"
        :data="list"
        style="width: 65%;padding: 15px; margin: 35px auto; text-align: center; border-radius: .428rem"
      >
        <el-table-column width="70px">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('customers.customer_name')" class-name="col">
          <template slot-scope="scope">
            <span v-if="scope.row.name != null && scope.row.name != '' ">{{ scope.row.name }}</span>
            <span v-else>{{ scope.row.first_name }} {{ scope.row.last_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('login.email')" class-name="col">
          <template slot-scope="scope">
            <span style="cursor: pointer;" @click="chooseEmail(scope.row);"> {{ scope.row.email }} </span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('user.phone')" class-name="col">
          <template slot-scope="scope">
            <span style="cursor: pointer;" @click="choosePhone(scope.row);">{{ scope.row.phone }}</span>
          </template>
        </el-table-column>
      </el-table>
      <el-button :disabled="mail.length == 0" style="background-color: #f58938; color: #fff; border-radius: .428rem; margin: auto 50px;" @click="send_mail">{{ $t('marketing.send_mail') }}</el-button>
      <el-button :disabled="phone.length == 0" style="float: right;background-color: #f58938; color: #fff; border-radius: .428rem; margin:auto 50px;" @click="send_sms">{{ $t('marketing.send_sms') }}</el-button>
    </div>
  </div>
</template>

<script>

import { fetchList, fetch_customers_category } from '@/api/category';
import { fetchAllCustomers, send_mail, send_sms } from '@/api/customer';
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
      clickedRow: true,
      clickedId: 0,
    };
  },
  methods: {
    async getCategories() {
      this.listLoading = true;
      const { data } = await fetchList(this.form);
      this.categories = data.categories;
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
      this.listLoading = true;
      const res = await send_mail(this.mail);
      this.listLoading = false;
      this.$notify({
        title: this.$t(res.title),
        message: this.$t(res.message),
        type: res.type,
        duration: 4500,
      });
    },
    async send_sms() {
      this.listLoading = true;
      const res = await send_sms(this.phone);
      this.listLoading = false;
      this.$notify({
        title: this.$t(res.title),
        message: this.$t(res.message),
        type: res.type,
        duration: 4500,
      });
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
      this.active1 = !this.active1;
      const { data } = await fetch_customers_category(category_id);
      this.list = Object.values(data);
      this.listLoading = false;
    },
    chooseEmail(row) {
      const self = this;
      const index = self.mail.indexOf(row.email);
      if (self.mail.includes(row.email)) {
        self.mail.splice(index, 1);
      } else {
        self.mail.push(row.email);
      }
    },
    choosePhone(row) {
      const self = this;
      const index = self.phone.indexOf(row.phone);
      if (self.phone.includes(row.phone)) {
        self.phone.splice(index, 1);
      } else {
        self.phone.push(row.phone);
      }
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
.color1{
  color: red !important;
}
.color2{
  color: orange !important;
}
</style>
