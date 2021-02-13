<template>
  <div class="sale-container">
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
        style="width: 100%; word-break: break-word; font-size: 15pt; border-radius: .428rem;"
      >
        <el-table-column :label="$t('table.code')" align="center" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.name')" min-width="120px">
          <template slot-scope="{row}">
            <span style="margin-top: 2px; cursor: pointer;" @click="articleList(row)">{{ row.title }}</span>
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
        <el-table-column :label="$t('stores.location')" width="120px" align="center">
          <template slot-scope="scope">
            <div v-if="scope.row.store.length > 0">
              <div class="hasTooltip" @mouseover="test">{{ scope.row.store.length }}
                <span>
                  <div v-for="(n, index) in scope.row.store" :key="index" style="padding: 2px 5px;">
                    {{ scope.row.store[index].address }}
                  </div>
                </span>
              </div>
            </div>
            <div v-else>-</div>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="bill">
      <el-table
        :key="tableKey"
        :data="pomBill.billList"
        fit
        highlight-current-row
        style="width: 100%; word-break: break-word; font-size: 15pt; border-radius: .428rem; margin-bottom: 10px;"
      >
        <el-table-column :label="$t('table.code')" align="center" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.name')" min-width="120px">
          <template slot-scope="{row}">
            <span style="margin-top: 2px; cursor: pointer;" @click="articleUndo(row)">{{ row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('articles.price') + ' (' + $t('articles.currency') + ')'" width="150px" align="center">
          <template slot-scope="scope">
            <span>{{ currencyFormatEU(scope.row.price/100, 2) }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div style="display: inline;">
        <div style="float: left;">
          {{ $t('articles.pieces') }}: {{ pomBill.billList.length }}
        </div>
        <div style="float:right;">
          {{ $t('articles.total') }}: {{ currencyFormatEU(listItem.bill / 100, 2) }} {{ $t('articles.currency') }}
        </div>
      </div>
      <br>
      <div style="float: right;">{{ $t('customers.total_points1') + ': ' + listItem.earnedPoints }}</div>
      <br>
      <el-button style="float: left; margin-top: 12px; width: 150px;" type="danger" class="pan-btn light-blue-btn" @click="resetBill">{{ $t('permission.delete') }}</el-button>
      <el-button :disabled="pomBill.billList.length == 0" style="float: right; margin-top: 12px; width: 150px;" type="success" class="pan-btn blue-btn" @click="onClick">
        {{ $t('articles.end_of_bill') }}
      </el-button>
      <confirm-modal ref="confirm" :test="pomBill.billList" :listitem="listItem" :title="$t('table.sure')" />
    </div>
  </div>
</template>

<script>

import { billUpdate, listing } from '@/api/sale';
import { fetchCustomer } from '@/api/customer';
import { fetchList1 } from '@/api/article';
import { parseTime } from '@/utils';
import ConfirmModal from './components/ConfirmModal';

export default {
  name: 'Selling',
  components: {
    ConfirmModal,
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      list1: '',
      level: '',
      listLoading: true,
      default_point_value: 1000,
      max_points: 500,
      listQuery: {
        page: 1,
        limit: 10,
        sort: '+id',
        keyword: '',
        id: this.$route.params && this.$route.params.id,
      },
      listItem: {
        id: this.$route.params && this.$route.params.id,
        bill: 0,
        earnedPoints: 0,
        order_id: 0,
        articleUpdate: '',
        listItem1: [],
      },
      pomBill: {
        billList: [],
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
    articleList(row){
      const clicked_article_list_id = this.list.findIndex(x => x.id === row.id);
      const pom = this.pomBill.billList.concat(row);
      this.pomBill.billList = pom;
      this.list[clicked_article_list_id].amount = this.list[clicked_article_list_id].amount - 1;
      this.listItem.bill = this.listItem.bill + row.price;
      this.listItem.earnedPoints = this.points(this.listItem.bill);
    },
    articleUndo(row){
      const clicked_article = this.pomBill.billList.find(x => x.id === row.id);
      const added_row = this.list.find(x => x.code === row.code);
      added_row.amount = added_row.amount + 1;
      for (var i = 0; i < this.pomBill.billList.length; i++) {
        if (this.pomBill.billList[i].id === clicked_article.id) {
          this.pomBill.billList.splice(i, 1);
        }
      }
      this.listItem.bill = this.listItem.bill - row.price;
      this.listItem.earnedPoints = this.points(this.listItem.bill);
    },
    test() {
    },
    points(bill) {
      var temp = Math.floor((this.listItem.bill / 100) / this.default_point_value);
      return temp > this.max_points ? this.max_points : temp;
    },
    confirmBill() {

    },
    async submitBill() {
      this.listLoading = true;
      this.listItem.articleUpdate = await listing(this.pomBill);
      console.log(this.listItem.articleUpdate.time);
      const { data } = await billUpdate(this.listItem);
      this.articleUpdate1 = data;
      this.listLoading = false;
      this.resetBill();
    },
    resetBill(){
      this.getCustomer();
      this.getList();
      this.listItem.earnedPoints = 0;
      this.listItem.bill = 0;
      this.pomBill.billList = [];
    },
    onClick() {
      this.$refs.confirm
        .show()
        .then(() => {
          this.submitBill();
        })
        .catch(() => {

        });
    },
  },
};
</script>

<style lang="scss" scoped>
  .sale-container {
    margin: 45px;
    font-size: 17pt;
    color: #fff;
  }
  .customer {
    width:auto;
    // border: 2px solid #4d4d4d;
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
    z-index: 0;
    position: relative;
    display: inline-block;
    width: 59%;
    // border: 2px solid #4d4d4d;
    border-radius: .428rem;
    padding: 10px 0 0 10px;
    margin-top: 15px;
    max-height: 700px;
    //overflow: hidden;
    overflow: scroll;
    // box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
  }
  .bill {
    position: relative;
    display:inline-block;
    width: 39%;
    // border: 2px solid #4d4d4d;
    border-radius: .428rem;
    padding: 10px 0 0 10px;
    margin-top: 15px;
    float: right;
    height: auto;
    overflow: hidden;
    overflow: scroll;
    // box-shadow: 5px 10px 10px #001133, -5px 10px 10px #001133 !important;
  }
  .hasTooltip span {
    display: none;
    color: #000;
    text-decoration: none;
    padding: 3px;
  }
  .hasTooltip:hover span {
    display: block;
    z-index: 1;
    position: absolute;
    top: 0;
    right: 75%;
    color: #fff;
    background-color: #283046;
    border-radius: .428rem;
    box-shadow: 5px 10px 10px #001133, -5px -5px 10px #001133 !important;
    margin: 2px 10px;
    width: 200px;
    //visibility: visible;
    overflow: visible;
  }
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300;1,400&family=Raleway:wght@500&display=swap');
</style>
