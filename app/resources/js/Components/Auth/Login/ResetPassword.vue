<template>
    <VForm action="/reset-password"
           class="w-full px-10 flex flex-col"
    >
        <VInput name="email"
                title="El. paštas"
        >
            <template #prepend>
                <i class="icon-envelope" />
            </template>
        </VInput>

        <VButton>Siųsti</VButton>
        <span class="linked" @click="emit('back')">Back</span>
    </VForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
export default {
    name: 'ResetPassword',
    components: {VButton, VInput, VForm},
    emits: ['back'],
    setup(props, { emit }) {
        return {
            emit,
        };
    }
}
</script>
