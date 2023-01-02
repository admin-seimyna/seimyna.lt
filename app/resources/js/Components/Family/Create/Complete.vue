<template>
    <VPage class="px-10">
        <div class="w-full h-full flex-center flex-col">
            <div v-if="progress"
                 class="flex-center flex-col w-full"
            >
                <VSpinner class="w-10 h-10" />
                <span class="mt-5"
                      v-html="$t('family.message.creating_new_family', { name: familyName })"
                />
            </div>
            <template v-else>
                <div v-if="success"
                     class="flex-center flex-col text-success-500"
                >
                    <i class="icon-check text-xxxl" />
                    <span class="font-bold mt-5">
                        {{ $t('family.message.successfully_created') }}
                    </span>

                    <VButton type="span"
                             primary
                             class="mt-5"
                             @click="emit('close')"
                    >
                        {{ $t('common.button.close')}}
                    </VButton>
                </div>
            </template>
        </div>
    </VPage>
</template>
<script>
import {ref} from 'vue';
import VPage from '@/Components/Layout/Page';
import VSpinner from '@/Elements/Spinner';
import VButton from '@/Elements/Button';

export default {
    name: 'FamilyCreateComplete',
    components: {VButton, VSpinner, VPage},
    props: {
        form: Object,
        familyName: String,
    },
    emits: ['close'],
    setup(props, { emit }) {
        const progress = ref(true);
        const success = ref(false);
        props.form.submit().then((response) => {
            success.value = true;
            progress.value = false;
        }).catch(() => {
            success.value = false;
        });

        return {
            emit,
            progress,
            success,
        }
    }
}
</script>
