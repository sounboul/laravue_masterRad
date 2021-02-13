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
          {{ customer.email }}
        </div>
        <div class="user-role text-center text-muted">
          {{ customer.mobile }}
        </div>
      </div>
      <div class="box-social">
        <span style="float: left">
          Facebook:
        </span>
        <span style="float: right;">
          {{ customer.facebook_account }}
        </span>
        <br>
        <span style="float: left">
          Instagram:
        </span>
        <span style="float: right;">
          {{ customer.instagram_account }}
        </span>
        <br>
        <span style="float: left">
          Twitter:
        </span>
        <span style="float: right">
          {{ customer.twitter_account }}
        </span>
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

export default {

  props: {
    customer: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          mobile: '',
          dob: '',
          facebook_account: '',
        };
      },
    },
  },
  data() {
    return {
      social: [
        {
          'name': 'Facebook: ',
          'account': this.customer,
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
  .user-follow {
    padding-top: 20px;
  }
}
.box-social {
  border-radius: .428rem;
  margin-top: 10px;
  padding: 30px 5px !important;
  background-color: #283046;
  color: #fff;
  span{
    display: inline;
  }
}
</style>
