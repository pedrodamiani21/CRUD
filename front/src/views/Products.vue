<template >
  <v-card class="pa-8 align-center">
    <v-card-title class="align-center">Products</v-card-title>
    <error-alert v-show="errorAlert" />
    <sucess-alert v-show="sucessAlert" />
    <v-row>
      <v-col cols="10">
        <v-text-field
          dense
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
          outlined
          @click:append="getProducts()"
          @keyup.enter="getProducts()"
          color="secondary"
        ></v-text-field>
      </v-col>

      <v-col>
        <v-btn color="secondary" dark @click="openProductModal()">
          <span>New Product</span>
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <br />

    <v-spacer></v-spacer>
    <v-data-table
      :headers="headers"
      class="elevation-1"
      :items="products.data"
      :loading="loading"
      :options.sync="options"
     :server-items-length="products.total"
      @pagination="updatePage"
      :footer-props="{
        'items-per-page-options': [20],
        'disable-items-per-page': true,
      }"
    >
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="text-h8">Product ID: {{ editedItem.id }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.name"
                  label="Name"
                  outlined
                  color="secondary"
                ></v-text-field>
                <vuetify-money
                  v-bind:options="options"
                  v-model="editedItem.price"
                  prefix="R$"
                  label="Price"
                  outlined
                  color="secondary"
                ></vuetify-money>
                <v-text-field
                  v-model="editedItem.bar_code"
                  label="Bar Code"
                  outlined
                  color="secondary"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="secondary" text @click="close"> Cancel </v-btn>
              <v-btn color="secondary" text @click="save"> Save </v-btn>
            </v-card-actions>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-card>
        <v-card-title class="text-h5"
          >Are you sure you want to delete this Product
          {{ editedItem.id }}?</v-card-title
        >

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.name"
                  label="Name"
                  outlined
                  readonly
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.price"
                  prefix="R$"
                  label="Price"
                  outlined
                  readonly
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.bar_code"
                  label="Bar Code"
                  outlined
                  readonly
                  color="secondary"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="secondary" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="secondary" text @click="deleteItemConfirm"
                >OK</v-btn
              >
            </v-card-actions>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogAdd" max-width="500px">
      <v-card>
        <v-container>
          <v-card-title class="text-h5">Add New Product</v-card-title>

          <v-card-text>
            <v-container>
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="newProduct.name"
                      label="Name"
                      type="text"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Name is required']"
                      color="secondary"
                    ></v-text-field>
                    <vuetify-money
                      v-bind:options="options"
                      v-model="newProduct.price"
                      label="Price"
                      outlined
                      required
                      color="secondary"
                      :rules="[(v) => !!v || 'Price is required']"
                    ></vuetify-money>
                    <v-text-field
                      v-model="newProduct.bar_code"
                      label="Bar Code"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Bar Code is required']"
                      color="secondary"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="closeAdd">Cancel</v-btn>
                  <v-btn color="secondary" text @click="addProduct()">OK</v-btn>
                </v-card-actions>
              </v-form>
            </v-container>
          </v-card-text>
        </v-container>
      </v-card>
    </v-dialog>
  </v-card>
</template>


<script>
/* eslint-disable */
import axios from "axios";
import errorAlert from "../components/ErrorAlert.vue";
import sucessAlert from "../components/SucessAlert.vue";
export default {
  components: { errorAlert, sucessAlert },
  name: "Products",
  data() {
    return {
      headers: [
        { text: "ID", value: "id" },
        { text: "Name", value: "name" },
        { text: "Price", value: "price" },
        { text: "Bar Code", value: "bar_code" },
        { text: "Options", value: "actions", sortable: false },
      ],
      errorAlert: false,
      sucessAlert: false,
      search: [],
      options: {},
      dialog: false,
      valid: false,
      dialogDelete: false,
      dialogAdd: false,
      loading: false,
      products: [],
      editedIndex: -1,
      editedItem: {
        name: "",
        price: "",
        bar_code: "",
      },
      newProduct: {
        name: "",
        price: "",
        bar_code: "",
      },
      options: {
        locale: "pt-BR",
        prefix: "R$",
        suffix: "",
        precision: 2,
      },
    };
  },

  created() {
    this.getProducts();
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  methods: {
    async getProducts() {
      Object.assign(this.search);
      this.loading = true;
      try {
        let products = await axios.get(
          `http://localhost:8000/api/products?page=${this.options.page}&q=${this.search}`
        );
        this.products = products.data[0];
        this.loading = false;
      } catch (error) {
        console.log(error);
        this.loading = false;
      }
    },

    openProductModal() {
      this.dialogAdd = true;
    },

    async addProduct() {
      if (this.$refs.form.validate()) {
        try {
          await axios.post(`http://localhost:8000/api/product/create`, {
            name: this.newProduct.name,
            price: this.newProduct.price,
            bar_code: this.newProduct.bar_code,
          });
          this.sucessAlert = true;
        } catch {
          this.errorAlert = true;
        }

        this.$refs.form.reset();
        this.dialogAdd = false;
      }
    },

    updatePage(pagination) {
      const { itemsPerPage: results, page } = pagination;
      this.pagination = pagination;
      this.getProducts({ page, results });
    },

    editItem(item) {
      this.editedIndex = this.products.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.products.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      try {
        this.products.data.splice(this.editedIndex, 1);
        await axios.post(`http://localhost:8000/api/product/delete`, {
          id: this.editedItem.id,
        });
      } catch {
        this.errorAlert = true;
      }
      this.closeDelete();

      this.sucessAlert = true;
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeAdd() {
      this.$refs.form.reset();
      this.dialogAdd = false;
    },

    async save() {
      if (this.editedIndex > -1) {
        try {
          Object.assign(this.products.data[this.editedIndex], this.editedItem);
          await axios.post(`http://localhost:8000/api/product/edit`, {
            id: this.editedItem.id,
            name: this.editedItem.name,
            price: this.editedItem.price,
            bar_code: this.editedItem.bar_code,
          });
          this.sucessAlert = true;
        } catch {
          this.errorAlert = true;
        }
      } else {
        this.products.data.push(this.editedItem);
      }

      this.close();
    },
  },
};
</script>

<style scoped>
</style>
