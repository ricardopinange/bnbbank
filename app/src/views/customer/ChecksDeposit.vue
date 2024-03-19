<template>
  <!--begin:Body-->
  <div class="d-flex flex-wrap flex-stack my-5 justify-content-center">
          <!--begin:Form-->
          <el-form
            id="kt_new_check_form"
            @submit.prevent="submit()"
            :model="formData"
            :rules="rules"
            ref="formRef"
            class="form"
          >
            <!--begin::Heading-->
            <div class="mb-13 text-center">
              <!--begin::Title-->
              <h1 class="mb-3">Check Deposit</h1>
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

            <!--begin::Input group-->
            <div class="d-flex flex-column mb-8 fv-row">
              <!--begin::Label-->
              <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                <span class="required">Description</span>
              </label>
              <!--end::Label-->

              <el-form-item prop="picture">
                <el-input
                  v-model="formData.picture"
                  placeholder=""
                  name="picture"
                  type="file"
                  @change="handleFileUpload"
                  accept="image/*"
                  rel="picture"
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
                  DEPOSIT CHECK
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
import { useCheckStore, type Check } from "@/stores/check";
import { useRouter } from "vue-router";

interface Check {
  amount: string;
  description: string;
  picture: string;
}

export default defineComponent({
  name: "customer-checks-deposit",
  components: {},
  setup() {
    const formRef = ref<null | HTMLFormElement>(null);
    const newCheckModalRef = ref<null | HTMLElement>(null);
    const loading = ref<boolean>(false);
    const file = ref<File | null>();
    const { formattedValue, inputRef } = useCurrencyInput({ currency: "USD", "locale": "en-US" });
    const store = useCheckStore();
    const router = useRouter();

    const formData = ref<Check>({
      amount: "",
      description: "",
      picture: "",
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
      ],
      picture: [
        {
          required: true,
          message: "Please select picture",
          trigger: "change",
        },
      ],
    });

    function handleFileUpload (e) {
      file.value = event.target.files[0];
    }

    const submit = async () => {
      if (!formRef.value) {
        return;
      }

      formRef.value.validate((valid: boolean) => {
        if (valid) {
          loading.value = true;

          formData.value.picture = file.value;
          store.insert(formData.value);
          const error = Object.values(store.errors);

          setTimeout(() => {
            loading.value = false;

            if (error.length === 0) {
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
          }, 2000);
        } else {
          Swal.fire({
            text: "Sorry, looks like there are some errors detected, please try again.",
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
      });
    };

    return {
      formData,
      submit,
      loading,
      formRef,
      rules,
      newCheckModalRef,
      getAssetPath,
      inputRef,
      formattedValue,
      handleFileUpload,
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
