<template>
    <VPage bubble-center
           class="justify-center items-center p-10 overflow-hidden"
    >
        <span class="h1 text-xxxl text-white mt-auto">
            {{ $t(`auth.title.verification.${type}`)}}
        </span>

        <VForm ref="formRef"
               :action="`/verify/${type}/${token}`"
               @progress="onSubmit"
               @success="onSuccess"
               @error="onError"
               class="bg-white rounded-md flex flex-col items-center justify-center w-full p-10 shadow-xl mt-8"
        >
            <template #default="{data, errors, progress}">

                <VSpinner v-if="loading || progress"
                          class="w-16 h-16"
                />

                <template v-else>
                    <template v-if="!verification.is_expired">
                        <VerificationCode
                            ref="codeRef"
                            :length="codeLength"
                            @complete="submitForm"
                        />

                        <p class="mt-5 text-text-light">
                            {{ $t('auth.message.verify.email')}}
                        </p>
                    </template>

                    <template v-else>
                        <div class="flex flex-col items-center">
                            <p class="text-center">
                                {{ $t('auth.message.verification_is_expired')}}
                            </p>
                            <VButton
                                type="span"
                                primary
                                class="mt-5"
                                @click="resend"
                            >{{ $t('auth.button.resend_verification_code')}}</VButton>
                        </div>
                    </template>
                </template>
            </template>
        </VForm>

        <div v-if="!loading && !verification.is_expired"
             class="flex items-center mt-16"
        >
            <span class="font-semibold text-white">
                {{ $t('auth.message.resend_verification_code')}}
            </span>
            <VButton
                class="bg-white ml-2"
                @click="resend"
            >{{ $t('auth.button.resend_verification_code')}}</VButton>
        </div>

        <div class="flex w-full mt-auto">
            <VButton
                rounded
                shadow
                class="bg-white w-16 h-16"
                @click="logout"
            >
                <i class="icon-arr-left text-primary-500 text-xl" />
            </VButton>
        </div>
    </VPage>
</template>
<script>
import VForm from '@/Elements/Form';
import {useRoute, useRouter} from 'vue-router';
import VInput from '@/Elements/Input';
import {computed, inject, reactive, ref} from 'vue';
import VButton from '@/Elements/Button';
import VSpinner from '@/Elements/Spinner';
import axios from 'axios';
import {useStore} from 'vuex';
import {useI18n} from 'vue-i18n';
import VPage from '@/Components/Layout/Page';
import VerificationCode from '@/Elements/VerificationCode';

export default {
    name: 'Verification',
    components: {
        VerificationCode,
        VPage,
        VSpinner,
        VButton,
        VInput,
        VForm
    },
    setup(props) {
        const app = inject('app');
        const formRef = ref(null);
        const codeRef = ref(null);
        const route = useRoute();
        const loading = ref(true);
        const router = useRouter();
        const verification = ref(null);
        const store = useStore();
        const t = useI18n().t;

        axios.get(`/verify/${route.params.token}`).then((response) => {
            verification.value = response.data;
            loading.value = false;
        });

        return {
            formRef,
            type: route.params.type,
            token: route.params.token,
            loading,
            verification,
            codeLength: app.config.get('system.auth.verification.code_length'),
            onSubmit(status) {
                loading.value = status;
                if (!status) {
                   codeRef.value.clear();
                }
            },
            onSuccess() {
                router.push({ name: 'dashboard'});
            },
            onError() {
                app.dialog.defaultAlert(t('validation.code'));
            },
            logout() {
                store.commit('app/loading', true);
                store.dispatch('auth/logout').then(() => {
                    router.push({ name: 'login' });
                });
            },
            submitForm() {
                formRef.value.submit();
            },
            resend() {
                loading.value = true;
                axios.post(`/verify/${verification.value.type}/${verification.value.token}/resend`)
                    .then((response) => {
                        verification.value = response.data;
                        app.dialog.defaultAlert(t('auth.message.verification_resend_success'));
                    }).catch((error) => {
                        app.dialog.defaultAlert(error.response.data.message);
                    }).finally(() => {
                        loading.value = false;
                    })
            }
        }
    },
}
</script>
