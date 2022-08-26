<template>
    <nav  class="relative px-4 space-y-3">


        <SideBarLink v-if="$page.props.user.role_name == 'admin'" route-name="admin.users.index" text="Users" />
        <SideBarLink v-if="$page.props.user.role_name == 'admin'" route-name="admin.settings" text="Setting" />
        <SideBarLink v-if="$page.props.user.role_name == 'admin'" route-name="admin.services.index" text="Services" />
        <SideBarLink route-name="customers.index" text="Customers" />
        <SideBarLink route-name="bookings.index" text="Bookings" />

    </nav>
</template>
<script>
import SideBarLink from './SideBarLink';
export default {
    components: {
        SideBarLink
    },
}
</script>
