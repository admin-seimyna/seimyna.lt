<template>
    <VPage bubble-top-center
           class="items-center px-10"
    >
        <Slider :slides="slides"></Slider>
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import Slider from '@/Elements/Slider/Slider';

export default {
    name: 'Dashboard',
    components: {Slider, VPage},
    setup(props) {
        return {
            slides: [
                {
                    name: 'finances'
                }
            ]
        }
    }
}
</script>
