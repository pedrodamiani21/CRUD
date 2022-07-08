<template >
  <v-card class="pa-8 align-center">
    <v-card-title class="align-center">Orders</v-card-title>
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
          @click:append="getOrders()"
          @keyup.enter="getOrders()"
          color="secondary"
        ></v-text-field>
      </v-col>

      <v-col>
        <v-btn color="secondary" dark @click="openOrderModal()">
          <span>New Order</span>
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <br />

    <v-spacer></v-spacer>
    <v-data-table
      :headers="headers"
      class="elevation-1"
      :items="orders.data"
      :loading="loading"
      :options.sync="options"
      :server-items-length="orders.total"
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
          <span class="text-h8">Order ID: {{ editedItem.id }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.customer_id"
                  label="Customer ID"
                  type="text"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Customer ID is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.product_id"
                  label="Product ID"
                  type="text"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Product ID is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.order_date"
                  label="Date"
                  type="text"
                  outlined
                  required
                  :rules="[(v) => !!v || 'Date is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.order_amount"
                  label="Amount"
                  :rules="[(v) => !!v || 'Date is required']"
                  outlined
                  required
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.status"
                  label="Status"
                  outlined
                  required
                  :rules="[(v) => !!v || 'Status is required']"
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
          >Are you sure you want to delete this Order
          {{ editedItem.id }}?</v-card-title
        >

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.customer_id"
                  label="Customer ID"
                  type="text"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Customer ID is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.product_id"
                  label="Product ID"
                  type="text"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Product ID is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.order_date"
                  label="Date"
                  type="text"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Date is required']"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.order_amount"
                  label="Amount"
                  :rules="[(v) => !!v || 'Date is required']"
                  outlined
                  required
                  readonly
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.status"
                  label="Status"
                  outlined
                  required
                  readonly
                  :rules="[(v) => !!v || 'Status is required']"
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
          <v-card-title class="text-h5">Add New Order</v-card-title>

          <v-card-text>
            <v-container>
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="newOrder.customer_id"
                      label="Customer ID"
                      type="text"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Customer ID is required']"
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      v-model="newOrder.product_id"
                      label="Product ID"
                      type="text"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Product ID is required']"
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      v-model="newOrder.order_date"
                      label="Date"
                      type="date"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Date is required']"
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      v-model="newOrder.order_amount"
                      label="Amount"
                      :rules="[(v) => !!v || 'Date is required']"
                      outlined
                      required
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      v-model="newOrder.status"
                      label="Status"
                      outlined
                      required
                      :rules="[(v) => !!v || 'Status is required']"
                      color="secondary"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="closeAdd">Cancel</v-btn>
                  <v-btn color="secondary" text @click="addOrder()">OK</v-btn>
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
  name: "Orders",
  data() {
    return {
      headers: [
        { text: "ID", value: "id" },
        { text: "Customer ID", value: "customer_id" },
        { text: "Product ID", value: "product_id" },
        { text: "Order Date", value: "order_date" },
        { text: "Amount", value: "order_amount" },
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
      orders: [],
      editedIndex: -1,
      editedItem: {
        customer_id: "",
        product_id: "",
        order_date: "",
        order_amount: "",
        status: '"',
      },
      newOrder: {
        customer_id: "",
        product_id: "",
        order_date: "",
        order_amount: "",
        status: "",
      },
    };
  },

  created() {
    this.getOrders();
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
    async getOrders() {
      Object.assign(this.search);
      this.loading = true;
      try {
        let orders = await axios.get(
          `http://localhost:8000/api/orders?page=${this.options.page}&q=${this.search}`
        );
        this.orders = orders.data[0];
        this.loading = false;
      } catch (error) {
        console.log(error);
        this.loading = false;
      }
    },

    openOrderModal() {
      this.dialogAdd = true;
    },

    async addOrder() {
      if (this.$refs.form.validate()) {
        try {
          await axios.post(`http://localhost:8000/api/order/create`, {
            customer_id: this.newOrder.customer_id,
            product_id: this.newOrder.product_id,
            order_date: this.newOrder.order_date,
            order_amount: this.newOrder.order_amount,
            status: this.newOrder.status,
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
      this.getOrders({ page, results });
    },

    editItem(item) {
      this.editedIndex = this.orders.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.orders.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      this.orders.data.splice(this.editedIndex, 1);
      try {
        await axios.post(`http://localhost:8000/api/order/delete`, {
          id: this.editedItem.id,
        });
        this.sucessAlert = true;
      } catch {
        this.errorAlert = true;
      }

      this.closeDelete();
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
        Object.assign(this.orders.data[this.editedIndex], this.editedItem);
        try {
          await axios.post(`http://localhost:8000/api/order/edit`, {
            id: this.editedItem.id,
            customer_id: this.editedItem.customer_id,
            product_id: this.editedItem.product_id,
            order_date: this.editedItem.order_date,
            order_amount: this.editedItem.order_amount,
            status: this.editedItem.status,
          });
           this.sucessAlert = true;
        } catch {
          this.errorAlert = true;
        }
      } else {
        this.orders.data.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>

<style scoped>
</style>
