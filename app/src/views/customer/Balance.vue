<template>
  <!--begin::Tables Widget 10-->
  <div :class="widgetClasses" class="card">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label text-muted fw-semibold fs-3 mb-1">
          <span v-if="currentMonth">Current</span>
          <span v-else>Month</span>
          balance
        </span>

        <span class="mt-1 fw-bold fs-18"
          v-if="currentMonth"
          >${{currentBalance.balance}}</span
        >
        <span class="mt-1 fw-bold fs-18"
          v-else
          >${{monthBalance.balance}}</span
        >
      </h3>
      <div class="card-toolbar">
        <!--begin::Input group-->
        <div class="fv-row mb-10">
          <!--begin::Label-->
          <label class="fs-5 fw-semibold form-label mb-5"
            ></label
          >
          <!--end::Label-->

          <!--begin::Input-->
          <el-form-item prop="name">
            <el-date-picker type="month"
              v-model="formData.monthYear"
              @change="changeMonthYear($event)"
              format="MMMM, YYYY"
              value-format="YYYY-MM"
              :clearable="false"
              :editable="false"
              >
            </el-date-picker>
          </el-form-item>
          <!--end::Input-->
        </div>
        <!--end::Input group-->
      </div>
    </div>
    <!--end::Header-->
  </div>
  <!--end::Tables Widget 10-->

  <!--begin::Tables Widget 10-->
  <div :class="widgetClasses" class="card">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label text-muted fw-semibold fs-3 mb-1">Incomes</span>

        <span class="mt-1 fw-bold fs-18"
          >${{monthBalance.incomes}}</span
        >
      </h3>
      <div
        class="card-toolbar"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        data-bs-trigger="hover"
        title="Deposit a check"
      >
        <router-link to="/customer/checks/deposit" class="btn btn-sm btn-light-primary">
          <KTIcon icon-name="plus" icon-class="fs-3" />
          <br>DEPOSIT A CHECK
        </router-link>
      </div>
    </div>
    <!--end::Header-->
  </div>
  <!--end::Tables Widget 10-->

  <!--begin::Tables Widget 10-->
  <div :class="widgetClasses" class="card">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label text-muted fw-semibold fs-3 mb-1">Expenses</span>

        <span class="mt-1 fw-bold fs-18"
          >${{monthBalance.expenses}}</span
        >
      </h3>
      <div
        class="card-toolbar"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        data-bs-trigger="hover"
        title="Purchase"
      >
        <router-link to="/customer/purchase" class="btn btn-sm btn-light-primary">
          <KTIcon icon-name="plus" icon-class="fs-3" />
          <br>PURCHASE
        </router-link>
      </div>
    </div>
    <!--end::Header-->
  </div>
  <!--end::Tables Widget 10-->

  <!--begin::Tables Widget 10-->
  <div :class="widgetClasses" class="card">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label text-muted fw-semibold fs-3 mb-1">Transactions</span>
      </h3>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body pt-3">
      <!--begin::Table container-->
      <div class="table-responsive">
        <!--begin::Table-->
        <table
          class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4"
        >
          <!--begin::Table head-->
          <thead>
            <tr class="border-0">
              <th class="p-0"></th>
              <th class="p-0 min-w-100px text-end"></th>
            </tr>
          </thead>
          <!--end::Table head-->

          <!--begin::Table body-->
          <tbody>
            <template v-for="(item, index) in list" :key="index">
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <!--begin::Name-->
                    <div class="d-flex justify-content-start flex-column">
                      <a
                        href="#"
                        class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6"
                        >{{ item.description }}</a
                      >

                      <a
                        href="#"
                        class="text-muted text-hover-primary fw-semibold text-muted d-block fs-7"
                      >
                        {{ formatDate(item.created_at) }}
                      </a>
                    </div>
                    <!--end::Name-->
                  </div>
                </td>

                <td class="text-end">
                  <a
                    href="#"
                    class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6"
                    >
                    <span v-if="item.type == 'Debit'" class="text-danger">-${{ item.amount }}</span>
                    <span v-else-if="item.type == 'Credit'">${{ item.amount }}</span>
                    </a
                  >
                </td>
                
              </tr>
            </template>
          </tbody>
          <!--end::Table body-->
        </table>
        <span class="d-flex justify-content-center align-items-center" v-if="list?.length == 0">No records found</span>
        <!--end::Table-->
      </div>
      <!--end::Table container-->
    </div>
    <!--begin::Body-->
  </div>
  <!--end::Tables Widget 10-->
</template>

<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, ref } from "vue";
import Dropdown2 from "@/components/dropdown/Dropdown2.vue";
import { useTransactionStore } from "@/stores/Transaction";
import moment from 'moment';

export default defineComponent({
  name: "customer-balance",
  components: {
    Dropdown2,
  },
  props: {
    widgetClasses: String,
  },
  methods: {
    formatDate(value){
        if (value) {
          return moment(String(value)).format('MM/DD/YYYY hh:mm A')
        }
    },
  },
  setup() {
    const store = useTransactionStore();
    const currentDate = new Date().toISOString().slice(0,10);
    let list = ref({});
    let currentBalance = ref({});
    let monthBalance = ref({});

    const formData = ref({
      monthYear: ref(currentDate),
    });

    const filter = {
      year: currentDate.slice(0,-6),
      month: +currentDate.slice(5,7),
    }

    const initialize = (e) => {
      store.getList(e);
      store.getCurrentBalance();
      store.getMonthBalance(e);
      setTimeout(() => {
        list.value = store.transactions;
        currentBalance.value = store.currentBalance;
        monthBalance.value = store.monthBalance;
      }, 1000)
    }

    function changeMonthYear(e) {
      filter.year = e.slice(0,-3);
      filter.month = +e.slice(5,7);
      initialize(filter);
      currentMonth();
    }

    let currentMonth = () => {
      return currentDate.slice(0,7) == formData.value.monthYear;
    }

    initialize(filter);

    return {
      list,
      currentBalance,
      monthBalance,
      formData,
      currentDate,
      currentMonth,
      initialize,
      getAssetPath,
      changeMonthYear,
    };
  },
});
</script>
