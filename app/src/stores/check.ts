import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Check {
  id: string;
  description: string;
  amount: string;
  picture: string;
  created_at: string;
}

export const useCheckStore = defineStore("check", () => {
  const errors = ref({});
  const checkControl = ref({});
  const checkDetails = ref({});
  const checks = ref<Check>({} as Check);

  function setError(error: any) {
    errors.value = { ...error };
  }

  function getList(params) {
    ApiService.setHeader();
    return ApiService.query("check/list", { params })
      .then(({ data }) => {
        this.checks = data.data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function getCheckControl() {
    ApiService.setHeader();
    return ApiService.query("check/control", null)
      .then(({ data }) => {
        this.checkControl = data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function getCheckDetails(params) {
    ApiService.setHeader();
    return ApiService.query("check/details", { params })
      .then(({ data }) => {
        this.checkDetails = data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function insert(attributes: Check) {
    ApiService.setHeader();
    ApiService.post("check/insert", attributes)
      .then(({ data }) => {
        
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  return {
    errors,
    checks,
    checkControl,
    checkDetails,
    getCheckControl,
    getCheckDetails,
    getList,
    insert,
  };
});
