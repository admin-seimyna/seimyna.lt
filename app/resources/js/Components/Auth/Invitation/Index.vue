<template>
    <VPage class="px-10">
        <VForm ref="formRef"
               :action="`/auth/invitation/${type}/accept`"
               class="w-full h-full flex flex-col items-center"
               @success="onSuccess"
        >
            <template #default="{data,progress,errors}">
                <span class="h1 text-xxxl text-primary-500 mt-auto">
                    {{ $t('auth.title.invitation')}}
                </span>

                <input type="hidden"
                       name="step"
                       :value="step"
                />

                <div class="flex w-full mt-8">
                    <Slider ref="sliderRef"
                            :slides="slides"
                            horizontal
                            disable-user-interaction
                    >
                        <template #email>
                            <div class="w-full flex-center">
                                <VInput v-model="data.email"
                                        name="identifier"
                                        :title="identifierTitle"
                                        :errors="errors"
                                />
                            </div>
                        </template>
                        <template #code>
                            <div class="w-full flex-center flex-col">
                                <VerificationCode
                                    v-model:completeness="completeness"
                                    ref="codeRef"
                                    :length="codeLength"
                                    type="text"
                                    @complete="submitForm"
                                />

                                <InputError v-if="errors.code"
                                            class="mt-3 justify-center"
                                            :message="errors.code"
                                />
                            </div>
                        </template>
                        <template #password>
                            <div class="w-full flex-center flex-col">
                                <VInput v-model="data.password"
                                        type="password"
                                        name="password"
                                        :errors="errors"
                                />

                                <VInput v-model="data.confirmation"
                                        type="password"
                                        name="confirmation"
                                        :errors="errors"
                                        class="mt-5"
                                />
                            </div>
                        </template>
                    </Slider>
                </div>

                <div class="flex-center mt-auto pb-10">
                    <VButton v-if="step > 1"
                             rounded
                             class="w-14 h-14 bg-white shadow-xl mr-5"
                             @click="back"
                    >
                        <i class="icon-arr-left text-xl" />
                    </VButton>

                    <VButton rounded
                             class="w-16 h-16 bg-white shadow-xl"
                             :progress="progress"
                             :disabled="submitIsDisabled"
                    >
                        <i class="icon-arr-right text-primary-500 text-xl" />
                    </VButton>
                </div>
            </template>
        </VForm>
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import VerificationCode from '@/Elements/VerificationCode';
import {computed, inject, ref} from 'vue';
import VInput from '@/Elements/Input';
import VForm from '@/Elements/Form';
import VButton from '@/Elements/Button';
import Slider from '@/Elements/Slider/Slider';
import {useI18n} from 'vue-i18n';
import InputError from '@/Elements/InputError';
import {useRouter} from 'vue-router';

export default {
    name: 'Invitation',
    components: {
        InputError,
        Slider,
        VButton,
        VForm,
        VInput,
        VerificationCode,
        VPage
    },
    setup(props) {
        const app = inject('app');
        const t = useI18n().t;
        const type = 'email';
        const formRef = ref(null);
        const step = ref(1);
        const sliderRef = ref(null);
        const codeRef = ref(null);
        const completeness = ref(false);
        const router = useRouter();
        const slides = [
            {
                name: 'email'
            }, {
                name: 'code'
            }, {
                name: 'password'
            }
        ];


        return {
            step,
            slides,
            sliderRef,
            formRef,
            codeRef,
            completeness,
            identifierTitle: computed(() => {
                switch (type) {
                    case 'email': return t('field.title.email');
                }
            }),
            type,
            submitIsDisabled: computed(() => {
                return step.value === 2 && !completeness.value;
            }),
            codeLength: app.config.get('system.auth.invitation.code_length'),
            back() {
                if (step.value < 2) return;

                codeRef.value.clear();
                step.value--;
                sliderRef.value.back();
            },

            submitForm() {
                formRef.value.submit().then(() => {
                    //
                }).catch((error) => {
                    codeRef.value.clear();
                    codeRef.value.focus();
                });
            },

            onSuccess() {
                if (step.value > 2) {
                    router.push({ name: 'dashboard' });
                    return;
                }

                step.value++;
                codeRef.value.focus();
                sliderRef.value.next();
            }
        }
    }
}
</script>
