<template>
    <Link :href="route(routeName)" class="flex items-center py-2 pl-12 pr-4 transition cursor-pointer hover:bg-gray-800 " :class="[ route().current() == routeName? 'text-green-400' : 'text-gray-200']"  >
        {{ text }}
    </Link>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
export default {
    components: {
        Link
    },
    props: {
        routeName: String,
        text: String
    }
}

</script>
