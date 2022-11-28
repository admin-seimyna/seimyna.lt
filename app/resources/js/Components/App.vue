<template>
    <div class="flex flex-col overflow-y-auto w-full h-full">
        <router-view />
        <modal />
    </div>
</template>
<script>

import Modal from '@/Elements/Modal';
export default {
    name: 'App',
    components: {
        Modal
    },
}
</script>
