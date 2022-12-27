<template>
    <VPage bubble-top-right
           class="px-10"
    >
        <div class="flex flex-col mt-auto">
            <span v-if="name"
                  class="text-xs text-white"
            >
                {{ $t('family.title.my_family') }}
            </span>
            <span class="h1 text-xxxl text-white">
                {{ name || $t('family.title.my_family') }}
            </span>
        </div>

        <VInput v-model="name"
                name="name"
                class="mt-8"
                :title="$t('field.title.title')"
                :error="error"
        />

        <FamilyCreateFooter
            :progress="progress"
            disable-back
        />
    </VPage>
</template>
<script>
import VInput from '@/Elements/Input';
import {ref, watch} from 'vue';
import VPage from '@/Components/Layout/Page';
import FamilyCreateFooter from '@/Components/Family/Create/Footer';

export default {
    name: 'FamilyName',
    components: {FamilyCreateFooter, VPage, VInput},
    props: {
        modelValue: [String, Number],
        progress: Boolean,
        error: String,
    },
    emits: ['cancel'],
    setup(props, {emit}) {
        const name = ref(props.modelValue);

        watch(
            () => name.value,
            (value) => {
                emit('update:modelValue', value);
            }
        );

        return {
            name,
        };
    }
}
</script>
