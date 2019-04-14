<template>
  <img :src="getProfilePic()" class="img-circle elevation-2" alt="User Image">
</template>

<script>
export default {
  data() {
    return {
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
  mounted() {
    console.log("Component Mounted.");
  },
  methods: {
    getProfilePic() {
      let prefix = this.form.photo.match(/\//) ? "" : "/img/profile/";
      return prefix + this.form.photo;
    }
  },
  created() {
    axios.get("api/profile").then(({ data }) => this.form.fill(data));
  }
};
</script>
