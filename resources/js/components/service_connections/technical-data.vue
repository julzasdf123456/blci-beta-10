<template>
    <div class="row mb-2" style="margin-top: 10px;">
        <!-- params -->
        <div class="col-lg-12">
            <div class="card shadow-none" style="margin-top: 10px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <span class="text-muted">From</span>
                            <flat-pickr v-model="from" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                        </div>
                        <div class="col-lg-2">
                            <span class="text-muted">To</span>
                            <flat-pickr v-model="to" :config="pickerOptions" class="form-control form-control-sm"></flat-pickr>
                        </div>
                        <div class="col-lg-2">
                            <span class="text-muted">Options</span>
                            <select class="form-control form-control-sm" v-model="options">
                                <option value="All Applications">All Applications</option>
                                <option value="Energized Only">Energized/Executed Only</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <span class="text-muted">Actions</span><br>
                            <button class="btn btn-default btn-sm ico-tab-mini" @click="viewResults()"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button class="btn btn-primary btn-sm" @click="download()"><i class="fas fa-file-excel ico-tab-mini"></i>Download Excel File</button>

                            <div class="spinner-border text-success float-right" v-if="isDisplayed" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- rsults -->
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <th>Account No</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Application</th>
                        <th>Account Type</th>
                        <th>Zone/Block</th>
                        <th>Building<br>Lat/Long</th>
                        <th>Metering<br>Lat/Long</th>
                        <th>Tapping Pole<br>Lat/Long</th>
                        <th>Service Entrance<br>Lat/Long</th>
                        <th>Pole Number</th>
                        <th>Transformer Number</th>
                    </thead>
                    <tbody>
                        <tr v-for="res in results.data">
                            <td>{{ res.AccountNumber }}</td>
                            <td>{{ res.ServiceAccountName }}</td>
                            <td>{{ res.Sitio + ', ' + res.BarangaySpelled + ', ' + res.TownSpelled }}</td>
                            <td>{{ res.AccountApplicationType }}</td>
                            <td>{{ res.AccountType }}</td>
                            <td>{{ res.Zone + ' - ' + res.Block }}</td>
                            <td>{{ res.GeoBuilding }}</td>
                            <td>{{ res.GeoMeteringPole }}</td>
                            <td>{{ res.GeoTappingPole }}</td>
                            <td>{{ res.GeoSEPole }}</td>
                            <td>{{ res.PoleNo }}</td>
                            <td>{{ res.TransformerNo }}</td>
                        </tr>
                    </tbody>
                </table>

                <pagination :data="results" :limit="50" @pagination-change-page="viewResults"></pagination>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
    name : 'TechnicalData.technical-data',
    components : {
        'pagination' : Bootstrap4Pagination,
        FlatPickr,
    },
    data() {
        return {
            search : '',
            isEditMode : false,
            accounts : {},
            // baseURL : axios.defaults.baseURL,
            baseURL : window.location.origin + axios.defaults.baseURL,
            pickerOptions: {
                enableTime: false,
                dateFormat: 'Y-m-d',
            },
            from : '',
            to : '',
            isDisplayed : false,
            options : 'All Applications',
            results : {}
        }
    },
    methods : {
        viewResults(page = 1) {
            axios.get(`${ this.baseURL }/service_connections/get-technical-data-report`, {
                params : {
                    From : this.from,
                    To : this.to,
                    Options : this.options,
                    page : page
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting data!',
                });
                console.log(error.response)
            });
        },
        download() {
            window.location.href = this.baseURL + '/service_connections/download-technical-data/' + this.options + '/' + this.from + '/' + this.to
        }
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>