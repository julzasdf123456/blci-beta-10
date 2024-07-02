<template>
    <div class="row">
        <!-- CONFIG PARAMS -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <!-- billing month -->
                        <div class="form-group col-lg-2 col-md-4">
                            <span class="text-muted">Billing Month</span>
                            <select class="form-control form-control-sm" v-model="billingMonth">
                                <option v-for="period in billingMonths" :key="period.ServicePeriod" :value="period.ServicePeriod">{{ moment(period.ServicePeriod).format("MMMM YYYY") }}</option>
                            </select>
                        </div>

                        <!-- meter reader -->
                        <div class="form-group col-lg-2 col-md-4">
                            <span class="text-muted">Meter Reader</span>
                            <select class="form-control form-control-sm" v-model="meterReader">
                                <option v-for="mreader in meterReaders" :key="mreader.id" :value="mreader.id">{{ mreader.name }}</option>
                            </select>
                        </div>
                        
                        <!-- reading day -->
                        <div class="form-group col-lg-1 col-md-2">
                            <span class="text-muted">Reading Day</span>
                            <select class="form-control form-control-sm" v-model="day">
                                <option value="01">Day 1</option>
                                <option value="02">Day 2</option>
                                <option value="03">Day 3</option>
                                <option value="04">Day 4</option>
                                <option value="05">Day 5</option>
                                <option value="06">Day 6</option>
                                <option value="07">Day 7</option>
                                <option value="08">Day 8</option>
                                <option value="09">Day 9</option>
                                <option value="10">Day 10</option>
                                <option value="11">Day 11</option>
                                <option value="12">Day 12</option>
                                <option value="13">Day 13</option>
                                <option value="14">Day 14</option>
                                <option value="15">Day 15</option>
                                <option value="16">Day 16</option>
                                <option value="17">Day 17</option>
                                <option value="18">Day 18</option>
                                <option value="19">Day 19</option>
                                <option value="20">Day 20</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <span class="text-muted">Action</span>
                            <br>
                            <button class="btn btn-default btn-sm" @click="getReportData"><i class="fas fa-filter ico-tab-mini"></i>Filter</button>
                        </div>

                        <div class="col-lg-4">
                            <a :href="baseURL + '/readings/reading-monitor'" class="float-right btn btn-sm">Old Reading Monitor <i class="fas fa-share ico-tab-left-mini"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTS -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link custom-tab active" href="#report" data-toggle="tab">
                            <i class="fas fa-info-circle"></i>
                            Reading Report</a></li>
                        <li class="nav-item"><a class="nav-link custom-tab" href="#map" data-toggle="tab">
                            <i class="fas fa-map-marker-alt"></i>
                            Geo-Map</a></li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <!-- report -->
                        <div class="tab-pane active" id="report">
                            <!-- zone tabs -->
                            <div class="p-2">
                                <!-- tab headers -->
                                <ul class="nav nav-pills px-2 py-2">
                                    <li class="nav-item" v-for="(zone, index) in zones">
                                        <a :href="'#Z' + zone.Zone + '' + zone.BlockCode" class="nav-link custom-tab" :class="index==0 ? 'active' : null" data-toggle="tab">{{ 'Z' + zone.Zone + '-B' + zone.BlockCode }}</a>
                                    </li>
                                </ul>

                                <!-- tab body -->
                                <div class="tab-content">
                                    <div class="tab-pane" v-for="(zone, indexInner) in zones" :id="'Z' + zone.Zone + '' + zone.BlockCode" :class="indexInner==0 ? 'active' : null">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm table-bordered">
                                                <thead>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center text-primary">Account #</th>
                                                    <th class="text-center">House #</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Acct.<br>Status</th>
                                                    <th class="text-center">Meter #</th>
                                                    <th class="text-center">Timestamp</th>
                                                    <th class="text-center">Prev</th>
                                                    <th class="text-center">Pres</th>
                                                    <th class="text-center">Consumption</th>
                                                    <th class="text-center">Amount</th>
                                                    <!-- <th class="text-center text-info">Previous <br>Kwh Used</th> -->
                                                    <!-- <th class="text-center">% <span class="text-danger">Inc</span>/<span class="text-success">Dec</span></th> -->
                                                    <!-- <th class="text-center">Daily <br>Average</th> -->
                                                    <!-- <th class="text-center"># of Days</th> -->
                                                    <!-- <th class="text-center">Reading <br>Code</th> -->
                                                    <th class="text-center">Reading<br>Remarks</th>
                                                    <th class="text-center">System<br>Remarks</th>
                                                    <th class="text-center"></th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(data, i) in zone.ReadingData" :key="data.id">
                                                        <td>{{ i+1 }}</td>
                                                        <td>
                                                            <a target="_blank" :href="baseURL + '/serviceAccounts/' + data.AccountId">{{ data.OldAccountNo }}</a>
                                                        </td>
                                                        <td>{{ data.HouseNumber }}</td>
                                                        <td>{{ data.ServiceAccountName }}</td>
                                                        <td>{{ data.AccountStatus }}</td>
                                                        <td>{{ data.MeterNumber }}</td>
                                                        <td>{{ moment(data.ReadingTimestamp).format('MM/DD/YYYY hh:mm A') }}</td>
                                                        <td class="text-right">{{ data.PreviousReading }}</td>
                                                        <td class="text-right">{{ data.KwhUsed }}</td>
                                                        <td class="text-right"><strong>{{ data.KwhConsumed }}</strong></td>
                                                        <td class="text-right">{{ toMoney(parseFloat(data.Balance)) }}</td>
                                                        <!-- <td>{{ data.ReadingErrorCode }}</td> -->
                                                        <td>{{ data.ReadingErrorRemarks }}</td>
                                                        <td>{{ data.FieldStatus }}</td>
                                                        <td class="text-right">
                                                            <a class="ico-tab" title="Adjust Reading/Bill" target="_blank" :href="baseURL + '/bills/adjust-bill/' + data.BillId"><i class="fas fa-pen"></i></a>
                                                            <a title="Print Bill" target="_blank" :href="baseURL + '/bills/print-single-bill-new-format/' + data.BillId"><i class="fas fa-print"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- map -->
                        <div class="tab-pane" id="map">
                            <div style="position: relative; display: block; width: 100%; height: 60vh;">
                                <div style="position: absolute; top: 0; bottom: 0; width: 100%; height: 60vh;" ref="mapContainer" class="map-container"></div>
                            </div>
                        </div>
                    </div>
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

import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';

export default {
    name : 'FullReport.readings-full-report',
    components : {
        Swal,
        'pagination' : Bootstrap4Pagination
    },
    data() {
        return {
            moment : moment,
            baseURL : window.location.origin + axios.defaults.baseURL,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            billingMonth : '',
            billingMonths : [],
            meterReader : '',
            meterReaders : [],
            day : '01',
            zones : [],
            map : null,
            mapStyle : '',
            mapContainerClass : 'col-lg-12'
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
        getBillingMonths() {
            axios.get(`${ this.baseURL }/readings/get-billing-months`)
            .then(response => {
                this.billingMonths = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting billing months!',
                });
                console.log(error)
            });
        },
        getMeterReaders() {
            axios.get(`${ this.baseURL }/readings/get-meter-readers`)
            .then(response => {
                this.meterReaders = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting meter readers!',
                });
                console.log(error)
            });
        },
        getReportData() {
            axios.get(`${ this.baseURL }/readings/get-reading-report-data`, {
                params : {
                    Period : this.billingMonth,
                    MeterReader : this.meterReader,
                    Day : this.day
                }
            })
            .then(response => {
                this.zones = response.data
            })
            .catch(error => {
                Swal.fire({
                    icon : 'error',
                    title : 'Error getting report data!',
                });
                console.log(error)
            });
        },
        mountMap() {
            const map = new mapboxgl.Map({
                container: this.$refs.mapContainer,
                style: 'mapbox://styles/mapbox/light-v11',
                center: [123.873378, 9.659182], // starting position [lng, lat], 
                zoom: 13 // starting zoom
            });

            this.map = map

            this.map.on('load', (e) => {
                this.map.resize()
                this.addCoordinates()
            })

            this.map.on('idle', (e) => {
                this.map.resize()
            })

        },
        addCoordinates() {
            const el = document.createElement('div');
            el.className = 'marker';
            el.id = 'Test';
            el.title = 'Test'
            el.innerHTML += `<button id="update" class="btn btn-sm text-white"> 
                    <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 1.8em;"></i></span> 
                    Test
                </button>`
            el.style.backgroundColor = `transparent`;                       
            // el.style.width = `20px`;
            el.style.height = `20px`;
            el.style.borderRadius = '50%';
            // el.style.backgroundSize = '100%';

            new mapboxgl.Marker({
                element : el,
                anchor : 'top-left'
            }).setLngLat([123.873378, 9.659182])
               .addTo(this.map);
        }
    },
    created() {
        
    },
    mounted() {
        this.mountMap()

        this.getBillingMonths()
        this.getMeterReaders()
        this.getReportData()
    }
}

</script>