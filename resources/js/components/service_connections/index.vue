<template>
    <section class="content-header">
        <div class="container-fluid">
            <form class="row mb-2" @submit.prevent="getSearch()">
                <div class="col-md-6 offset-md-3">
                    <input type="text" v-model="search" @keyup="getSearch()" class="form-control" placeholder="Search Account # or Account Name" name="params" autofocus>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">Search <i class="fas fa-search ico-tab-left-mini"></i></button>
                </div>
            </form>
        </div>
    </section>

    <div class="content px-3 table-responsive">
        <table class="table table-hover table-borderless">
            <thead>
                <th>Service Account Name</th>
                <th>Turn On ID</th>
                <th>Application</th>
                <th>Status</th>
                <th><!-- OR Number --></th>
            </thead>
            <tbody>
                <tr v-for="item in results.data" :key="item.ConsumerId" style="cursor: pointer;" @click="goToApplication(item.ConsumerId)">
                    <td class="v-align">
                        <div style="display: inline-block; vertical-align: middle;">
                            <img src="../../../../public/imgs/prof-icon.png" style="width: 40px; margin-right: 15px;" class="img-circle" alt="profile">
                        </div>
                        
                        <div style="display: inline-block; height: inherit; vertical-align: middle;">
                            <strong style="font-size: 1.2em;">{{ item.ServiceAccountName }}</strong>
                            <br>
                            <p class="no-pads text-muted">{{ (isNull(item.Sitio) ? '' : (item.Sitio.toUpperCase() + ', ')) + (isNull(item.Barangay) ? '' : (item.Barangay.toUpperCase() + ', ')) + item.Town.toUpperCase() }}</p>
                        </div>
                    </td>
                    <td class="v-align">{{ item.ConsumerId }}</td>
                    <td class="v-align">
                        {{ item.AccountApplicationType }}
                        <br>
                        <span class="text-muted">Date Applied: {{ isNull(item.DateOfApplication) ? '-' : moment(item.DateOfApplication).format("MMM DD, YYYY") }}</span>
                    </td>
                    <td class="v-align">
                        {{ item.Status }} ({{ getProgressIncFromStatus(item.Status) }} %)
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar progress-bar-striped" :class="getProgressBgFromStatus(item.Status)" role="progressbar" :style="{width: getProgressIncFromStatus(item.Status) + '%'}" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="v-align text-right" 
                        v-html="isNull(item.ORNumber) ? '' : `<span class='badge bg-warning'>${item.ORNumber}</span>`"></td>
                </tr>
            </tbody>
        </table>

        <pagination :data="results" :limit="15" @pagination-change-page="getSearch"></pagination>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'AllApplications.all-applications',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            search : '',
            results : {},
            baseURL : window.location.origin + axios.defaults.baseURL,
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
        toMoney(value) {
            if (this.isNumber(value)) {
                return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
            } else {
                return '-'
            }
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        generateRandomString(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
        },
        generateUniqueId() {
            return moment().valueOf() + "-" + this.generateRandomString(32);
        },
        getSearch(page = 1) {
            axios.get(`${ this.baseURL }/service_connections/search`, {
                params : {
                    page : page,
                    params : this.search,
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error searching application'
                // })
            })
        },
        getProgressIncFromStatus(status) {
            if (this.isNull(status)) {
                return 1;
            } else {
                if (status === 'For Inspection' | status === 'Re-Inspection') {
                    return 14.285;
                } else if (status === 'Approved') {
                    return 28.57;
                } else if (status === 'Payment Approved') {
                    return 42.855;
                } else if (status === 'For Energization') {
                    return 57.14;
                } else if (status === 'Approved for Energization') {
                    return 71.425;
                } else if (status === 'Energized') {
                    return 85.71;
                } else if (status === 'Closed') {
                    return 100;
                } else {
                    return 1;
                }
            }
        },
        getProgressBgFromStatus(status) {
            if (this.isNull(status)) {
                return 'bg-warning';
            } else {
                if (status === 'For Inspection') {
                    return 'bg-primary';
                } else if (status === 'Re-Inspection') {
                    return 'bg-danger';
                } else if (status === 'Approved') {
                    return 'bg-primary';
                } else if (status === 'Payment Approved') {
                    return 'bg-primary';
                } else if (status === 'For Energization') {
                    return 'bg-info';
                } else if (status === 'Approved for Energization') {
                    return 'bg-info';
                } else if (status === 'Energized') {
                    return 'bg-info';
                } else if (status === 'Closed') {
                    return 'bg-success';
                } else {
                    return 'bg-warning';
                }
            }
        },
        goToApplication(id) {
            window.location.href = this.baseURL + '/serviceConnections/' + id
        }
    },
    created() {
        
    },
    mounted() {
        this.getSearch()
    }
}

</script>