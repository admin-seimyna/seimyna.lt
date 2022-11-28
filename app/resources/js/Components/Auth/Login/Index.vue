<template>
    <div class="flex flex-col p-10">
        <VForm action="/test">
            <template #default="{errors, data, progress}">
                <VInput name="email" title="Email" />

                <VButton class="mt-5" primary :progress="progress">Submit</VButton>
            </template>
        </VForm>
        <VButton class="mt-5" primary @click="send">Submit</VButton>
    </div>
</template>
<script>

import {inject} from 'vue';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import VForm from '@/Elements/Form';
import axios from 'axios';

export default {
    name: 'Login',
    components: {VForm, VButton, VInput},
    setup(props) {
        const app = inject('app');
        return {
            send() {
                axios.post('/test', { name: 1}).then((payload) => {
                    console.log(payload);
                });
            }
        }
    }
}
</script>
