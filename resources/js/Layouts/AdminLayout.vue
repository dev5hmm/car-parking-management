<template>
<div>
        <div class="ltr:ml-0 rtl:mr-0 transition md:ltr:ml-60 md:rtl:mr-60" >
            <arc-banner />
        </div>
        <div class="min-h-screen">
            <nav class="fixed top-0 ltr:left-0 rtl:right-0 z-20 h-full pb-10 overflow-hidden transition origin-left transform bg-gray-900 w-60 md:ltr:translate-x-0 md:rtl:-translate-x-0">
                <inertia-link class="flex items-center px-4 py-5" :href="route('home')">
                    <!-- <img :src="$page.props.assetUrl + $page.props.general.logo_path" :alt="$page.props.general.app_name" class="h-8 w-auto"> -->
                </inertia-link>
                <perfect-scrollbar class="h-full overflow-hidden" ref="scroll" :options="scrollbarOptions">
                    <nav class="text-sm font-medium pb-16 text-gray-400" aria-label="Main Navigation">
                        <template v-for="item in items">
                            <sidebar-dropdown :key="item.id" v-if="item.active && item.item_type === 'dropdown'" :title="item.label" :items="item.items">
                                <template #icon>
                                    <span v-if="item.label=='Abacus' || item.label=='Abacus configuration'" class="mr-3">
                                        <font-awesome-icon icon="fa-solid fa-calculator" />
                                    </span>
                                    <span v-else-if="item.label=='Communication'" class="mr-3">
                                        <font-awesome-icon icon="fa-solid fa-message" />
                                    </span>
                                    <span v-else v-html="item.icon">
                                    </span>

                                </template>
                            </sidebar-dropdown>
                            <sidebar-link :key="item.id" v-if="item.active === true && item.item_type === 'link'" :title="item.label" :url="item.url">
                                <template #icon>
                                    <span v-html="item.icon"></span>
                                </template>
                            </sidebar-link>
                            <div :key="item.id" class="my-4 mx-4 uppercase font-semibold text-green-500 text-xs" v-if="item.item_type === 'divider'">
                                {{ item.label }}
                            </div>
                        </template>
                    </nav>
                </perfect-scrollbar>
            </nav>
            <div class="ltr:ml-0 rtl:mr-0 transition" >
                <header class="bg-white shadow flex items-center justify-between w-full md:mx-auto md:sticky md:z-30 md:top-0 px-4 h-14">
                    <button class="block btn btn-light-secondary md:hidden" >
                        <span class="sr-only">{{ __('Menu') }}</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div>
                        <div class="flex">
                            <!-- @click="$store.state.ui.menuCollapse = !$store.state.ui.menuCollapse" -->
                            <svg @click="$store.commit('slideMenu')" class="w-6 h-6 mr-2 hidden md:block cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div class="hidden -ml-3 form-icon md:block w-96">
                                <inertia-link class="text-sm font-semibold px-4 py-5 capitalize" :href="route('home')">
                                     {{ __($page.props.user.role_id) }} {{ __('Dashboard') }}
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <!-- Dark Mode Switch -->
                        <!--<div class="flex justify-between items-center mr-8">
                            <label for="dark_mode" class="text-xs text-gray-800 pb-1">{{ __('Dark Mode') }}</label>
                            <div class="cursor-pointer rounded-full relative shadow-sm">
                                <InputSwitch id="dark_mode" v-model="darkMode" @change="changeMode"/>
                            </div>
                        </div>-->
                        <a href="#" class="flex text-gray-500">
                            <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </a>
                        <!-- Account Dropdown -->
                        <div class="ltr:ml-4 rtl:mr-4 relative">
                            <arc-dropdown align="right" width="48">
                                <template #trigger>
                                    <button  class="flex border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                        <img class="h-8 w-8 rounded-full object-cover" />
                                    </button>

                                    <span  class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.user.name }}
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Account') }}
                                    </div>

                                    <arc-dropdown-link :href="route('profile.show')">
                                        {{ __('Profile') }}
                                    </arc-dropdown-link>

                                    <arc-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                        {{ __('Api Tokens') }}
                                    </arc-dropdown-link>

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Authentication -->
                                    <form method="post" :action="route('logout')">
                                        <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                                        <arc-dropdown-link as="button">
                                            {{ __('Logout') }}
                                        </arc-dropdown-link>
                                    </form>
                                </template>
                            </arc-dropdown>
                        </div>
                    </div>
                </header>
                <section>
                    <div class="mx-auto pt-4 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div>
                                <slot name="header"></slot>
                            </div>
                            <div class="mb-6 sm:mb-0 sm:mt-0">
                                <slot name="actions"></slot>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Page Content -->
                <main>
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <Message v-if="$page.props.successMessage" severity="success" :closable="false">{{ $page.props.successMessage }}</Message>
                        <Message v-if="$page.props.errorMessage" severity="error" :closable="false">{{ $page.props.errorMessage }}</Message>
                    </div>

                    <slot></slot>
                </main>



                <ConfirmDialog></ConfirmDialog>
                <Toast :position="$page.props.rtl ? 'bottom-left' : 'bottom-right'" />
                <Toast :position="$page.props.rtl ? 'top-left' : 'top-right'" group="lm"/>
            </div>
             <!-- Modal Portal -->
                <portal-target name="modal" multiple>
                </portal-target>
            <!-- Sidebar Backdrop -->
            <div class="fixed inset-0 z-10 w-screen h-screen bg-black bg-opacity-25" v-show="sideBar" @click="sideBar = false"></div>
        </div>
    </div>
</template>
<script>
    import ArcApplicationMark from '@/Jetstream/ApplicationMark'
    import ArcBanner from '@/Jetstream/Banner'
    import ArcDropdown from '@/Jetstream/Dropdown'
    import ArcDropdownLink from '@/Jetstream/DropdownLink'
    import ArcNavLink from '@/Jetstream/NavLink'
    import ArcResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import ConfirmDialog from 'primevue/confirmdialog';
    import Toast from 'primevue/toast';
    import Message from 'primevue/message';
    import SidebarDropdown from "@/Jetstream/SidebarDropdown";
    import SidebarLink from "@/Jetstream/SidebarLink";
    import { PerfectScrollbar } from 'vue2-perfect-scrollbar'
    import InputSwitch from "primevue/inputswitch";
    export default {
        components: {
            ArcApplicationMark,
            ArcBanner,
            ArcDropdown,
            ArcDropdownLink,
            ArcNavLink,
            ArcResponsiveNavLink,
            ConfirmDialog,
            Message,
            Toast,
            SidebarDropdown,
            SidebarLink,
            PerfectScrollbar,
            InputSwitch
        },

        data() {
            return {
                sideBar: false,
                darkMode: false,
                scrollbarOptions: {
                    suppressScrollX: true
                },
                successMessage: String,
                errorMessage: String,
                items: [
                    {
                        label:'Home Dashboard',
                        item_type: 'link',
                        icon: '<svg class="flex-shrink-0 w-5 h-5 ltr:mr-2 rtl:ml-2 transition group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                        url: route('admin_dashboard'),
                        active: this.$page.props.user.role_id === 'admin' || this.$page.props.user.role_id == 'manager',
                    },
                    {
                        label:'Home Dashboard',
                        item_type: 'link',
                        icon: '<svg class="flex-shrink-0 w-5 h-5 ltr:mr-2 rtl:ml-2 transition group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                        url: route('instructor_dashboard'),
                        active: this.$page.props.user.role_id === 'instructor',
                    },
                    {
                        label:'Users',
                        item_type: 'dropdown',
                        icon:'<svg class="flex-shrink-0 w-5 h-5 ltr:mr-2 rtl:ml-2 transition group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                        active: this.$page.props.user.role_id === 'admin'  || this.$page.props.user.role_id == 'manager' || this.$page.props.user.role_id == 'instructor',
                        items:[
                            {
                                label:'Admin',
                                url: route('users.index'),
                                active: this.$page.props.user.role_id === 'admin',
                            },
                        ],
                    },



                ]
            }

        },
        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            changeMode() {
                if (this.darkMode) {
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                }
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
    },
    mounted() {

    }

    }
</script>
