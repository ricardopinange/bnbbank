import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
  {
    heading: "",
    route: "/customer",
    pages: [
      {
        heading: "Balance",
        route: "/customer/balance",
        keenthemesIcon: "calendar-8",
        bootstrapIcon: "bi-currency-dollar",
      },
      {
        heading: "Incomes",
        route: "/customer/incomes",
        keenthemesIcon: "calendar-8",
        bootstrapIcon: "bi-arrow-90deg-up",
      },
      {
        heading: "Expenses",
        route: "/customer/expenses",
        keenthemesIcon: "calendar-8",
        bootstrapIcon: "bi-arrow-90deg-down",
      },
      {
        heading: "Checks",
        route: "/customer/checks",
        keenthemesIcon: "calendar-8",
        bootstrapIcon: "bi-wallet-fill",
      },
    ],
  },
];

export default MainMenuConfig;
