<template>
    <app-layout>
        <template #header>
            <h1>Setting</h1>
        </template>
        <div>
            <button @click="addForm()" class="bg-green-500 text-white font-bold py-2 px-5 text-lg">
                    Add Date Range
            </button>
            <div v-for="(setting, index) in (settingForm)" :key="index">
                <div class="flex">
                    <div class=" flex flex-col mb-6 ml-2">
                        <label for="from font-bold">From <span class="ml-1 text-red-400">*</span></label>
                        <input type="number" id="from" v-model="settingForm[index].from"  class="font-light text-gray-500 border-gray-700">
                    </div>
                    <div class=" flex flex-col mb-6 ml-2">
                        <label for="to font-bold">To <span class="ml-1 text-red-400">*</span></label>
                        <input type="number" id="to" v-model="settingForm[index].to" class="font-light text-gray-500 border-gray-700">
                    </div>
                    <div class=" flex flex-col mb-6 ml-2">
                        <label for="fee font-bold">Fee <span class="ml-1 text-red-400">*</span></label>
                        <input type="number" id="to" v-model="settingForm[index].fee"  class="font-light text-gray-500 border-gray-700">
                    </div>
                    <div class="flex">
                        <div class="flex items-center ml-2">
                            <button @click="removeForm(index)" class="bg-red-400 px-4 py-2 rounded text-white">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="settingForm.length" class="flex justify-end w-3/4">
                <Link :href="route(route().current())">
                    <button class="text-black font-bold bg-gray-200 py-3 px-6 rounded mr-3">
                        Cancel
                    </button>
                </Link>
                <button @click="save" class="text-white font-bold bg-blue-500 py-3 px-6 rounded">
                    Save
                </button>
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
        settings: Array
    },
    data() {
        return {
            settingForm : this.settings ? this.settings : [],
        }
    },
    methods: {
        submit() {
            // this.$inertia.post(route('admin.services.store'), this.form)
        },
        addForm() {

            if (this.settingForm.length < 1) {
                this.settingForm.push({
                    from: 1,
                    to: 1,
                    fee: 1,
                })
            } else {
                console.log("It works");
                var lastSetting = this.settingForm[this.settingForm.length - 1];
                if (lastSetting.from && lastSetting.to && lastSetting.fee) {
                    this.settingForm.push({
                        from: lastSetting.to + 1,
                        to: lastSetting.to + 1,
                        fee: 1,
                    })
                }
            }
        },
        removeForm(index) {
            this.settingForm.splice(index, 1);
        },
        save() {
            this.$inertia.patch('setting-update', this.settingForm)
        }

    }
}

</script>
