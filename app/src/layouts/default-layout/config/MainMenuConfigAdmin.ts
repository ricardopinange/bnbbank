import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfigAdmin: Array<MenuItem> = [
  {
    heading: "",
    route: "/admin",
    pages: [
      {
        heading: "Checks",
        route: "/admin/checks",
        keenthemesIcon: "calendar-8",
        bootstrapIcon: "bi-wallet-fill",
      },
    ],
  },
];

export default MainMenuConfigAdmin;
