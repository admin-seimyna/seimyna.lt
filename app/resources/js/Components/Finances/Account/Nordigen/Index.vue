<template>
    <Slider ref="sliderRef"
            :slides="slides"
            disable-user-interaction
            class="bg-white"
    >
        <template #banks>
            <NordigenBanks v-model="bank"
                           @next="next"
            />
        </template>
        <template #iframe="{active}">
            <NordigenIframe v-if="active"
                            :bank="bank"
                            @back="back"
                            @next="next"
            />
        </template>

        <template #success="{active}">
            <NordigenSuccess :active="active"
                             @close="emit('close')"
            />
        </template>
    </Slider>
</template>
<script>
import Slider from '@/Elements/Slider/Slider';
import NordigenBanks from '@/Components/Finances/Account/Nordigen/Banks';
import {reactive, ref} from 'vue';
import NordigenIframe from '@/Components/Finances/Account/Nordigen/NordigenIframe';
import NordigenSuccess from '@/Components/Finances/Account/Nordigen/NordigenSuccess';

export default {
    name: 'Nordigen',
    components: {NordigenSuccess, NordigenIframe, NordigenBanks, Slider},
    emits: ['close'],
    setup(props, { emit }) {
        const sliderRef = ref('sliderRef');
        const bank = reactive({});

        return {
            emit,
            sliderRef,
            bank,
            slides: [
                {
                    name: 'banks'
                }, {
                    name: 'iframe',
                }, {
                    name: 'success',
                }
            ],
            next() {
                sliderRef.value.next();
            },
            back() {
                sliderRef.value.back();
            }
        };
    }
}
</script>
