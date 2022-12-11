<template>
    <VPage bubble-bottom-right
           class="justify-center items-center p-10 overflow-hidden"
    >
        <div class="flex flex-col">
            <span class="h1 text-xxxl text-primary-500 leading-9 mb-5">
                {{ $t('auth.title.reset-password') }}
            </span>

            <VForm action="/reset-password"
                   class="w-full p-10 flex flex-col bg-white rounded-md shadow-xl"
            >
                <p class="text-text-normal">
                    {{ $t('auth.message.reset-password-instruction') }}
                </p>

                <VInput name="email"
                        class="mt-5"
                >
                    <template #prepend>
                        <i class="icon-envelope" />
                    </template>
                </VInput>

                <VButton primary
                         shadow
                         class="mt-5"
                >
                    {{ $t('auth.button.send') }}
                </VButton>
            </VForm>
        </div>

        <VButton rounded
                 @click="emit('back')"
                 class="w-16 h-16 bg-white shadow-xl mt-10"
        >
            <i class="icon-arr-right text-primary-500 text-xl" />
        </VButton>
    </VPage>

</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import VPage from '@/Elements/Page';
export default {
    name: 'ResetPasswordForm',
    components: {VPage, VButton, VInput, VForm},
    emits: ['back'],
    setup(props, { emit }) {
        return {
            emit,
        };
    }
}
</script>
