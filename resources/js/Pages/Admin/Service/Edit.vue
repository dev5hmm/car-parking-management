<template>
    <app-layout>
        <template #header>
            <h1>Service Edit Form</h1>
        </template>
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="my-6 w-3/4">
                        <div class="w-3/4 flex flex-col mb-6">
                            <label for="name">Name <span class="ml-1 text-red-400">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="font-light text-gray-500 border-gray-700">
                            <small id="name-help" v-if="errors.name" class="text-red-400">{{ errors.name }}</small>
                        </div>
                        <div class="w-3/4 flex flex-col mb-6">
                            <label for="fee">Fee <span class="ml-1 text-red-400">*</span></label>
                            <input type="number" id="fee" v-model="form.fee" class="font-light text-gray-500">
                            <small id="fee-help" v-if="errors.fee" class="text-red-400">{{ errors.fee }}</small>
                        </div>
                        <div class="w-3/4">
                            <div class="flex justify-between items-center mb-8">
                                <div class="w-9/12">
                                    <label for="is_active" class="font-semibold text-gray-800 pb-1" v-html="form.is_active ? 'Status'+' - '+'Active' : 'Status'+' - '+ 'In-active'"></label>
                                    <p class="text-gray-500">{{ 'Active' }} ({{ 'Allow Selection' }}). {{ 'In-active' }} ({{ 'Disallow Selection' }}).</p>
                                </div>
                                <div class="cursor-pointer rounded-full relative shadow-sm">
                                    <InputSwitch id="is_active" v-model="form.is_active" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end w-3/4">
                            <Link :href="route('admin.services.index')">
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
import { Link } from '@inertiajs/inertia-vue3';
import InputSwitch from 'primevue/inputswitch';


export default {
    components: {
    AppLayout,
    Button,
    Link,
    Input,
    InputSwitch
},
    props: {
        errors: Object,
        service: Object
    },
    data() {
        return {
            form : {
                name: this.service.data.name,
                fee: this.service.data.fee,
                is_active: this.service.data.is_active ? true: false,
            }
        }
    },
    methods: {
        submit() {
            this.$inertia.patch(route('admin.services.update', {id: this.service.data.id}), this.form)
        }
    }
}

</script>
