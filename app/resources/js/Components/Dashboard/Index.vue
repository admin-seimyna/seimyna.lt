<template>
    <VPage bubble-top-center
           class="items-center px-10"
    >
        <div class="flex">
            <span class="text-xxxl text-white font-bold">
                {{ $t('dashboard.title.hello')}}
            </span>
        </div>
        <span @click="logout" class="mt-5">Logout</span>
    </VPage>
</template>
<script>
import {useStore} from 'vuex';
import {useRouter} from 'vue-router';
import VPage from '@/Components/Layout/Page';

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
