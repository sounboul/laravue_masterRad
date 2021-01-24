<template>
  <div class="errPage-container">
    <el-button icon="arrow-left" class="pan-back-btn" @click="back">
      {{ $t('errors.back') }}
    </el-button>
    <el-row>
      <el-col :span="12">
        <h1 class="text-jumbo text-ginormous">
          Oops!
        </h1>
        <h2 style="color: #fff;">
          {{ $t('errors.have_no_permission') }}
        </h2>
        <h6 style="color: #fff;">
          {{ $t('errors.contact_administrator') }}
        </h6>
        <ul class="list-unstyled" style="list-style: none;">
          <li style="color: #484848;">
            {{ $t('errors.or_go') }}
          </li>
          <li class="link-type">
            <router-link to="/dashboard">
              {{ $t('route.dashboard') }}
            </router-link>
          </li>
          <li class="link-type">
            <a href="https://www.google.com/">just looking around</a>
          </li>
          <li><a href="#" @click.prevent="dialogVisible=true">{{ $t('errors.show_picture') }}</a></li>
        </ul>
      </el-col>
      <el-col :span="12">
        <img :src="errGif" width="313" height="428" alt="Girl has dropped her ice cream.">
      </el-col>
    </el-row>
    <el-dialog :visible.sync="dialogVisible" title="Casual look">
      <img :src="ewizardClap" class="pan-img">
    </el-dialog>
  </div>
</template>

<script>
import errGif from '@/assets/401_images/401.gif';

export default {
  name: 'Page401',
  data() {
    return {
      errGif: errGif + '?' + +new Date(),
      ewizardClap: 'https://i.redd.it/7jvcbsfjz1h11.gif',
      dialogVisible: false,
    };
  },
  methods: {
    back() {
      if (this.$route.query.noGoBack) {
        this.$router.push({ path: '/dashboard' });
      } else {
        this.$router.go(-1);
      }
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .errPage-container {
    width: 800px;
    max-width: 100%;
    margin: 100px auto;
    .pan-back-btn {
      background: #008489;
      color: #fff;
      border: none!important;
    }
    .pan-gif {
      margin: 0 auto;
      display: block;
    }
    .pan-img {
      display: block;
      margin: 0 auto;
      width: 100%;
    }
    .text-jumbo {
      font-size: 60px;
      font-weight: 700;
      color: #484848;
    }
    .list-unstyled {
      font-size: 14px;
      li {
        padding-bottom: 5px;
      }
      a {
        color: #fff;
        text-decoration: none;
        &:hover {
          text-decoration: underline;
        }
      }
    }
  }
</style>
