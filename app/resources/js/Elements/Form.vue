<template>
    <form ref="formRef"
          :method="method"
          :action="action"
          autocomplete="off"
          @submit.prevent="submit"
    >
        <slot v-bind="{progress, errors, data }" />
    </form>
</template>
<script>
import {computed, reactive, ref} from 'vue';
import axios from 'axios';

export default {
    name: 'VForm',
    props: {
        action: {
            type: String,
            required: true,
        },
        method: {
            type: String,
            default: 'post'
        },
        data: Object,
        emits: [
            'error',
            'success',
            'progress',
        ],
    },
    setup(props, {emit}) {
        const formRef = ref(null);
        const progress = ref(false);
        const errors = reactive({value: {}});
        const data = reactive({value: props.data || {}});

        function submit() {
            progress.value = true;
            emit('progress', true);

            const formData = new FormData(formRef.value);

            clearErrors();

            if (props.method !== 'post') {
                formData.append('_method', props.method);
            }

            return new Promise((resolve, reject) => {
                axios.post(props.action, formData)
                    .then((response) => {
                        response = response.data ? response.data : response;
                        resolve(response);
                        emit('success', response);
                    }).catch((error) => {
                    const response = error.response ? error.response : error;
                    reject(response);
                    if (!response.data) return;
                    handleErrors(response.data.errors);
                    emit('error', errors.value);
                }).finally(() => {
                    progress.value = false;
                    emit('progress', false);
                });
            });
        }

        function handleErrors(messages) {
            for (const key in messages) {
                errors.value[key] = messages[key][0];
            }
        }

        function clearErrors() {
            errors.value = {};
        }

        function reset() {
            clearErrors();
            formRef.value.reset();
            data.value = props.data || {};
            progress.value = false;
        }

        function set(key, value) {
            let setData = data.value;
            const keys = key.split('.');
            const lastIndex = keys.length - 1;
            keys.forEach((field, index) => {
                if (index === lastIndex) {
                    setData[field] = value;
                    data.value = setData;
                    return;
                }
                setData = setData[field];
            });
        }

        return {
            formRef,
            progress,
            data,
            errors: computed(() => {
                return errors.value;
            }),
            submit,
            reset,
            set,
        }
    }
}
</script>
