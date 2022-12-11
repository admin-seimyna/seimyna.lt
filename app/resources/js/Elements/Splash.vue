<template>
    <div class="flex flex-col items-center justify-center fixed top-0 left-0 w-full h-full z-max bg-white">
        <Logo primary class="w-24" />
        <span class="font-bold mb-10">{{ appName }}</span>
        <VSpinner class="w-14 h-14" />
    </div>
</template>
<script>
import Logo from '@/Elements/Logo';
import VSpinner from '@/Elements/Spinner';
import {computed, inject} from 'vue';

export default {
    name: 'Splash',
    components: {VSpinner, Logo},
    setup() {
        const app = inject('app');
        return {
            appName: computed(() => {
                return app.config.get('system.app.name');
            })
        }
    }
}
</script>
