<template>
  <div class="container">
    <!-- USER AUTHENTICATION -->
    <div class="row mt-5" v-if="$gate.isAdminOrDev()">
      <!-- USER AUTHENTICATION END -->
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users List</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New
                <i class="fas fa-user-plus fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Bio</th>
                  <th>Registered At</th>
                </tr>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | capitalize }}</td>
                  <td>{{ user.bio }}</td>
                  <td>{{ user.created_at | normalDate }}</td>
                  <td>
                    <a href="#" @click="editModal(user)">
                      <i class="fas fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(user.id)">
                      <i class="fas fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="users" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrDev()">
      <not-found></not-found>
    </div>

    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New User</h5>
            <h5 v-show="editMode" class="modal-title" id="addNewLabel">Edit User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateUser() : createUser()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  placeholder="Name"
                  type="text"
                  id="name"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid':form.errors.has('name')}"
                >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.email"
                  placeholder="Email"
                  type="email"
                  id="email"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid':form.errors.has('email')}"
                >
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <textarea
                  v-model="form.bio"
                  placeholder="Short Bio for User"
                  type="text"
                  id="bio"
                  name="bio"
                  class="form-control"
                  :class="{ 'is-invalid':form.errors.has('bio')}"
                ></textarea>
                <has-error :form="form" field="bio"></has-error>
              </div>
              <div class="form-group">
                <select
                  v-model="form.type"
                  placeholder="Type of User"
                  name="type"
                  id="type"
                  class="form-control"
                  :class="{ 'is-invalid':form.errors.has('type')}"
                >
                  <option value>Select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="dev">Desarrollador</option>
                  <option value="employee">Empleado</option>
                  <option value="delivery">Picker</option>
                  <option value="user">Usuario</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.password"
                  placeholder="Password"
                  type="password"
                  id="password"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid':form.errors.has('password')}"
                >
                <has-error :form="form" field="password"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editMode: true,
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/user?page=" + page).then(response => {
        this.users = response.data;
      });
    },
    updateUser(id) {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          Fire.$emit("Refresh");
          Toast.fire({
            type: "success",
            title: "El Usuario fue Editado Exitosamente!"
          });
          $("#addNew").modal("hide");
        })
        .catch(() => {
          this.$Progress.finish();
          Toast.fire({
            type: "error",
            title: "Hubo un Error al Editar el Usuario!"
          });
        });
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    editModal(user) {
      this.editMode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("Refresh");
          $("#addNew").modal("hide"); //Cierra del Modal de Bootstrap
          Toast.fire({
            type: "success",
            title: "El Usuario fue Creador Exitosamente!"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          Toast.fire({
            type: "error",
            title: "Hubo un Error al Crear el Usuario!"
          });
        });
    },
    deleteUser(id) {
      Swal.fire({
        title: "Estas Seguro que Quieres Eliminar este Usuario?",
        text: "No hay vuelta atras si lo haces!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminalo!"
      }).then(result => {
        //Ajax Request
        if (result.value) {
          this.form
            .delete("api/user/" + id)
            .then(() => {
              Swal.fire("Listo!", "El Usuario ha sido Eliminado", "success");

              Fire.$emit("Refresh");
            })
            .catch(() => {
              Swal.fire(
                "Error!",
                "Algo salio mal al intentar eliminar el usuario",
                "warning"
              );
            });
        }
      });
    },
    loadUsers() {
      if (this.$gate.isAdminOrDev()) {
        axios.get("api/user").then(({ data }) => {
          this.users = data;
        });
      }
    }
  },
  mounted() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("/api/findUser?q=" + query)
        .then(data => {
          this.users = data.data;
        })
        .catch(() => {});
    });
    this.loadUsers();
    Fire.$on("Refresh", () => {
      this.loadUsers();
    });
  }
};
</script>