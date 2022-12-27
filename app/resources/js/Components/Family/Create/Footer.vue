<template>
    <div class="flex items-center justify-around mt-auto pb-5">
        <VButton v-if="!disableBack"
                 type="span"
                 rounded
                 shadow
                 class="bg-white w-14 h-14"
                 @click="emit('back')"
        >
            <i class="icon-arr-left" />
        </VButton>

        <VButton rounded
                 shadow
                 class="w-16 h-16"
                 :primary="!disableBack"
                 :class="{
                    'bg-white': disableBack
                 }"
                 :progress="progress"
        >
            <i class="icon-arr-right" />
        </VButton>
    </div>
</template>
<script>
import VButton from '@/Elements/Button';
export default {
    name: 'FamilyCreateFooter',
    components: {VButton},
    props: {
        disableBack: Boolean,
        progress: Boolean,
    },
    emits: ['back'],
    setup(props, {emit}) {
        return { emit };
    }
}
</script>
