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
      <span style="color: #eee;">{{ message }}</span>
    </div>
  </div>
</template>

<script>

import { getCategories, executeQuery } from '@/api/category';

export default {
  data() {
    return {
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
    };
  },
  methods: {
    async getCategories() {
      this.listLoading = true;
      const { data } = await getCategories(this.form);
      this.categories = data.items;
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
    async executeQuery() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.form.date1 = new Date(this.form.date1).toLocaleString('en-EN', { timeZone: 'Europe/Belgrade' });
          executeQuery(this.form).then((response) => {
            this.message = response.message;
            console.log(this.message);
            this.$notify({
              title: this.$t('table.success'),
              message: this.$t('table.created_successfully'),
              type: 'success',
              duration: 3000,
            });
          });
        }
      });
    },
    onSubmit() {
      this.active1 = !this.active1;
      this.executeQuery();
      // this.$message('submit!');
    },
    onCancel() {},
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
