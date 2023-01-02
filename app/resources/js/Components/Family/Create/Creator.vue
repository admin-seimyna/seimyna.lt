<template>
    <VPage class="px-10">
        <span class="h1 text-xxxl mt-auto">
            {{ $t('family.title.creator_name_and_gender') }}
        </span>

        <VInput v-model="member.name"
                :title="$t('field.title.name')"
                class="my-5"
        />

        <VOptions v-model="member.gender"
                  vertical
                  name="members[0][gender]"
                  :errors="errors"
                  :payload="genderOptions"
        >
            <template #default="{option}">
                <div class="option flex-col items-center justify-center"
                     :class="{
                        'option--selected': option.selected
                     }"
                >
                    <i :class="`icon-${option.id} text-xxxl`" />
                    <span class="mt-1">{{ option.name }}</span>
                </div>
            </template>
        </VOptions>

        <FamilyCreateFooter
            :progress="progress"
            @back="emit('back')"
        />
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import {computed, inject, ref, watch} from 'vue';
import VInput from '@/Elements/Input';
import VOptions from '@/Elements/Options';
import FamilyCreateFooter from '@/Components/Family/Create/Footer';

export default {
    name: 'Creator',
    components: {
        FamilyCreateFooter,
        VOptions,
        VInput,
        VPage
    },
    props: {
        members: Array,
        progress: Boolean,
        errors: Object,
    },
    emits: ['back'],
    setup(props, { emit }) {
        const app = inject('app');

        return {
            member: computed(() => props.members[0]),
            emit,
            genderOptions: app.constant.asPayload('gender', 'member.title.gender.'),
        }
    }
}
</script>
