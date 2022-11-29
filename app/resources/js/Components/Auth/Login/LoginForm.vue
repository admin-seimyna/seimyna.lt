<template>
    <VForm action="/login"
           class="login-form flex flex-col w-full px-10 mt-10"
    >
        <template #default="{data, errors, progress}">
            <VInput v-model="data.email"
                    :error="errors.email"
                    name="email"
                    title="El. paštas"
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
                        title="Slaptažodis"
                >
                    <template #prepend>
                        <i class="icon-lock" />
                    </template>
                </VInput>

                <span class="link ml-2"
                      @click="emit('reset')"
                >
                    Pamiršote?
                </span>
            </div>

            <VButton primary
                     shadow
                     :progress="progress"
                     class="mt-5"
            >
                Prisijungti
            </VButton>
        </template>
    </VForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
export default {
    name: 'LoginForm',
    components: {VButton, VInput, VForm},
    emits: ['reset'],
    setup(props, { emit}) {
        return {
            emit,
        }
    }
}
</script>
