<template>
  <el-card v-if="user.name">
    <div class="user-profile">
      <div class="user-avatar box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />
      </div>
      <div class="box-center">
        <div class="user-name text-center">
          {{ user.name }}
        </div>
        <div class="user-role text-center text-muted">
          {{ user.email }}
        </div>
      </div>
      <div class="box-social">
        <el-table :data="social" :show-header="false">
          <el-table-column prop="name" label="Name" />
          <el-table-column label="Count" align="right" width="235">
            <template slot-scope="scope">
              {{ scope.row.account }}
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb';

export default {
  components: { PanThumb },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
        };
      },
    },
  },
  data() {
    return {
      social: [
        {
          'name': 'Facebook: ',
          'account': 'facebook.com/bexter.rs',
        },
        {
          'name': 'Instagram: ',
          'account': 'instagram.com/bexterserbia',
        },
        {
          'name': 'Twitter: ',
          'account': '-',
        },
      ],
    };
  },
  methods: {
    getRole() {
      const roles = this.user.roles.map(value => this.$options.filters.uppercaseFirst(value));
      return roles.join(' | ');
    },
  },
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    margin-left: -7px;
    width: 340px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
