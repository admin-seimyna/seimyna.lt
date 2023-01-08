<template>
    <VPage>
        <Slider :slides="slides">
            <template #finances>
                <DashboardFinances />
            </template>
        </Slider>
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import Slider from '@/Elements/Slider/Slider';
import DashboardFinances from '@/Components/Dashboard/Finances';

export default {
    name: 'Dashboard',
    components: {DashboardFinances, Slider, VPage},
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
