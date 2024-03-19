<template>

  <div class="card-toolbar w-md-25">
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

  <!--begin::Tables Widget 10-->
  <div :class="widgetClasses" class="card">
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
  name: "customer-incomes",
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

    const formData = ref({
      monthYear: ref(currentDate),
    });

    const filter = {
      year: currentDate.slice(0,-6),
      month: +currentDate.slice(5,7),
      type: 'Credit'
    }

    const initialize = (e) => {
      store.getList(e);
      setTimeout(() => {
        list.value = store.transactions;
      }, 1000)
    }

    function changeMonthYear(e) {
      filter.year = e.slice(0,-3);
      filter.month = +e.slice(5,7);
      initialize(filter);
    }

    initialize(filter);

    return {
      list,
      formData,
      initialize,
      getAssetPath,
      changeMonthYear,
    };
  },
});
</script>
