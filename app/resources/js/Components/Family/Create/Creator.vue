<template>
    <VPage class="px-10">
        <span class="h1 text-xxxl mt-auto">
            {{ $t('family.title.creator_name_and_gender') }}
        </span>

        <VInput v-model="memberName"
                :title="$t('field.title.name')"
                name="members[0][name]"
                class="my-5"
        />

        <input type="hidden"
               name="members[0][user_id]"
               :value="userId"
        />

        <VOptions name="members[0][gender]"
                  vertical
                  :errors="errors"
                  :payload="genderOptions"
                  @change="onSelectGender"
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
        userId: Number,
        name: String,
        gender: String,
        status: String,
        progress: Boolean,
        errors: Object,
    },
    emits: ['back'],
    setup(props, { emit }) {
        const app = inject('app');
        const memberName = ref(props.name);

        watch(
            () => memberName.value,
            (value) => emit('update:name', value)
        );

        return {
            memberName,
            emit,
            genderOptions: app.constant.asPayload('gender', 'member.title.gender.'),
            onChangeStatus(status) {
                emit('update:status', status);
            },
            onSelectGender(gender) {
                emit('update:gender', gender);
            },
        }
    }
}
</script>
