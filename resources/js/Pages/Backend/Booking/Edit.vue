<template>
    <app-layout>
        <template #header>
            <h1>Booking Create Form</h1>
        </template>
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="my-6">
                        <div class="w-3/4 mb-6 flex justify-center font-bold">
                            Total - {{total}}
                        </div>

                        <div class="w-3/4 flex flex-col mb-6">
                            <label for="car_no">Car No <span class="ml-1 text-red-400">*</span></label>
                            <input type="text" id="car_no" v-model="form.car_no" class="font-light text-gray-500 border-gray-700">
                            <small id="car_no-help" v-if="errors.car_no" class="text-red-400">{{ errors.car_no }}</small>
                        </div>
                        <div class="w-3/4 flex flex-col mb-6">
                            <label for="duration_type">Enter Duration<span class="ml-1 text-red-400">*</span></label>
                            <div class="flex">
                                <input type="number" v-model="form.duration" class="font-light text-gray-500">
                                <Dropdown class="w-full ml-4" v-model="form.duration_type" :options="['day', 'week']" placeholder="Select Type" />
                            </div>
                            <small id="duration-help" v-if="errors.duration" class="text-red-400">{{ errors.duration }}</small>
                            <small id="duration_type-help" v-if="errors.duration_type" class="text-red-400">{{ errors.duration_type }}</small>

                        </div>
                        <div  class="w-3/4 flex flex-col mb-6">
                            <label for="service_id">Select Additional Services</label>
                            <MultiSelect v-model="form.services" :options="services.data" optionValue="id" optionLabel="name" placeholder="Select services" />

                        </div>

                        <div  class="w-3/4 flex flex-col mb-6">
                            <label for="note">Keep Note</label>
                            <textarea id="" cols="30" rows="10" v-model="form.note"></textarea>

                        </div>

                        <div class="flex justify-end w-3/4">
                            <Link :href="route('bookings.index')">
                                <button class="text-black font-bold bg-gray-200 py-3 px-6 rounded mr-3">
                                    Cancel
                                </button>
                            </Link>
                            <button @click="submit" class="text-white font-bold bg-blue-500 py-3 px-6 rounded">
                                Update
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from 'primevue/button';
import Input from "@/Jetstream/Input";
import { Link } from '@inertiajs/inertia-vue3'
import MultiSelect from 'primevue/multiselect';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';


export default {
    components: {
    AppLayout,
    Button,
    Link,
    Input,
    MultiSelect,
    Dropdown
},
    props: {
        errors: Object,
        customers: Object,
        services: Object,
        min_duration: Number,
        max_duration: Number,
        booking: Object,
    },
    data() {
        return {
            total_duration: 0,
            propessing: false,
            total: this.booking.data.total,
            form : {
                note: this.booking.data.note,

                services: this.booking.data.service_ids,
                car_no: this.booking.data.car_no,
                duration: this.booking.data.duration,
                duration_type: this.booking.data.duration_type,
            }
        }
    },
    watch: {
        'form.duration'(val, old) {
            if (val) {
                if (this.form.duration_type) {

                    var totalDay = this.form.duration_type == 'week' ? this.form.duration * 7 : this.form.duration * 1;

                    if (totalDay > this.max_duration || totalDay <this.min_duration) {
                        this.form.duration = old;
                        this.errors.duration = "Invalid Duration";
                    } else {
                        this.fetch(totalDay)
                    }
                }
            }
        },
        'form.duration_type'(val, old) {
            if (val) {
                if (this.form.duration) {
                    var totalDay = this.form.duration_type == 'week' ? this.form.duration * 7 : this.form.duration * 1;
                    if (totalDay > this.max_duration || totalDay <this.min_duration) {
                        this.form.duration_type = old;
                        this.errors.duration = "Invalid Duration";
                    } else {
                        this.fetch(totalDay)
                    }
                }
            }
        },
        'form.services'(val) {
            if (this.form.duration && this.form.duration_type) {
                var totalDay = this.form.duration_type == 'week' ? this.form.duration * 7 : this.form.duration * 1;
                this.fetch(totalDay)
            }
        }

    },
    methods: {
        submit() {
            this.$inertia.patch(route('bookings.update', {id: this.booking.data.id}), this.form)
        },
        fetch(total_duration, services) {
            let _this = this;
            services = this.form.services;
            axios.get(route('bookings.get-total-amount'), {params :{
                total_duration: total_duration,services
            }}).then(function (data) {

                if (data.data.success) {
                    _this.total = data.data.total;
                } else {
                    _this.total = data.data.total;
                    _this.errors.duration = "Invalid Duration";
                }
            })
        }

    }
}

</script>
