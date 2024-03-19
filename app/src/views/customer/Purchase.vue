<template>
  <!--begin:Body-->
  <div class="d-flex flex-wrap flex-stack my-5 justify-content-center">
          <!--begin:Form-->
          <el-form
            id="kt_add_purchase_form"
            @submit.prevent="submit()"
            :model="formData"
            :rules="rules"
            ref="formRef"
            class="form"
          >
            <!--begin::Heading-->
            <div class="mb-13 text-center">
              <!--begin::Title-->
              <h1 class="mb-3">Purchase</h1>
              <!--end::Title-->
            </div>
            <!--end::Heading-->

            <!--begin::Input group-->
            <div class="d-flex flex-column mb-8 fv-row">
              <!--begin::Label-->
              <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                <span class="required">Amount</span>
              </label>
              <!--end::Label-->

              <el-form-item prop="amount">
                <el-input
                  v-model="formData.amount"
                  placeholder=""
                  name="amount"
                  ref="inputRef"
                  :value="formattedValue"
                ></el-input>
              </el-form-item>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="d-flex flex-column mb-8 fv-row">
              <!--begin::Label-->
              <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                <span class="required">Description</span>
              </label>
              <!--end::Label-->

              <el-form-item prop="description">
                <el-input
                  v-model="formData.description"
                  placeholder=""
                  name="description"
                ></el-input>
              </el-form-item>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="text-center">
              <!--begin::Button-->
              <button
                :data-kt-indicator="loading ? 'on' : null"
                class="btn btn-lg btn-primary"
                type="submit"
              >
                <span v-if="!loading" class="indicator-label">
                  ADD PURCHASE
                </span>
                <span v-if="loading" class="indicator-progress">
                  Please wait...
                  <span
                    class="spinner-border spinner-border-sm align-middle ms-2"
                  ></span>
                </span>
              </button>
              <!--end::Button-->
            </div>
            <!--end::Actions-->
          </el-form>
          <!--end:Form-->
  </div>
  <!--end:Body-->
</template>

<style lang="scss">
.el-select {
  width: 100%;
}

.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
</style>

<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, ref } from "vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useCurrencyInput } from "vue-currency-input";
import { useTransactionStore, type Transaction } from "@/stores/transaction";
import { useRouter } from "vue-router";

interface Transaction {
  amount: string;
  description: string;
  type: string;
}

export default defineComponent({
  name: "customer-purchase",
  components: {},
  setup() {
    const formRef = ref<null | HTMLFormElement>(null);
    const loading = ref<boolean>(false);
    const { formattedValue, inputRef } = useCurrencyInput({ currency: "USD", "locale": "en-US" });
    const store = useTransactionStore();
    const router = useRouter();

    const formData = ref<Transaction>({
      amount: "",
      description: "",
      type: "Debit",
    });

    const rules = ref({
      amount: [
        {
          required: true,
          message: "Please input amount",
          trigger: "blur",
        },
      ],
      description: [
        {
          required: true,
          message: "Please input description",
          trigger: "blur",
        },
      ]
    });

    function submit () {
      if (!formRef.value) {
        return;
      }

      formRef.value.validate((valid: boolean) => {
        if (valid) {
          loading.value = true;
          store.insert(formData.value);

          setTimeout(() => {
            loading.value = false;
            let error = Object.values(store.errors);

            if (error.length == 0) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                heightAuto: false,
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(() => {
                router.push({ name: "customer-balance" });
              });
            
            } else {
              Swal.fire({
                text: error[0] as string,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                heightAuto: false,
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
              return false;
            }

          }, 1000)

        }
      });
    };

    return {
      formData,
      submit,
      loading,
      formRef,
      rules,
      getAssetPath,
      inputRef,
      formattedValue,
    };
  },
});
</script>

<style lang="scss">
.override-styles {
  z-index: 99999 !important;
  pointer-events: initial;
}
</style>
