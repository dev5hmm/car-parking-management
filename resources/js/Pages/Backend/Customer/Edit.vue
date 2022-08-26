<template>
    <app-layout>
        <template #header>
            <h1>Customer Edit Form</h1>
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
                            <label for="email">Email <span class="ml-1 text-red-400">*</span></label>
                            <input type="email" id="email" v-model="form.email" class="font-light text-gray-500">
                            <small id="email-help" v-if="errors.email" class="text-red-400">{{ errors.email }}</small>
                        </div>
                        <div class="flex justify-end w-3/4">
                            <Link :href="route('admin.users.index')">
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


export default {
    components: {
    AppLayout,
    Button,
    Link,
    Input
},
    props: {
        errors: Object,
        customer: Object
    },
    data() {
        return {
            form : {
                name: this.customer.data.name,
                email: this.customer.data.email,
            }
        }
    },
    methods: {
        submit() {
            this.$inertia.patch(route('customers.update', { id: this.customer.data.id}), this.form)
        }
    }
}

</script>
