import type LayoutConfigTypes from "@/layouts/default-layout/config/types";

const config: LayoutConfigTypes = {
  general: {
    mode: "system",
    primaryColor: "#50CD89",
    pageWidth: "default",
    layout: "dark-sidebar",
    iconsType: "solid",
  },
  header: {
    display: true,
    default: {
      container: "fluid",
      fixed: {
        desktop: true,
        mobile: false,
      },
      menu: {
        display: true,
        iconType: "bootstrap",
      },
    },
  },
  sidebar: {
    display: true,
    default: {
      minimize: {
        desktop: {
          enabled: true,
          default: false,
          hoverable: true,
        },
      },
      menu: {
        iconType: "bootstrap",
      },
    },
  },
  toolbar: {
    display: true,
    container: "fluid",
    fixed: {
      desktop: false,
      mobile: false,
    },
  },
  pageTitle: {
    display: true,
    breadcrumb: true,
    direction: "column",
  },
  content: {
    container: "fluid",
  },
  footer: {
    display: true,
    container: "fluid",
    fixed: {
      desktop: false,
      mobile: false,
    },
  },
  illustrations: {
    set: "sketchy-1",
  },
  scrolltop: {
    display: true,
  },
};

export default config;
