<template >
  <v-card class="pa-8 align-center">
    <v-card-title class="align-center">Customers</v-card-title>
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
          @click:append="getCustomers()"
          @keyup.enter="getCustomers()"
          color="secondary"
        ></v-text-field>
      </v-col>

      <v-col>
        <v-btn color="secondary" dark @click="openCustomerModal()">
          <span>New Customer</span>
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <br />

    <v-spacer></v-spacer>
    <v-data-table
      :headers="headers"
      class="elevation-1"
      :items="customers.data"
      :loading="loading"
      :options.sync="options"
      :server-items-length="customers.total"
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
          <span class="text-h8">Customer ID: {{ editedItem.id }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedItem.name"
                  label="Name"
                  outlined
                  :rules="nameRules"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.cpf"
                  label="CPF"
                  outlined
                  maxlength="11"
                  :rules="cpfRules"
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.email"
                  label="Email"
                  outlined
                  :rules="emailRules"
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
          >Are you sure you want to delete this Customer
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
                  v-model="editedItem.cpf"
                  label="CPF"
                  outlined
                  readonly
                  color="secondary"
                ></v-text-field>
                <v-text-field
                  v-model="editedItem.email"
                  label="Email"
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
          <v-card-title class="text-h5">Add New Customer</v-card-title>

          <v-card-text>
            <v-container>
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="newCustomer.name"
                      label="Name"
                      type="text"
                      outlined
                      required
                      :rules="nameRules"
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      maxlength="11"
                      v-model="newCustomer.cpf"
                      label="CPF"
                      outlined
                      required
                      :rules="cpfRules"
                      color="secondary"
                    ></v-text-field>
                    <v-text-field
                      v-model="newCustomer.email"
                      label="Email"
                      outlined
                      required
                      :rules="emailRules"
                      color="secondary"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="secondary" text @click="closeAdd">Cancel</v-btn>
                  <v-btn color="secondary" text @click="addCustomer()"
                    >OK</v-btn
                  >
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
  name: "Customers",
  data() {
    return {
      headers: [
        { text: "ID", value: "id" },
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "CPF", value: "cpf" },
        { text: "Options", value: "actions", sortable: false },
      ],
      errorAlert: false,
      sucessAlert: false,
      search: [],
      cpfRules: [
        (v) => !!v || "CPF is required",
        (v) => (v && v.length == 11) || "This field must have 11 characters",
        (v) => (v.length > 0 && /^[0-9]+$/.test(v)) || "Numbers only",
      ],
      emailRules: [
        (v) => !!v || "Email is required",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ],
      nameRules: [(v) => !!v || "Name is required"],
      options: {},
      dialog: false,
      valid: false,
      dialogDelete: false,
      dialogAdd: false,
      loading: false,
      customers: [],
      editedIndex: -1,
      editedItem: {
        name: "",
        email: "",
        cpf: "",
      },
      newCustomer: {
        name: "",
        email: "",
        cpf: "",
      },
    };
  },

  created() {
    this.getCustomers();
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
    async getCustomers() {
      Object.assign(this.search);
      this.loading = true;
      try {
        let customers = await axios.get(
          `http://localhost:8000/api/customers?page=${this.options.page}&q=${this.search}`
        );
        this.customers = customers.data[0];
        this.loading = false;
      } catch (error) {
        console.log(error);
        this.loading = false;
      }
    },

    openCustomerModal() {
      this.dialogAdd = true;
    },

    async addCustomer() {
      if (this.$refs.form.validate()) {
        try {
          await axios.post(`http://localhost:8000/api/customer/create`, {
            name: this.newCustomer.name,
            email: this.newCustomer.email,
            cpf: this.newCustomer.cpf,
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
      this.getCustomers({ page, results });
    },

    editItem(item) {
      this.editedIndex = this.customers.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.customers.data.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      this.customers.data.splice(this.editedIndex, 1);
      try {
        await axios.post(`http://localhost:8000/api/customer/delete`, {
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
        Object.assign(this.customers.data[this.editedIndex], this.editedItem);
        try {
          await axios.post(`http://localhost:8000/api/customer/edit`, {
            id: this.editedItem.id,
            email: this.editedItem.email,
            name: this.editedItem.name,
          });
          this.sucessAlert = true;
        } catch {
          this.errorAlert = true;
        }
      } else {
        this.customers.data.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>

<style scoped>
</style>
