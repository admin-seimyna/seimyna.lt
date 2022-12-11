<template>
    <VPage
        bubble-top-right
        class="items-center justify-center px-10 overflow-hidden"
    >

        <Logo white class="mt-auto mb-8 w-24" />

        <div class="w-full text-center">
            <h1 class="text-xxxl text-white">
                {{ $t('auth.title.login')}}
            </h1>
        </div>

        <VForm action="/auth/login"
               class="bg-white rounded-md flex flex-col w-full p-10 shadow-xl mt-8"
               @success="onSuccess"
        >
            <template #default="{data, errors, progress}">
                <VInput v-model="data.email"
                        :error="errors.email"
                        name="email"
                >
                    <template #prepend>
                        <i class="icon-envelope" />
                    </template>
                </VInput>

                <div class="flex items-center mt-5">
                    <VInput v-model="data.password"
                            :error="errors.password"
                            type="password"
                            name="password"
                    >
                        <template #prepend>
                            <i class="icon-lock" />
                        </template>
                    </VInput>

                    <span class="link ml-2"
                          @click="emit('reset')"
                    >
                        {{ $t('auth.button.forgot-password')}}
                    </span>
                </div>

                <VButton primary
                         shadow
                         :progress="progress"
                         class="mt-5"
                >
                    {{ $t('auth.button.login')}}
                </VButton>
            </template>
        </VForm>

        <SocialLogin class="mt-10" />

        <div class="flex items-center text-center py-5 mt-auto">
            <p class="font-bold mr-2">
                {{ $t('auth.message.dont-have-an-account')}}
            </p>
            <VButton rounded
                     @click="emit('signup')"
                     class="w-16 h-16 bg-white shadow-xl ml-3"
            >
                <i class="icon-arr-right text-primary-500 text-xl" />
            </VButton>
        </div>
    </VPage>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import SocialLogin from '@/Components/Auth/Login/SocialLogin';
import Logo from '@/Elements/Logo';
import {useRouter} from 'vue-router';
import VPage from '@/Elements/Page';

export default {
    name: 'LoginForm',
    components: {VPage, Logo, SocialLogin, VButton, VInput, VForm},
    emits: [
        'reset',
        'signup'
    ],
    setup(props, { emit}) {
        const router = useRouter();
        return {
            emit,
            onSuccess() {
                router.push({ name: 'dashboard' });
            }
        }
    }
}
</script>
