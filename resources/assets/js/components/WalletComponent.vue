<template>
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">John Wallet Details</div>

                    <div class="card-body" v-if="response">

                        <div class="row">
                                <div class="col-md-6">
                                        <h4>Email : {{ response.email }}</h4>
                                </div>
                                <!--col-md-6-->
                                <div class="col-md-6">
                                     <h4 style="text-align:right;">Balance Amount : {{ response.balance }}</h4>
                                </div>
                                <!--col-md-6-->
                        </div>
                        <!--row-->
                        <br>
                        <h4 class="text-center">Recent Transactions</h4>

                        <div class="row">
                                <div class="table-responsive"> 
                                    <table class="table table-condensed table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Trans Time</th>
                                                        <th>Trans Id</th>
                                                        <th>Type</th>
                                                        <th>Amount</th>
                                                        <th>Currency</th>
                                                        <th>Sender</th>
                                                        <th>Receiver</th>
                                                        <th>Description</th>                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="transaction in response.transactions">
                                                        <td>{{ transaction.created_at }}</td>
                                                        <td>{{ transaction.trans_id }}</td>
                                                        <td>{{ transaction.type }}</td>
                                                        <td>{{ transaction.amount }}</td>
                                                        <td>{{ transaction.currency }}</td>
                                                        <td>{{ transaction.sender }}</td>
                                                        <td>{{ transaction.receiver }}</td>
                                                        <td>{{ transaction.description }}</td>
                                                    </tr>
                                                </tbody>
                                    </table>
                                </div> 
                        </div>


                    </div>
                </div>
            </div>
</template>

<script>
    export default {
        data () {
                    return {
                      response: null
                    }
                  },
      mounted () {
        axios
          .get('/api/user')
          .then(response => (this.response = response.data.response[0]))
      }
    }
</script>