<template>
    <app-layout>
        <template #header>
            <h1 class="">Users</h1>
        </template>
        <template #action>
            <Link :href="route('admin.users.create')">
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
                        :rows="users.data"
                        :totalRows="users.meta.total"
                    >
                        <template #table-row="props">
                        <!-- Action Column -->
                        <div v-if="props.column.field == 'actions'">
                            <Link :href="route('admin.users.edit', {user: props.row.id})" >

                                <Button label="Edit" class="p-button-rounded p-button-secondary p-button-text p-mr-2"/>
                            </Link>
                            <button @click="deleteRecord(props.row.id)" class="bg-red-500 text-white font-bold py-2 px-5 "> Delete</button>

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
        users: Object,
        errors: Object,
    },
    data() {
        return {
            value: '',
            createForm: false,
            columns: [
                {
                    label: "Name",
                    field: "name",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search Name",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "email",
                    field: "email",
                    filterOptions: {
                        enabled: true,
                        placeholder: "Search Email",
                        trigger: "enter",
                    },
                    sortable: false,
                },
                {
                    label: "Actions",
                    field: "actions",
                    sortable: false,
                    width: "14rem",
                },
            ],
            options: {
                enabled: true,
                mode: "pages",
                perPage: this.users.meta.per_page,
                setCurrentPage: this.users.meta.current_page,
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
                    this.$inertia.delete(route("admin.users.destroy", { id: id }));
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        }
    }
}

</script>
