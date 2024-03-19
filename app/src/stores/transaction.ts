import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Transaction {
  id: string;
  description: string;
  amount: string;
  type: string;
  created_at: string;
}

export const useTransactionStore = defineStore("transaction", () => {
  const errors = ref({});
  const currentBalance = ref({});
  const monthBalance = ref({});
  const transactions = ref<Transaction>({} as Transaction);

  function setError(error: any) {
    errors.value = { ...error };
  }

  function getList(params) {
    ApiService.setHeader();
    return ApiService.query("transaction/list", { params })
      .then(({ data }) => {
        this.transactions = data.data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function getCurrentBalance() {
    ApiService.setHeader();
    return ApiService.query("transaction/current-balance", null)
      .then(({ data }) => {
        this.currentBalance = data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function getMonthBalance(params) {
    ApiService.setHeader();
    return ApiService.query("transaction/month-balance", { params })
      .then(({ data }) => {
        this.monthBalance = data.data;
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  function insert(attributes: Transaction) {
    ApiService.setHeader();
    ApiService.post("transaction/insert", attributes)
      .then(({ data }) => {
        
      })
      .catch(({ response }) => {
        setError(response.data.errors);
      });
  }

  return {
    errors,
    transactions,
    currentBalance,
    monthBalance,
    getCurrentBalance,
    getMonthBalance,
    getList,
    insert,
  };
});
