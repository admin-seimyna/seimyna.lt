<template>
    <div class="w-full flex items-center justify-between h-16 bg-white shadow px-10 absolute bottom-0 left-0 z-10">
        <router-link
            :to="{ name: 'dashboard'}"
            class="flex items-center justify-center w-12 h-12 rounded-full"
        >
            <i class="icon-home text-xl text-text-light" />
        </router-link>

        <div class="text-xl flex items-center justify-center w-14 h-14 rounded-full text-primary-500 font-bold border-2 border-solid border-primary-500"
             @click="createFamily"
        >
            +
        </div>

        <Avatar :subject="user"
                ignore-color
                class="w-12 h-12 bg-gray-200"
                @click="openUserMenu"
        />
    </div>
</template>
<script>
import Avatar from '@/Elements/Avatar';
import {computed, inject} from 'vue';
import {useStore} from 'vuex';
import FamilyCreate from '@/Components/Family/Create/Index';
import UserMenu from '@/Components/Layout/UserMenu';

export default {
    name: 'Navigation',
    components: {
        Avatar
    },
    setup(props) {
        const app = inject('app');
        const store = useStore();
        return {
            user: computed(() => {
                return store.getters['auth/user'];
            }),

            openUserMenu() {
                app.modal({
                    component: UserMenu,
                    options: {
                        type: 'popup'
                    }
                });
            },

            createFamily() {
                app.modal({
                    component: FamilyCreate,
                    options: {
                        disableBackButton: true
                    }
                });
            }
        }
    }
}
</script>
