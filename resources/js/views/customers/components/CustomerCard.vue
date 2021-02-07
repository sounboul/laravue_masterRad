<template>
  <el-card v-if="customer.name">
    <div class="user-profile">
      <!--  <div class="user-avatar box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />
      </div> -->
      <div class="box-center">
        <div class="user-name text-center">
          {{ customer.name }}
        </div>
        <div class="user-role text-center text-muted">
          {{ test }}
        </div>
      </div>
      <div class="box-social">
        <el-table :data="social" :show-header="false">
          <el-table-column prop="name" label="Name" />
          <el-table-column label="Count" align="right" width="250">
            <template slot-scope="scope">
              {{ scope.row.account }}
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="user-follow">
        <el-button type="primary" style="width: 100%;">
          Follow
        </el-button>
      </div>
    </div>
  </el-card>
</template>

<script>
// import PanThumb from '@/components/PanThumb';

export default {
  // components: { PanThumb },
  props: {
    customer: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          mobile: '',
        };
      },
    },
  },
  data() {
    return {
      test: 'Proba',
      social: [
        {
          'name': 'Facebook: ',
          'account': 'facebook.account',
        },
        {
          'name': 'Instagram: ',
          'account': 'instagram.account',
        },
        {
          'name': 'Twitter: ',
          'account': 'twitter.account',
        },
      ],
    };
  },
  methods: {

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
