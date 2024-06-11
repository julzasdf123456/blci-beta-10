<template>
    <div class="row px-2">
        <div class="col-lg-12">
            <h4>All Applied Requests</h4>
        </div>

        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-list ico-tab"></i>All Applied Requests</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <thead>
                            <th class="v-align">ID</th>
                            <th class="v-align">Customer/Address</th>
                            <th class="v-align">Application For</th>
                            <th class="v-align">Status Override</th>
                            <th class="v-align">Notes/Remarks</th>
                            <th class="v-align"></th>
                        </thead>
                        <tbody>
                            <tr v-for="req in requests" :key="req.id">
                                <td class="v-align">
                                    <strong>{{ req.id }}</strong>
                                    <br>
                                    <span class="text-muted">Date Applied: {{ moment(req.DateApplied).format("MMM DD, YYYY") }}</span>
                                </td>
                                <td class="v-align">
                                    <strong>{{ req.ServiceAccountName }}</strong>
                                    <br>
                                    <span class="text-muted">{{ req.BarangayFull + ", " + req.TownFull }}</span>
                                </td>
                                <td class="v-align">
                                    {{ req.AccountApplicationType }}
                                    <br>
                                    <span class="badge bg-success">{{ req.Status }}</span>
                                </td>
                                <td class="v-align">
                                    <select class="form-control" v-model="req.Status">
                                        <option value="Paid">Paid</option>
                                        <option value="Forwarded to Accounting">Forwarded to Accounting</option>
                                        <option value="Forwarded to CS Supervisor">Forwarded to CS Supervisor</option>
                                        <option value="Energized">Done</option>
                                    </select>
                                </td>
                                <td class="v-align">
                                    <input v-model="req.Notes" type="text" class="form-control" placeholder="Input your remarks here">
                                </td>
                                <td class="v-align text-right">
                                    <button @click="save(req.id, req.Status, req.Notes)" class="btn btn-sm btn-success">Save <i class="fas fa-check ico-tab-left-mini"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'Overtime.overtime',
    components : {
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            baseURL : window.location.origin + axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            imgsPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            requests : [],
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
        getRequests() {
            axios.get(`${ this.baseURL }/service_connections/get-applied-requests`)
            .then(response => {
                this.requests = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting requests!\n' + error.response.data
                })
            })
        },
        save(id, status, notes) {
            axios.get(`${ this.baseURL }/service_connections/update-status`, {
                params : {
                    id : id,
                    Status : status,
                    Notes : notes,
                }
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Status updated!'
                })

                // remove done
                this.requests = this.requests.filter(obj => obj.Status !== 'Energized')
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error updating status!\n' + error.response.data
                })
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getRequests()
    }
}

</script>