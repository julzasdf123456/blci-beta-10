<template>
    <div class="row mb-2" style="margin-top: 10px;">
        <form class="col-md-6 offset-md-3" @submit.prevent="view">
            <div class="input-group">
                <input v-model="search" type="text" @keyup="view()"  class="form-control" placeholder="Search Account Number, Name, Meter Number" name="params">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>   
        </form>
        <div class="col-md-3">
            <a :href="baseURL + '/tickets/create-new/0'" class="btn btn-sm btn-primary float-right">Create Walkin <i class="fas fa-fast-forward"></i></a>
        </div>
    </div>

    <!-- results -->
    <div class="row">
        <div class="col-lg-12 p-3 table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <th>Account No.</th>
                    <th>Service Account Name</th>
                    <th>Address</th>
                    <th>Account Type</th>
                    <th>Status</th>
                    <th>Meter Number</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="account in accounts.data" :key="account.id">
                        <td><strong>{{ account.OldAccountNo }}</strong></td>
                        <td>{{ account.ServiceAccountName }}</td>
                        <td>{{ isNull(account.Purok) ? '' : (account.Purok + ", ") }} {{ (isNull(account.Barangay) ? '' : (account.Barangay + ", ")) + account.Town }}</td>
                        <td>{{ account.AccountType }}</td>
                        <td>{{ account.AccountStatus }}</td>                        
                        <td>{{ account.MeterNumber }}</td>
                        <td class="text-right">
                            <a :href="baseURL + '/tickets/create-new/' + account.id" class="btn btn-success btn-xs">Create Ticket <i class="fas fa-arrow-right"></i></a><!--     IF PORT 80     -->                    
                            <!-- <a :href="'/serviceAccounts/' + account.id" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>View</a>  --> <!-- IF PORT 8000 -->
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
import jquery from 'jquery';
import moment from 'moment';

export default {
    name : 'CreateSelect.create-select',
    components : {
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            search : '',
            isEditMode : false,
            accounts : {},
            baseURL : window.location.origin + axios.defaults.baseURL,
            moment : moment,
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        view (page = 1) {
            // axios.get(`/service_accounts/search-account-ajax?page=${page}&search=${this.search}`) // IF PORT 8000
            axios.get(`${ this.baseURL }/tickets/search-account?page=${page}&params=${this.search}`) // IF PORT 80 DIRECT FROM APACHE
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
        console.log('page mounted')
    }
}

</script>