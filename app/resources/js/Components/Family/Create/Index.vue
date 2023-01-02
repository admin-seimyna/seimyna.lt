<template>
    <div class="w-full h-full">
        <VButton v-if="canClose"
                 rounded
                 shadow
                 class="w-10 h-10 bg-white absolute top-2 right-2 z-10"
                 @click="close"
        >
            <i class="icon-times" />
        </VButton>

        <VForm ref="formRef"
               action="/family/create"
               class="w-full h-full"
               @success="changeSlide"
        >
            <template #default="{data,errors, progress}">
                <input type="hidden"
                       name="step"
                       :value="step"
                />

                <template v-for="(member, index) in members">
                    <input type="hidden"
                           :name="`members[${index}][name]`"
                           :value="member.name"
                    />
                    <input type="hidden"
                           :name="`members[${index}][gender]`"
                           :value="member.gender"
                    />
                    <input type="hidden"
                           :name="`members[${index}][invite]`"
                           :value="member.invite"
                    />

                    <input v-if="member.invite"
                           type="hidden"
                           :name="`members[${index}][email]`"
                           :value="member.email"
                    />
                    <input v-if="member.user_id"
                           type="hidden"
                           :name="`members[${index}][user_id]`"
                           :value="member.user_id"
                    />
                </template>

                <Slider ref="sliderRef"
                        :slides="slides"
                        horizontal
                        disable-user-interaction
                >
                    <template #family>
                        <Family v-model="data.name"
                                :progress="progress"
                                :error="errors.name"
                                @cancel="emit('close')"
                        />
                    </template>
                    <template #creator>
                        <Creator
                            :members="members"
                            :progress="progress"
                            :errors="errors"
                            @back="back"
                        />
                    </template>
                    <template #members>
                        <FamilyMembers :members="members"
                                       :progress="progress"
                                       @back="back"
                        />
                    </template>
                    <template #complete="{active}">
                        <FamilyCreateComplete v-if="active"
                                              :form="formRef"
                                              :family-name="data.name"
                                              @close="forceClose"
                        />
                    </template>
                </Slider>
            </template>
        </VForm>
    </div>
</template>
<script>
import VButton from '@/Elements/Button';
import Slider from '@/Elements/Slider/Slider';
import VForm from '@/Elements/Form';
import Family from '@/Components/Family/Create/Family';
import {computed, inject, reactive, ref} from 'vue';
import {useI18n} from 'vue-i18n';
import FamilyMembers from '@/Components/Family/Create/FamilyMembers';
import {useStore} from 'vuex';
import Creator from '@/Components/Family/Create/Creator';
import FamilyCreateComplete from '@/Components/Family/Create/Complete';

export default {
    name: 'FamilyCreate',
    components: {
        FamilyCreateComplete,
        Creator,
        FamilyMembers,
        Family,
        VForm,
        Slider,
        VButton
    },
    emits: ['close'],
    setup(props, {emit}) {
        const app = inject('app');
        const formRef = ref(null);
        const store = useStore();
        const t = useI18n().t;
        const step = ref(1);
        const sliderRef = ref(null);
        const user = computed(() => store.getters['auth/user']);
        const slides = reactive([
            {
                name: 'family'
            }, {
                name: 'creator'
            }, {
                name: 'members'
            }, {
                name: 'complete'
            }
        ]);
        const members = reactive([
            {
                name: user.value.name,
                gender: null,
                user_id: user.value.id,
                invite: 0,
            }
        ])

        return {
            emit,
            formRef,
            step,
            sliderRef,
            user,
            members,
            slides,
            canClose: computed(() => step.value < 4),
            changeSlide(response) {
                if (!response.step) return;
                step.value = response.step;
                sliderRef.value.next();
            },
            back() {
                step.value--;
                sliderRef.value.back();
            },
            forceClose() {
                emit('close');
            },
            close() {
                app.dialog.defaultConfirm(
                    t('family.prompt.title.cancel_creation'),
                    t('family.prompt.message.cancel_creation'),
                ).then(() => {
                    emit('close');
                });
            }
        }
    }
}
</script>
