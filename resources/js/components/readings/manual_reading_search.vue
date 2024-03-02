<template>
    <div class="row mb-2" style="margin-top: 10px;">
        <form class="col-md-6 offset-md-3" @submit.prevent="view">
            <div class="input-group">
                <input v-model="search" @keyup="view" type="text" class="form-control" placeholder="Search Account Number, Name, Meter Number" name="params" autofocus>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>
    </div>

    <!-- results -->
    <div class="row">
        <div class="col-lg-12 p-3">
            <table class="table table-hover table-sm">
                <thead>
                    <th>Account No.</th>
                    <th>Service Account Name</th>
                    <th>Barangay</th>
                    <th>Purok</th>
                    <th>Account Type</th>
                    <th>Status</th>
                    <th>Meter Number</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="account in accounts.data" :key="account.id">
                        <td><a :href="baseURL + '/serviceAccounts/' + account.id"><strong>{{ account.OldAccountNo }}</strong></a></td>
                        <td>{{ account.ServiceAccountName }}</td>
                        <td>{{ account.Barangay }}</td>
                        <td>{{ account.Purok }}</td>
                        <td>{{ account.AccountType }}</td>
                        <td>{{ account.AccountStatus }}</td>                        
                        <td>{{ account.MeterNumber }}</td>
                        <td class="text-right">
                            <a :href="baseURL + '/readings/manual-reading-console/' + account.id" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>Perform Reading</a>   <!-- IF PORT 80 -->                         
                            <!-- <a :href="'/readings/manual-reading-console/' + account.id" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>Perform Reading</a>  --> <!-- IF PORT 8000 -->
                            
                        </td>
                    </tr>
                </tbody>
            </table>

            <pagination :data="accounts" :limit="10" @pagination-change-page="view"></pagination>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'

export default {
    name : 'ReadingsManualReadingSearch',
    components : {
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            search : '',
            isEditMode : false,
            accounts : {},
            // baseURL : axios.defaults.baseURL,
            baseURL : window.location.origin + axios.defaults.baseURL
        }
    },
    methods : {
        view (page = 1) {
            // axios.get(`/service_accounts/search-account-ajax?page=${page}&search=${this.search}`) // IF PORT 8000
            axios.get(`${ this.baseURL }/service_accounts/search-account-ajax?page=${page}&search=${this.search}`) // IF PORT 80 DIRECT
            .then(response => {
                this.accounts = response.data
            })
            .catch(error => {
                console.log(error)
            })
        },
    },
    created() {
        this.view()
    },
    mounted() {
        // console.log('page mounted')
    }
}

</script>