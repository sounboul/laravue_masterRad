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
          {{ getRole() }}
        </div>
      </div>
      <div class="box-social">
        <el-table :data="social" :show-header="false">
          <el-table-column prop="name" />
          <el-table-column align="right" width="200">
            <template slot-scope="scope">
              {{ scope.row.account }}
            </template>
            <!-- {{ user.account }} -->
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
          f_account: '',
          i_account: '',
          l_account: '',
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
          'account': '-',
        },
        {
          'name': 'Instagram: ',
          'account': '-',
        },
        {
          'name': 'Linked In: ',
          'account': '-',
        },
      ],
    };
  },
  watch: {
    user(user) {
      this.social = [
        { 'name': 'Facebook:',
          'account': this.user.f_account,
        },
        { 'name': 'Instagram:',
          'account': this.user.i_account,
        },
        { 'name': 'Linked In:',
          'account': this.user.l_account,
        },
      ];
    },
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
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
