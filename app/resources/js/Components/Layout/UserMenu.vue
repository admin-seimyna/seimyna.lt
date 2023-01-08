<template>
    <div class="w-full flex flex-col">
        <div class="flex items-center px-5 py-3 border-b border-solid">
            <Avatar :subject="user"
                    class="w-12 h-12 bg-gray-200"
            />

            <span class="ml-5 font-semibold ellipsis">
                {{ user.name }}
            </span>
        </div>

        <ul class="w-full flex flex-col">
            <li class="flex items-center px-5 py-3"
                @click="logout"
            >
                <i class="icon-sign-out mr-5" />
                <span class="font-semibold">
                    {{ $t('auth.button.logout')}}
                </span>
            </li>
        </ul>
    </div>
</template>
<script>
import Avatar from '@/Elements/Avatar';
import {useStore} from 'vuex';
import {computed, inject} from 'vue';
import {useRouter} from 'vue-router';
export default {
    name: 'UserMenu',
    components: {Avatar},
    emits: ['close'],
    setup(props, { emit }) {
        const app = inject('app');
        const store = useStore();
        const router = useRouter();

        return {
            user: computed(() => {
                return store.getters['auth/user'];
            }),
            logout() {
                app.dialog.logout().then(() => {
                    emit('close');
                    store.commit('app/loading', true);
                    store.dispatch('auth/logout').then(() => {
                        router.push({name: 'login'});
                    }).catch(() => {
                        store.commit('app/loading', false);
                    });
                })
            }
        }
    }
}
</script>
