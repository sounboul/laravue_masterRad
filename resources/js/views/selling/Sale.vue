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
        <el-table-column :label="$t('articles.in_stock') + ' (' + $t('articles.pieces') + ')'" width="120px" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.amount }}</span>
          </template>
        </el-table-column>
        <!-- <el-table-column :label="$t('articles.category')" width="120px" align="center">
          <template slot-scope="scope">
            <span>{{ scope.row.categories.name }}</span>
          </template>
        </el-table-column> -->
        <el-table-column :label="$t('stores.location')" width="120px" align="center">
          <template slot-scope="scope">
            <div v-if="scope.row.store.length > 0">
              <div class="hasTooltip" @mouseover="previewArticle()">{{ scope.row.store.length }}
                <span>
                  <div v-for="(n, index) in scope.row.store" :key="index" style="padding: 2px 5px;">
                    {{ scope.row.store[index].address }}
                  </div>
                </span>
              </div>
            </div>
            <div v-else>{{ $t('-') }}</div>
          </template>
        </el-table-column>
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
      // const id = this.$route.params && this.$route.params.id;
      this.listLoading = true;
      const { data } = await fetchCustomer(this.listQuery.id);
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
      console.log(row);
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
    max-height: 700px;
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

  /* Tooltip container */
  .tooltip {
    position: relative;
    display: inline-block;
    //border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
  }

  /* Tooltip text */
  .tooltip .tooltiptext {
    visibility: hidden;
    width: 220px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 9999;
    top: -5px;
    right: 105%;
  }

  /* Show the tooltip text when you mouse over the tooltip container */
  .tooltip:hover .tooltiptext {
    visibility: visible;
  }

  .hasTooltip span {
      display: none;
      color: #000;
      text-decoration: none;
      padding: 3px;
  }

  .hasTooltip:hover span {
      display: block;
      z-index: 9999;
      position: absolute;
      top: -25px;
      right: 75%;
      color: #fff;
      background-color: #283046;
      border-radius: .428rem;
      box-shadow: 5px 10px 10px #001133, -5px -5px 10px #001133 !important;
      margin: 2px 10px;
      width: 200px;
      visibility: visible;
      owerflow: visible;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
