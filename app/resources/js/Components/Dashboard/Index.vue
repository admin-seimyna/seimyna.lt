<template>
    <VPage bubble-top-center>
        <span>Dashboard</span>
        <span @click="logout" class="mt-5">Logout</span>
    </VPage>
</template>
<script>
import {useStore} from 'vuex';
import {useRouter} from 'vue-router';
import VPage from '@/Elements/Page';

export default {
    name: 'Dashboard',
    components: {VPage},
    setup(props) {
        const store = useStore();
        const router = useRouter();

        return {
            logout() {
                store.commit('app/loading', true);
                store.dispatch('auth/logout').then(() => {
                    router.push({name: 'login'});
                }).catch(() => {
                    store.commit('app/loading', false);
                });
            }
        }
    }
}
</script>
