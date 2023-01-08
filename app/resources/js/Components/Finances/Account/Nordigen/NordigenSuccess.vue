<template>
    <VPage class="px-10">
        <div class="w-full h-full flex-center">
            <div class="flex flex-col items-center">
                <AnimatedIcon v-if="active"
                              success
                              class="w-24 h-24"
                />

                <span class="w-full text-center h1 text-xxxl my-8">
                    {{ $t('bank_account.title.accounts_successfully_imported') }}
                </span>

                <VButton basic
                         shadow
                         @click="emit('close')"
                >
                    {{ $t('common.button.close') }}
                </VButton>
            </div>
        </div>
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import VButton from '@/Elements/Button';
import AnimatedIcon from '@/Elements/AnimatedIcon';
export default {
    name: 'NordigenSuccess',
    components: {AnimatedIcon, VButton, VPage},
    emits: ['close'],
    props: {
        active: Boolean,
    },

    setup(props, {emit}) {
        return {
            emit,
        };
    }
}
</script>
