<template>
    <div class="gallery-flex">
        <div class="card shadow-soft" v-for="imgs in files">
            <img class="card-img-top" style="border-radius: inherit;" :src="filePath + scId + '/images/' + imgs.file" alt="">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <!-- <h5 class="card-title text-primary text-white">Card Title</h5> -->
                <p class="card-text no-pads ellipsize-auto">{{ imgs.file }}</p>
                <div class="block">
                    <span class="text-muted" style="display: inline;">{{ imgs.dateModified }}</span>
                    <div class="dropdown" style="display: inline;">
                        <a class="btn btn-link-muted btn-sm float-right" href="#" id="more-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="more-menu">
                            <a target="_blank" class="dropdown-item" :href="filePath + scId + '/images/' + imgs.file"><i class="fas fa-external-link-alt ico-tab"></i>View</a>
                            <button @click="trash(imgs.file)" class="dropdown-item"><i class="fas fa-trash ico-tab"></i>Delete</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
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
    name : 'ImagesGallery.images-gallery',
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
            scId : document.querySelector("meta[name='scId']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            files : {},
            filePath : window.location.origin + axios.defaults.filePath,
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
        getFiles() {
            this.files = {}
            axios.get(`${ this.baseURL }/service_connections/fetch-images`, {
                params : {
                    ServiceConnectionId : this.scId,
                }
            })
            .then(response => {
                this.files = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting images!\n' + error.response.data
                })
            })
        },
        // goToFile(file) {
        //     window.open(file, '_blank');
        // },
        // rename(oldName) {
        //     (async () => {
        //         const { value: text } = await Swal.fire({
        //             input: 'text',
        //             inputPlaceholder: 'New Name',
        //             inputAttributes: {
        //                 'aria-label': 'Type your remarks here'
        //             },
        //             title: 'Rename File',
        //             text : 'Rename ' + oldName,
        //             showCancelButton: true
        //         })

        //         if (text) {
        //             if (this.isNull(text)) {
        //                 this.toast.fire({
        //                     icon : 'info',
        //                     text : 'Please provide name to rename!',
        //                 })
        //             } else { 
        //                 try {
        //                     axios.get(`${ axios.defaults.baseURL }/employees/rename-file`, {
        //                         params : {
        //                             EmployeeId : this.employeeId,
        //                             OldFileName : oldName,
        //                             NewFileName : text,
        //                         }
        //                     })
        //                     .then(response => {
        //                         this.toast.fire({
        //                             icon : 'success',
        //                             text : 'File renamed!'
        //                         })
        //                         this.getFiles()
        //                     })
        //                     .catch(error => {
        //                         console.log(error)
        //                         this.toast.fire({
        //                             icon : 'error',
        //                             text : error.response.data
        //                         })
        //                     })
        //                 } catch (err) {
        //                     console.log(err)
        //                     Swal.fire({
        //                         icon : 'info',
        //                         title : 'Oops!',
        //                         text : err.message,
        //                     })
        //                 }
        //             }
        //         }
        //     })()
        // },
        trash(file) {
            Swal.fire({
                icon : 'warning',
                title: "Move image to trash?",
                text : 'This cannot be undone',
                showCancelButton: true,
                confirmButtonText: "Move to Trash",
                confirmButtonColor: '#e03822',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/service_connections/trash-file`, {
                        ServiceConnectionId : this.scId,
                        CurrentFile : file,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'info',
                            text : 'Image deleted!'
                        })
                        this.files = this.files.filter(obj => obj.file !== file)
                    })
                    .catch(error => {
                        console.log(error)
                        this.toast.fire({
                            icon : 'error',
                            text : error.response.data
                        })
                    })
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getFiles()
    }
}

</script>