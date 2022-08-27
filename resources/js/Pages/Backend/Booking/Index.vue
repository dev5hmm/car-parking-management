<template>
    <app-layout>
        <template #header>
            <h1 class="">Bookings</h1>
        </template>
        <template #action>
            <Link :href="route('bookings.create')">
                <button class="bg-green-500 text-white font-bold py-2 px-5 text-lg">
                    Create
                </button>
            </Link>
        </template>
        <div>
            <div class="card">
                <div class="card-body">
                    <vue-good-table
                        mode="remote"
                        v-on:page-change="onPageChange"
                        v-on:column-filter="onColumnFilter"
                        v-on:per-page-change="onPerPageChange"
                        :pagination-options="options"
                        :columns="columns"
                        :rows="bookings.data"
                        :totalRows="bookings.meta.total"
                    >
                        <template #table-row="props">
                        <!-- Action Column -->
                        <div v-if="props.column.field == 'actions'">
                            <div v-if="!props.row.has_paid">
                                <button @click="paid(props.row.id)" class="bg-red-500 text-white font-bold py-2 px-5 "> Paid</button>
                                <Link :href="route('bookings.edit', {booking: props.row.id})" >

                                    <Button label="Edit" class="bg-red-500 text-white font-bold py-2 px-4 my"/>
                                </Link>
                                <button @click="deleteRecord(props.row.id)" class="bg-red-500 text-white font-bold py-2 px-5 "> Delete</button>
                            </div>


                        </div>

                        <div v-if="props.column.field == 'services'">
                            <span v-for="service in props.row.services" >
                                {{service.name}} ,
                            </span>
                        </div>


                        <!-- Remaining Columns -->
                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                        </template>
                        <div slot="emptystate">
                        <no-data-table>
                            <template slot="action">
                            <button
                            >
                                {{ __("New") }} {{ __("User") }}
                            </button>
                            </template>
                        </no-data-table>
                        </div>
                    </vue-good-table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import NoDataTable from "@/Jetstream/NoDataTable";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { Link } from '@inertiajs/inertia-vue3'


export default {
    components: {
        AppLayout,
        NoDataTable,
        Button,
        InputText,
        Link
    },
    props: {
        bookings: Object,
        errors: Object,
    },
    data() {
        return {
            value: '',
            createForm: false,
            columns: [
                {
                    label: 'Date',
                    field: 'created_at',
                    type: 'date',
                    dateInputFormat: 'yyyy-MM-dd h:m:s', // expects 2018-03-16
                    dateOutputFormat: 'MMM do yyyy', // outputs Mar 16th 2018
                },

                {
                    label: "Name",
                    field: "customer_name",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search Name",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "Email",
                    field: "customer_email",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search Email",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "Car No",
                    field: "car_no",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search Car No",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "Duration",
                    field: "full_duration",

                    width: '6rem',
                    sortable: false,
                },
                {
                    label: "Services",
                    field: "services",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search services",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "Note",
                    field: "note",

                    sortable: false,
                },
                {
                    label: "Actions",
                    field: "actions",
                    sortable: false,
                    width: "16rem",
                },
            ],
            options: {
                enabled: true,
                mode: "pages",
                perPage: this.bookings.meta.per_page,
                setCurrentPage: this.bookings.meta.current_page,
                perPageDropdown: [10, 20, 50, 100],
                dropdownAllowAll: false,
            },
            serverParams: {
                columnFilters: {},
                sort: {
                field: "",
                type: "",
                },
                page: 1,
                perPage: 10,
            },

            loading: false,
        }
    },
    methods: {
        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);
        },
        onPageChange(params) {
            this.updateParams({ page: params.currentPage });
            this.loadItems();
        },
        onPerPageChange(params) {
            console.log(params)
            this.updateParams({ perPage: params.currentPerPage });
            this.loadItems();
        },

        onColumnFilter(params) {

            this.updateParams(params);
            this.serverParams.page = 1;
            this.loadItems();
        },
        getQueryParams() {
            let data = {
                page: this.serverParams.page,
                perPage: this.serverParams.perPage,
            };

            for (const [key, value] of Object.entries(
                this.serverParams.columnFilters
            )) {
                if (value) {
                data[key] = value;
                }
            }

            return data;
        },
        loadItems() {
            this.$inertia.get(route(route().current()), this.getQueryParams(), {
                replace: false,
                preserveState: true,
                preserveScroll: true,
            });
        },
        deleteRecord(id) {
            this.$confirm.require({
                message: 'Are you sure you want to delete this record?',
                header: 'Confirmation',
                icon: "pi pi-info-circle",
                acceptClass: "p-button-danger",
                rejectLabel: "Cancel",
                acceptLabel: "Delete",
                accept: () => {
                    this.$inertia.delete(route("bookings.destroy", { id: id }));
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        paid(id) {
            this.$confirm.require({
                message: 'Are you sure this customer paid the bill?.Be Aware. The paid record cannot be changed.',
                header: 'Confirmation',
                icon: "pi pi-info-circle",
                acceptClass: "p-button-danger",
                rejectLabel: "Cancel",
                acceptLabel: "Confirm",
                accept: () => {
                    this.$inertia.patch(route("bookings.paid", { id: id }));
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        }
    }
}

</script>
