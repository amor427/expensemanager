<template>
    <div class="container">
  

        <div class="row mt-5" >
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Expenses</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Expense Category</th>
                        <th>Amount</th>
                        <th>Entry Date</th>
                        <th>Created At</th>
                        
                  </tr>


                  <tr v-for="expense in expenses" :key="expense.id"  @click="editModal(expense)">

                    <td>{{expense.name}}</td>
                    <td>{{expense.amount}}</td>
                    <td>{{expense.entry_date | myDate}}</td>
                    <td>{{expense.created_at | myDate}}</td>

                  </tr>
                </tbody></table>
              </div>
              
            </div>
            <!-- /.card -->
          </div>
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
                <form @submit.prevent="editmode ? updateExpense() : createExpense()">
                <div class="modal-body">

                <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Expense Category</label>
                <div class="col-sm-8">
                   <select name="type" v-model="form.expense_category_id" id="expense_category_id" class="form-control" :class="{ 'is-invalid': form.errors.has('expense_category_id') }">
                            
                             <option v-for="cat in categories" :value="cat.id">
                             {{ cat.name }}
                             </option>
                        </select>
                        <has-error :form="form" field="id"></has-error>
                </div>
                </div>

                <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Amount</label>
                <div class="col-sm-8">
                   <input v-model="form.amount" type="text" name="amount"
                            placeholder="Amount"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                        <has-error :form="form" field="amount"></has-error>
                </div>
                </div>

                <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Entry Date</label>
                <div class="col-sm-8">
                    <div id="app">
    
                    <datepicker v-model="form.entry_date" name="entry_date"></datepicker>
                  </div>
                </div>
                </div>

                </div>
                <div class="modal-footer" >
                    <a href="#" @click="deleteExpense(form.id)"><button v-show="editmode" type="button" class="btn btn-danger float-left text-left" data-dismiss="modal">Delete</button></a>
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
                expenses : {},
                categories : {},
                form: new Form({
                    id:'',
                    expense_category_id : '',
                    entry_date: '',
                    amount: ''
                })
            }
        },
        methods: {
        
         
       
            updateExpense(){
                this.$Progress.start();
               
                this.form.put('api/expense/'+this.form.id)
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
            editModal(expense){
                this.loadcategories();
                
                   
                    this.editmode = true;
                      this.form.reset();
                    $('#addNew').modal('show');
                    this.form.fill(expense);
              
                     //this.editmode = false;
               
                
              
            },
            newModal(){

                this.loadcategories();
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteExpense(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                       
                         if (result.value) {
                                axios.delete('api/expense/'+id).then(()=>{
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
            loadExpense(){
                
                    axios.get("api/expense").then(({ data }) => (this.expenses = data));
               
            },
            loadcategories(){
                
                    axios.get("api/expense_category ").then(({ data }) => (this.categories = data));
                
            },

            createExpense(){

                this.$Progress.start();

                this.form.post('api/expense')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast({
                        type: 'success',
                        title: 'Expense Created in successfully'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            }
        },
        created() {
          
           
           this.loadExpense();
           Fire.$on('AfterCreate',() => {
               this.loadExpense();

             
           });
       


        }


    }

</script>

    