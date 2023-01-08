<template>
    <div class="w-full flex flex-col px-10 overflow-hidden">
        <div v-if="loading"
             class="w-full h-full flex-center"
        >
            <VSpinner class="w-10 h-10" />
        </div>

        <template v-else>
            <span class="h1 text-xxxl mt-8 mb-5">
                {{ $t('bank_account.title.select_bank') }}
            </span>

            <ul class="flex flex-col w-full h-full pb-5 overflow-y-auto">
                <li v-for="bank in banks"
                    :key="bank.id"
                    class="flex items-center border rounded px-3 h-14 shrink-0 mb-1"
                    :class="{
                    'border-primary-500 shadow-lg': modelValue.id === bank.id,
                }"
                    @click="select(bank)"
                >
                    <img :src="bank.logo"
                         class="w-10 h-auto"
                    />
                    <span class="font-semibold ml-3 ellipsis"
                          :class="{
                        'text-primary-500': modelValue.id === bank.id,
                    }"
                    >
                    {{ bank.name }}
                </span>
                </li>
            </ul>

            <div class="flex-center py-5">
                <VButton rounded
                         shadow
                         :disabled="nextIsDisabled"
                         class="w-16 h-16"
                         @click="emit('next')"
                >
                    <i class="icon-arr-down text-xl text-primary-500" />
                </VButton>
            </div>
        </template>
    </div>
</template>
<script>
import axios from 'axios';
import {computed, ref} from 'vue';
import VSpinner from '@/Elements/Spinner';
import {useStore} from 'vuex';
import VButton from '@/Elements/Button';

export default {
    name: 'NordigenBanks',
    components: {VButton, VSpinner},
    emits: ['next', 'update:modelValue'],
    props: {
        modelValue: Object,
    },
    setup(props, { emit }) {
        const loading = ref(false);
        const store = useStore();
        const banks = computed(() => {
            return store.getters['bank/list'];
        });


        if (!banks.value.length) {
            loading.value = true;
            axios.get('/finances/bank/list').then((response) => {
                loading.value = false;
            });
        }

        return {
            emit,
            loading,
            banks,
            nextIsDisabled: computed(() => {
                return !props.modelValue.id;
            }),
            select(bank) {
                emit('update:modelValue', Object.assign(props.modelValue, bank));
            }
        }
    }
}
</script>
