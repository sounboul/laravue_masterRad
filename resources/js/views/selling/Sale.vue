<template>
  <div class="test">
    <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 250px;" class="filter-item" @keyup.native="handleFilter" />
    <div class="customer" :data="list1">
      <span class="title">{{ $t('customers.customer') + ': ' }}</span><span>{{ list1.name }}</span>
      <span class="title">{{ $t('customers.total_points1') + ': ' }}</span><span>{{ list1.total_points }}</span>
      <span class="title">{{ $t('customers.level') + ': ' }}</span><span>{{ level | uppercaseFirst() }}</span>
      <span class="title">{{ $t('customers.last_change') + ': ' }}</span><span>{{ list1.updated_at | parseTime('{d}.{m}.{y}.') }}</span>
    </div>
    <div class="articles">
      <el-table
        :key="tableKey"
        v-loading="listLoading"
        :data="list"
        fit
        highlight-current-row
        style="width: 100%; word-break: break-word; font-size: 15pt;"
      >
        <el-table-column :label="$t('table.code')" align="center" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.name')" min-width="120px">
          <template slot-scope="{row}">
            <span style="margin-top: 2px; cursor: pointer;" @click="previewArticle(row)">{{ row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.price') + ' (' + $t('articles.currency') + ')'" width="150px" align="center">
          <template slot-scope="scope">
            <span>{{ currencyFormatEU(scope.row.price/100, 2) }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.discount') + ' (%) '" width="130px" align="center">
          <template slot-scope="scope">
            <span>{{ currencyFormatEU(scope.row.discount/10, 1) }}</span>
          </template>
        </el-table-column>
        <!-- <el-table-column :label="$t('articles.discount_silver') + ' (' + $t('articles.currency') + ')'" width="155px" align="center">
          <template slot-scope="scope">
            <span>{{ discountPrice(scope.row.discount_silver, scope.row.price) }}</span>
            <br>
            <span style="color: #4f637d;">{{ '(' + currencyFormatEU(scope.row.discount_silver/10, 1) + ' %) ' }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.discount_gold') + ' (' + $t('articles.currency') + ')'" width="155px" align="center">
          <template slot-scope="scope">
            <span>{{ discountPrice(scope.row.discount_gold, scope.row.price) }}</span>
            <br>
            <span style="color: #4f637d;">{{ '(' + currencyFormatEU(scope.row.discount_gold/10, 1) + ' %) ' }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.discount_premium') + ' (' + $t('articles.currency') + ')'" width="155px" align="center">
          <template slot-scope="scope">
            <span>{{ discountPrice(scope.row.discount_premium, scope.row.price) }}</span>
            <br>
            <span style="color: #4f637d;">{{ '(' + currencyFormatEU(scope.row.discount_premium/10, 1) + ' %) ' }}</span>
          </template>
        </el-table-column> -->
        <el-table-column :label="$t('articles.in_stock') + ' (' + $t('articles.pieces') + ')'" width="120px" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.amount }}</span>
          </template>
        </el-table-column>
        <!-- <el-table-column :label="$t('articles.category')" width="120px" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.categories.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('stores.location')" width="180px" align="center">
          <template slot-scope="scope">
            <span v-for="(n, index) in scope.row.store" :key="index">{{ scope.row.store[index].address }}<br></span>
          </template>
        </el-table-column> -->
      </el-table>
    </div>
    <div class="bill">
      BBB
    </div>
  </div>
</template>

<script>

import { fetchCustomer } from '@/api/customer';
import { fetchList1 } from '@/api/article';
import { parseTime } from '@/utils';

export default {
  name: 'Selling',
  data() {
    return {
      tableKey: 0,
      list: null,
      list1: '',
      level: '',
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        sort: '+id',
        keyword: '',
        id: this.$route.params && this.$route.params.id,
      },
    };
  },
  created() {
    this.getCustomer();
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await fetchList1(this.listQuery);
      this.list = data.items;
      /* for (var i = 0; i < this.list.length; i++) {
        this.test[i] = this.list[i].categories;
      } */
      this.listLoading = false;
    },
    async getCustomer() {
      const id = this.$route.params && this.$route.params.id;
      this.listLoading = true;
      const { data } = await fetchCustomer(id);
      this.list1 = data.items;
      this.level = data.level;
      // console.log(this.list1);
      console.log(parseTime);
      this.listLoading = false;
    },
    handleFilter() {
      this.listQuery.page = 1;
      this.getList();
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
    previewArticle(row){

    },
  },
};
</script>

<style lang="scss" scoped>
  .test {
    margin: 45px;
    font-size: 17pt;
    color: #fff;
  }
  .customer {
    width:auto;
    border: 2px solid #f58938;
    border-radius: .428rem;
    padding: 7px;
    display: inline-block;
    float: right;
    // box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
  }
  .title {
    font-size: 13pt;
    font-weight: lighter;
    color: #97a8be;
    margin-left:10px;
  }
  .articles {
    position: relative;
    display: inline-block;
    width: 59%;
    border: 2px solid #f58938;
    border-radius: .428rem;
    padding: 7px;
    margin-top: 15px;
    height: 800px;
    overflow: hidden;
    overflow: scroll;
    // box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
  }
  .bill {
    position: relative;
    display:inline-block;
    width: 39%;
    border: 2px solid #f58938;
    border-radius: .428rem;
    padding: 7px;
    margin-top: 15px;
    float: right;
    height: 250px;
    overflow: hidden;
    overflow: scroll;
    // box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
