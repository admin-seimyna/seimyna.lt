<template>
    <VPage bubble-top-right>
        <Slider ref="sliderRef"
                :slides="slides"
                horizontal
                disable-user-interaction
        >
            <template #nordigen>
                <Nordigen @close="emit('close')"/>
            </template>
            <template #index>
                <div class="w-full h-full flex flex-col justify-center p-10">
                    <span class="h1 text-xxxl text-white">
                        {{ $t('bank_account.title.new_account')}}
                    </span>

                    <ul class="flex flex-col mt-8">
                        <li class="bg-white flex items-center border rounded p-5 mb-2">
                            <i class="icon-vallet text-xl mr-5" />
                            <span class="font-semibold">
                                {{ $t('bank_account.button.open_virtual_account')}}
                            </span>
                        </li>

                        <li class="bg-white shadow-lg flex items-center border rounded border-primary-500 p-5 text-primary-500"
                            @click="slideTo('nordigen')"
                        >
                            <i class="icon-bank text-xl mr-5" />
                            <span class="font-semibold">
                                {{ $t('bank_account.button.import_from_bank')}}
                            </span>
                        </li>

                        <li class="flex justify-center mt-8"
                            @click="emit('close')"
                        >
                            <span class="font-semibold">
                                {{ $t('common.button.cancel') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </template>
        </Slider>
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import Slider from '@/Elements/Slider/Slider';
import Nordigen from '@/Components/Finances/Account/Nordigen/Index';
import {ref} from 'vue';
export default {
    name: 'AccountCreate',
    components: {Nordigen, Slider, VPage},
    emits: ['close'],
    setup(props, { emit }) {
        const sliderRef = ref(null);

        return {
            emit,
            sliderRef,
            slideTo(name) {
                sliderRef.value.slideTo(name);
            },
            slides: [
                {
                    name: 'nordigen',
                },
                {
                    name: 'index',
                    active: true,
                }
            ]
        }
    }
}
</script>
