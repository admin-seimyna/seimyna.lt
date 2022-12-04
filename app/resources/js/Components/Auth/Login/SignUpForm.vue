<template>
    <div class="bubble-center-right w-full flex flex-col items-center justify-center px-10 overflow-hidden">
        <div class="w-full mb-5">
            <h1 class="text-xxxl text-primary-500">
                {{ $t('auth.title.signup')}}
            </h1>
        </div>

        <VForm action="/auth/signup"
               class="w-full p-10 flex flex-col bg-white rounded-md shadow-xl"
        >
            <template #default="{data,progress,errors}">
                <VInput name="name"
                        :errors="errors"
                >
                    <template #prepend>
                        <i class="icon-user" />
                    </template>
                </VInput>

                <VInput name="email"
                        class="mt-5"
                        :errors="errors"
                >
                    <template #prepend>
                        <i class="icon-envelope" />
                    </template>
                </VInput>

                <VInput name="password"
                        type="password"
                        class="mt-5"
                        :errors="errors"
                >
                    <template #prepend>
                        <i class="icon-lock" />
                    </template>
                </VInput>

                <VButton primary
                         shadow
                         class="mt-5"
                         :progress="progress"
                >
                    {{ $t('auth.button.signup')}}
                </VButton>
            </template>
        </VForm>

        <VButton rounded
                 @click="emit('back')"
                 class="w-16 h-16 bg-white shadow-xl mt-10"
        >
            <i class="icon-arr-left text-primary-500 text-xl" />
        </VButton>
    </div>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
export default {
    name: 'SignUpForm',
    components: {VButton, VInput, VForm},
    emits: ['back'],
    setup(props, { emit }) {
        return {
            emit,
        };
    }
}
</script>
