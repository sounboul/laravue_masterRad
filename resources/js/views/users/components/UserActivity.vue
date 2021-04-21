<template>
  <el-card v-if="user.name">
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      <el-tab-pane v-loading="updating" :label="$t('marketing.api_credentials')" name="first">
        <p>Unos parametara za integraciju putem API-ja</p>
        <p />
        <div style="width: 350px; margin-top: 30px; ">
          <el-form-item :label="$t('login.username')">
            <el-input v-model="user.username" :disabled="user.role === 'admin'" style="width: 220px; float: right;" />
          </el-form-item>
          <el-form-item :label="$t('login.password')">
            <el-input v-model="user.pass" :disabled="user.role === 'admin'" style="width: 220px; float: right;" />
          </el-form-item>
        </div>
        <el-form-item style="margin:26px 0 0 130px; ">
          <el-button type="primary" :disabled="user.role === 'admin'" @click="APIcredentials">
            {{ $t('permission.confirm') }}
          </el-button>
        </el-form-item>
      </el-tab-pane>
      <el-tab-pane :label="$t('user.education')" name="second">
        <div class="block">
          <el-timeline>
            <el-timeline-item timestamp="10.2017. - 04.2021." placement="top">
              <el-card>
                <h4>Akademija tehničko-vaspitačkih STRUKOVNIH STUDIJA - Odsek Niš</h4>
                <p>Master Strukovne studije – Odsek Multimedijalne komunikacione tehnologije</p>
                <p>Zvanje: Master strukovni inženjer elektrotehnike i računarstva</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="10.2012. - 04.2014." placement="top">
              <el-card>
                <h4>Visoka Tehnička Škola STRUKOVNIH STUDIJA u Nišu</h4>
                <p>Specijalističke Strukovne studije – Odsek Informacione tehnologije</p>
                <p>Zvanje: Specijalista strukovni inženjer elektrotehnike i računarstva</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="10.1996. - 06.1999." placement="top">
              <el-card>
                <h4>Viša tehnička škola u Nišu – smer elektrotehnika</h4>
                <p>Zvanje: Inženjer elektrotehnike</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="09.1985. - 06.1989." placement="top">
              <el-card>
                <h4>ETŠ „Mija Stanimirović“ Niš</h4>
                <p>Zvanje: Elektrotehničar za telekomunikacione uređaje</p>
              </el-card>
              <el-card>
                <h4>ETŠ „Nikola Tesla“ Niš</h4>
                <p>Usmereno obrazovanje</p>
              </el-card>
            </el-timeline-item>
          </el-timeline>
        </div>
      </el-tab-pane>
      <el-tab-pane :label="$t('marketing.references')" name="third">
        <div class="user-activity">
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle img-bordered-sm"
                src="images/Memorandum.png"
                alt="User Image"
              >
              <span class="username">
                <a href="https://bexter.rs" target="_blank">BexterDesign</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Neki od dosadašnjih projekata autora</span>
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
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/resource';
import { APIcredentials } from '@/api/article';
const userResource = new Resource('users');

export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          username: '',
          email: '',
          pass: '',
          avatar: '',
          roles: [],
        };
      },
    },
  },
  data() {
    return {
      activeActivity: 'first',
      carouselImages: [
        'images/dental.jpeg',
        'images/tours.jpeg',
        'images/autoskola.jpeg',
        'images/rachelle.jpeg',
      ],
      updating: false,
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    onSubmit() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user)
        .then(response => {
          this.updating = false;
          this.$message({
            message: this.$t('user.edit_success'),
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    async APIcredentials() {
      const { data } = await APIcredentials(this.user);
      console.log(data);
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
</style>
