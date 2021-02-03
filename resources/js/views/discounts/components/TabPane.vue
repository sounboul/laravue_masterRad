<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column
      v-loading="loading"
      align="center"
      label=""
      width="170"
      element-loading-text="Please be patient！"
    >
      <template slot-scope="{row}">
        <el-button type="success" size="mini" @click="handleUpdate(row)">
          {{ $t('table.edit') }}
        </el-button>
      </template>
    </el-table-column>

    <el-table-column class="col" align="center" label="Bodovi">
      <template slot-scope="scope">
        <span>{{ $t('customers.from') }} {{ scope.row.from_point }}  {{ $t('customers.to') }} {{ scope.row.to_point }} {{ $t('customers.total_points1') }}
        </span>
      </template>
    </el-table-column>

    <el-table-column class="col" align="center" label="Nivo">
      <template slot-scope="scope">
        <span>
          {{ scope.row.level | uppercaseFirst }}
        </span>
      </template>
    </el-table-column>

    <!-- <el-table-column class="col" align="center" label="Silver">
      <template slot-scope="scope">
        <span>{{ $t('customers.from') }} {{ scope.row.regular }} {{ $t('customers.to') }} {{ scope.row.silver }} {{ $t('customers.total_points1') }}</span>
      </template>
    </el-table-column>

    <el-table-column class="col" align="center" label="Gold">
      <template slot-scope="scope">
        <span>{{ $t('customers.from') }} {{ scope.row.silver }} {{ $t('customers.to') }} {{ scope.row.gold }} {{ $t('customers.total_points1') }}</span>
      </template>
    </el-table-column>

    <el-table-column class="col" align="center" label="Premium">
      <template slot-scope="scope">
        <span>{{ $t('customers.over') }} {{ scope.row.gold }} {{ $t('customers.total_points1') }}</span>
      </template>
    </el-table-column> -->

    <!-- <el-table-column width="120px" label="Importance">
      <template slot-scope="scope">
        <svg-icon v-for="n in +scope.row.rating" :key="n" icon-class="star" />
      </template>
    </el-table-column> -->

    <!-- <el-table-column align="center" label="Readings" width="95">
      <template slot-scope="scope">
        <span>{{ scope.row.pageviews }}</span>
      </template>
    </el-table-column> -->

    <!-- <el-table-column class-name="status-col" label="Status" width="110">
      <template slot-scope="scope">
        <el-tag :type="scope.row.status | statusFilter">
          {{ scope.row.status }}
        </el-tag>
      </template>
    </el-table-column> -->
  </el-table>
</template>

<script>
import { fetchList } from '@/api/discounts';

export default {
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
      default: 'ME',
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
      loading: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      this.$emit('type', this.type); // for tests
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.loading = false;
    },
  },
};
</script>

