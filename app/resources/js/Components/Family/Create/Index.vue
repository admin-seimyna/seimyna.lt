<template>
    <div class="w-full h-full">
        <VButton rounded
                 shadow
                 class="w-10 h-10 bg-white absolute top-2 right-2 z-10"
                 @click="close"
        >
            <i class="icon-times" />
        </VButton>

        <VForm action="/family/create"
               class="w-full h-full"
               @success="changeSlide"
        >
            <template #default="{data,errors, progress}">
                <input type="hidden"
                       name="step"
                       :value="step"
                />

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
                            v-model:gender="members[0].gender"
                            v-model:name="members[0].name"
                            :userId="user.id"
                            :progress="progress"
                            :errors="errors"
                            @back="back"
                        />
                    </template>
                    <template #members>
                        <FamilyMembers :members="members"
                                       @back="back"
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

export default {
    name: 'FamilyCreate',
    components: {
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
        const store = useStore();
        const t = useI18n().t;
        const step = ref(1);
        const sliderRef = ref(null);
        const user = computed(() => store.getters['auth/user']);
        const members = reactive([
            {
                name: user.value.name,
                gender: null,
                status: null,
            }
        ])

        return {
            emit,
            step,
            sliderRef,
            user,
            members,
            slides: [
                {
                    name: 'family'
                }, {
                    name: 'creator'
                }, {
                    name: 'members'
                }
            ],
            changeSlide(response) {
                step.value = response.step;
                sliderRef.value.next();
            },
            back() {
                step.value--;
                sliderRef.value.back();
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
