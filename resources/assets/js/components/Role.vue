<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Roles</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Display Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        
                  </tr>


                  <tr v-for="role in roles" :key="role.id"  @click="editModal(role)">

                    <td>{{role.id}}</td>
                    <td>{{role.type}}</td>
                    <td>{{role.description}}</td>
                    <td>{{role.created_at | myDate}}</td>

                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateRole() : createRole()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.type" type="text" name="type"
                            placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                        <has-error :form="form" field="type"></has-error>
                    </div>

                     <div class="form-group">
                        <input v-model="form.description" type="text" name="description"
                            placeholder=""
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                        <has-error :form="form" field="description"></has-error>
                    </div>

                     


                

                </div>
                <div class="modal-footer" >
                    <a href="#" @click="deleteRole(form.id)"><button v-show="editmode" type="button" class="btn btn-danger float-left text-left" data-dismiss="modal">Delete</button></a>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                   
                    <button v-show="editmode"  type="submit" class="btn btn-success" >Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Save</button>
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
                editmode: false,
                users : {},
                roles : {},
                form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    password: '',
                    type: '',
                    bio: ''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/user?page=' + page)
                            .then(response => {
                                this.users = response.data;
                            });
                },
            updateRole(){
                this.$Progress.start();
               
                this.form.put('api/user_role/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(user){
               
                if(user.type !="admin"){
                   
                    this.editmode = true;
                      this.form.reset();
                    $('#addNew').modal('show');
                    this.form.fill(user);
                }
                else{
                     this.editmode = false;
                }
                
              
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteRole(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                axios.delete('api/user_role/'+id).then(()=>{
                                        swal(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },
           
            loadroles(){
                if(this.$gate.isAdminOrAuthor()){
                    axios.get("api/user_role").then(({ data }) => (this.roles = data));
                }
            },

            createRole(){
                this.$Progress.start();

                this.form.post('api/user_role')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'User Role Created in successfully'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        },
        created() {
           
           
           this.loadroles();
           Fire.$on('AfterCreate',() => {
               this.loadroles();

           });
       


        }

    }
</script>
