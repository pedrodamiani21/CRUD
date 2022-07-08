<template>
  <v-app id="inspire">
    <v-navigation-drawer class="header" v-model="drawer" app>
      <v-list-item>
        <v-list-item-avatar>
          <v-img
            src="data:image/png;base64,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"
          ></v-img>
        </v-list-item-avatar>
        <v-list-item-title class="header-title"> CRUD </v-list-item-title>
      </v-list-item>

      <v-divider></v-divider>

      <v-list  dense nav>
        <v-list-item  v-for="item in items" :key="item.title" :to="item.to" link>
          <v-list-item-icon class="header-icon">
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content class="header-text">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>CRUD</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "NavBar",
  data: () => ({
    drawer: null,
    items: [
      { title: "Customers", icon: "mdi-account-multiple", to: "/" },
      { title: "Products", icon: "mdi-storefront-outline", to: "/products" },
      { title: "Orders", icon: "mdi-calendar-edit", to: "/orders" },
    ],
  }),
};
</script>

<style scoped>
  .header{
    padding: 16px;
    font-size: 0.875rem;
    line-height: 1.2em!important;
    max-width: 100%;
  }
  .header-title{
    font-size: x-large;
    line-height: 1.25rem;
    letter-spacing: 0.01786em;
  }
  .header-text{
    color:  #757575;
    font-family: Muli,sans-serif;
  }
  .header-icon{
     color:  #757575;
  }
</style>