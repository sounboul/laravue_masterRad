<template>
  <div class="app-container">
    <el-form v-if="customer" :model="customer">
      <el-row :gutter="20">
        <el-col :span="6">
          <customer-card :customer="customer" />
          <customer-bio />
        </el-col>
        <el-col :span="18">
          <customer-activity :customer="customer" :level="level" />
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
// import CustomerResource from '@/api/customer';
import CustomerBio from './components/CustomerBio';
import CustomerCard from './components/CustomerCard';
import CustomerActivity from './components/CustomerActivity';
import { fetchCustomer } from '@/api/customer';

// const customerResource = new CustomerResource('customers');
export default {
  name: 'EditCustomer',
  components: { CustomerBio, CustomerCard, CustomerActivity },
  data() {
    return {
      customer: {},
      level: '', // Obavezan!
    };
  },
  watch: {
    '$route': 'getCustomer',
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getCustomer(id);
  },
  methods: {
    async getCustomer(id) {
      const { data } = await fetchCustomer(id);
      this.customer = data.items;
      // console.log(this.customer);
      this.level = data.level;
    },
  },
};
</script>
