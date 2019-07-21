<template>
    <div class="container">
  

        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Expense Categories</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Display Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        
                  </tr>


                  <tr v-for="cat in categories" :key="cat.id"  @click="editModal(cat)">

                    <td>{{cat.name}}</td>
                    <td>{{cat.description}}</td>
                    <td>{{cat.created_at | myDate}}</td>

                  </tr>
                </tbody></table>
              </div>
              
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
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Category</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                <div class="modal-body">

                <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Display Name</label>
                <div class="col-sm-8">
                   <input v-model="form.name" type="text" name="name"
                            placeholder=""
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                </div>
                </div>

                <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Description</label>
                <div class="col-sm-8">
                   <input v-model="form.description" type="text" name="description"
                            placeholder=""
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                        <has-error :form="form" field="description"></has-error>
                </div>
                </div>

               

                </div>
                <div class="modal-footer" >
                    <a href="#" @click="deleteCategory(form.id)"><button v-show="editmode" type="button" class="btn btn-danger float-left text-left" data-dismiss="modal">Delete</button></a>
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

import Datepicker from 'vuejs-datepicker';  
    export default {
        name: 'app',
          components: {
            Datepicker
          },    
        data() {
            return {
                editmode: false,
                
                categories : {},
                form: new Form({
                    id:'',
                    name:'',
                    created_at: '',
                    description: ''
                })
            }
        },
        methods: {
        
         
       
            updateCategory(){
                this.$Progress.start();
               
                this.form.put('api/expense_category/'+this.form.id)
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
            editModal(cat){
                this.loadcategories();
                
                   
                    this.editmode = true;
                      this.form.reset();
                    $('#addNew').modal('show');
                    this.form.fill(cat);
              
                     //this.editmode = false;
               
                
              
            },
            newModal(){

                this.loadcategories();
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteCategory(id){
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
                                axios.delete('api/expense_category/'+id).then(()=>{
                                        swal(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            loadExpenseCategories(){
                if(this.$gate.isAdminOrAuthor()){
                    axios.get("api/expense_category").then(({ data }) => (this.categories = data));
                }
            },
            loadcategories(){
                if(this.$gate.isAdminOrAuthor()){
                    axios.get("api/expense_category ").then(({ data }) => (this.categories = data));
                }
            },

            createCategory(){

                this.$Progress.start();

                this.form.post('api/expense_category')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'Category Created successfully'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        },
        created() {
             
           
           this.loadExpenseCategories();
           Fire.$on('AfterCreate',() => {
               this.loadExpenseCategories();

               console.log(this.loadExpenseCategories());
           });
       


        }


    }

</script>

    