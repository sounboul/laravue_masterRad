<template>
  <el-card v-if="customer.name">
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      <el-tab-pane v-loading="updating" :label="$t('customers.account')" name="first" class="first">
        <div class="main">
          <div class="part1">
            <el-form-item :label="$t('customers.customer_name')">
              <el-input v-model="customer.name" />
            </el-form-item>
            <el-form-item label="E-mail">
              <el-input v-model="customer.email" />
            </el-form-item>
            <el-form-item :label="$t('customers.mobile')">
              <el-input v-model="customer.mobile" />
            </el-form-item>
            <el-form-item :label="$t('customers.dob') + ': ' ">
              <br>
              <div v-if="customer.dob != null" style="border-radius: .25rem; background-color: #283046; color: #6699ff; padding-left: 15px;">
                {{ customer.dob }}
              </div>
              <div v-else>
                <el-date-picker
                  v-model="customer.dob"
                  type="datetime"
                  :placeholder="$t('discounts.pick_date')"
                />
              </div>
            </el-form-item>
            <el-form-item :label="$t('customers.ID_number')">
              <span v-if="customer.ID_number != null && customer.ID_number.length == 13">
                {{ customer.ID_number }}
              </span>
              <div v-else>
                <el-input v-model="customer.ID_number" />
              </div>
            </el-form-item>
            <el-form-item :label="$t('customers.street')">
              <el-input v-model="customer.street" />
            </el-form-item>
            <el-form-item :label="$t('customers.number')">
              <el-input v-model="customer.number" />
            </el-form-item>
            <el-form-item :label="$t('customers.city')">
              <el-input v-model="customer.city" />
            </el-form-item>
          </div>
          <div class="part2">
            <el-form-item :label="$t('customers.postal_code')">
              <el-input v-model="customer.postal_code" />
            </el-form-item>
            <el-form-item :label="$t('customers.country')">
              <el-input v-model="customer.country" />
            </el-form-item>
            <el-form-item :label="$t('customers.member_since')">
              {{ customer.member_since }}
            </el-form-item>
            <el-form-item :label="$t('customers.total_points') + ': '">
              {{ customer.total_points }}
            </el-form-item>
            <el-form-item :label="$t('customers.level') + ': '">
              {{ level | uppercaseFirst() }}
            </el-form-item>
            <el-form-item :label="$t('customers.facebook')">
              <el-input v-model="customer.facebook_account" />
            </el-form-item>
            <el-form-item :label="$t('customers.instagram')">
              <el-input v-model="customer.instagram_account" />
            </el-form-item>
            <el-form-item :label="$t('customers.twitter')">
              <el-input v-model="customer.twitter_account" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" style="margin-top: 10px; float: right;" @click="onSubmit">
                {{ $t('user.update') }}
              </el-button>
            </el-form-item>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Activity" name="third">
        <div class="user-activity">
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDkaQO69Fro8SZLTVZQ75JH2R0T-sn5yIA_lKGwvvgQ0R0BoQtUQ"
                alt="user image"
              >
              <span class="username text-muted">
                <a href="#">Iron Man</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Shared publicly - 7:30 PM today</span>
            </div>
            <p>
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <ul class="list-inline">
              <li>
                <a href="#" class="link-black text-sm">
                  <i class="el-icon-share" /> Share
                </a>
              </li>
              <li>
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="like" />Like
                </a>
              </li>
              <li class="pull-right">
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="comment" />Comments
                  (5)
                </a>
              </li>
            </ul>
            <el-input placeholder="Type a comment" />
          </div>
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMMN-8f9CQQ3MKJpboBJIqaiJ2Wus2Tf4w_vx9STtalxrY3qGJ"
                alt="user image"
              >
              <span class="username text-muted">
                <a href="#">Captain American</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Sent you a message - yesterday</span>
            </div>
            <p>
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <el-input placeholder="Response">
              <el-button slot="append">
                Send
              </el-button>
            </el-input>
          </div>
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle img-bordered-sm"
                src="https://cdn3.iconfinder.com/data/icons/movies-3/32/daredevil-superhero-marvel-comics-mutant-avatar-512.png"
                alt="User Image"
              >
              <span class="username">
                <a href="#">Daredevil</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Posted 4 photos - 2 days ago</span>
            </div>
            <div class="user-images">
              <el-carousel :interval="6000" type="card" height="200px">
                <el-carousel-item v-for="item in carouselImages" :key="item">
                  <img :src="item" class="image">
                </el-carousel-item>
              </el-carousel>
            </div>
            <ul class="list-inline">
              <li>
                <a href="#" class="link-black text-sm">
                  <i class="el-icon-share" /> Share
                </a>
              </li>
              <li>
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="like" />Like
                </a>
              </li>
              <li class="pull-right">
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="comment" />Comments
                  (5)
                </a>
              </li>
            </ul>
            <el-input placeholder="Type a comment" />
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Timeline" name="second">
        <div class="block">
          <el-timeline>
            <el-timeline-item timestamp="2019/4/17" placement="top">
              <el-card>
                <h4>Update Github template</h4>
                <p>tuandm committed 2019/4/17 20:46</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="2019/4/18" placement="top">
              <el-card>
                <h4>Update Github template</h4>
                <p>tonynguyen committed 2019/4/18 20:46</p>
              </el-card>
              <el-card>
                <h4>Update Github template</h4>
                <p>tuandm committed 2019/4/19 21:16</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="2019/4/19" placement="top">
              <el-card>
                <h4>
                  Deploy
                  <a href="https://laravue.dev" target="_blank">laravue.dev</a>
                </h4>
                <p>tuandm deployed 2019/4/19 10:23</p>
              </el-card>
            </el-timeline-item>
          </el-timeline>
        </div>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import CustomerResource from '@/api/resource';
const customerResource = new CustomerResource('customers');

export default {
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
      dob: '',
    },
    level: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      activeActivity: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      updating: false,
    };
  },
  methods: {
    handleClick(tab, event) {
      // console.log('Switching tab ', tab, event);
      // console.log(parseTime);
    },
    onSubmit() {
      this.updating = true;
      customerResource
        .update(this.customer.id, this.customer)
        .then(response => {
          this.updating = false;
          this.$message({
            message: this.$t('customers.edit_success'),
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
.first {
  /* width:35%; */
}
.third {
  background-color:#283046 !important;
  padding: 10px;
  border-radius: .428rem;
  .el-input {
   width:250px;
  }
  color: #6699ff;
}
.main {
  padding: 0 !important;
}
.part1 {
  display: inline-block;
  position: relative;
  width: 40%;
  top: 30px;
  margin: -30px 0 0 60px;
}
.part2 {
  display: inline-block;
  position: relative;
  width: 40%;
  top: -50px;
  margin: 0 35px 0 90px;
}
</style>
