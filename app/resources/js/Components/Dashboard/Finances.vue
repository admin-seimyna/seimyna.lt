<template>
    <div class="w-full h-full bg-primary-500 p-10">
        <span class="h1 text-white text-xxxl">Biudžetas</span>
        <VButton primary
                 @click="openAccount"
        >
            Atsidaryti sąskaitą
        </VButton>
    </div>
</template>
<script>
import VButton from '@/Elements/Button';
import AccountCreate from '@/Components/Finances/Account/Create';
import {inject} from 'vue';

export default {
    name: 'DashboardFinances',
    components: {VButton},
    setup(props) {
        const app = inject('app');

        return {
            openAccount() {
                app.modal({
                    component: AccountCreate
                });
            }
        }
    }
}
</script>
