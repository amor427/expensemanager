<template>

  <div class="">
    <div class="">
      <h2 class="card-title">My Expenses</h2>
    </div>

    <div class="row">
      <div class="col-sm-4">
     <table class="table">
  <thead>
    <tr>
      <th scope="col">Expense Categories</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="t in table">
     
      <td>{{t.name}}</td>
      <td>{{t.total_amount}}</td>
    </tr>
   
  </tbody>
</table>
    </div>
    <div class="col-sm-8 ">
        
        <div class="card-img-bottom">
          <chartjs-doughnut
            :bind="true"
            :datasets="datasets"
            :labels="labels"
            :option="option"
          />
        </div>
    </div>

    </div>

    
  </div>
</template>

<script>
export default {
  data() {
    return {
      table:{},
      datasets: [
        {
          data:{},
          backgroundColor: ["#f36e60", "#ffdb3b", "#185190","#37d1d3","#192022","#e49b2f","#d04538","#8d1673"],
          
        }
      ],
      labels:[],
      option: {}
    };
  },
   methods: {
     loadData(){
                
                    axios.get("api/dashboard")
                    .then(response => {
                    this.table = response.data[0];
                     this.datasets[0].data = response.data.datasets;
                     this.labels = response.data.label;

                      console.log(response.data[0]);

                                                                                                                                            
                      })
                
            },
   },
    created() {
           
           this.loadData();
            


        }
};
</script>