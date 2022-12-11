<template>
    <div class="flex flex-col overflow-y-auto w-full h-full">
        <transition
            appear
            name="splash"
        >
            <Splash v-if="loading" />
        </transition>

        <router-view v-slot="{ Component }">
            <transition name="page">
                <component :is="Component" />
            </transition>
        </router-view>
        <modal />
    </div>
</template>
<script>

import Modal from '@/Elements/Modal';
import Splash from '@/Elements/Splash';
import {computed} from 'vue';
import {useStore} from 'vuex';
export default {
    name: 'App',
    components: {
        Splash,
        Modal
    },
    setup() {
        const store = useStore();
        return {
            loading: computed(() => {
                return store.getters['app/loading'];
            }),
        }
    }
}
</script>
