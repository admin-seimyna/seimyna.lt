<template>
    <div class="flex flex-col overflow-y-auto w-full h-full overflow-hidden">
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

        <Navigation v-if="showNavigation" />

        <modal />
    </div>
</template>
<script>

import Modal from '@/Elements/Modal';
import Splash from '@/Elements/Splash';
import {computed} from 'vue';
import {useStore} from 'vuex';
import Navigation from '@/Components/Layout/Navigation';
import {useRoute} from 'vue-router';

export default {
    name: 'App',
    components: {
        Navigation,
        Splash,
        Modal
    },
    setup() {
        const store = useStore();
        const route = useRoute();

        return {
            loading: computed(() => {
                return store.getters['app/loading'];
            }),

            showNavigation: computed(() => {
                return (typeof route.meta.navigation === 'undefined' || !!route.meta.navigation) && !route.meta.public && !!store.getters['auth/user'];
            }),
        }
    }
}
</script>
