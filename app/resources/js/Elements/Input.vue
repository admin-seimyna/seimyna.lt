<template>
    <div class="input"
         :class="{
            'input--focus': focused,
            'input--error': hasError,
        }"
    >
        <div class="input__wrapper">
            <label :for="name"
                   class="text-xs font-semibold mt-2 ml-3 text-text-light"
            >
                {{ label }}
            </label>

            <div class="input__container w-full">
                <div v-if="hasPrependSlot"
                     class="input__prepend flex items-center pr-2"
                >
                    <slot name="prepend" />
                </div>
                <input v-model="inputValue"
                       :name="name"
                       :type="type"
                       :placeholder="placeholder"
                       @focus="onFocus"
                       @blur="onBlur"
                />
                <div v-if="hasAppendSlot"
                     class="input__append flex items-center pl-2"
                >
                    <slot name="append" />
                </div>
            </div>
        </div>

        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />
    </div>
</template>
<script>
import {computed, ref, watch} from 'vue';
import InputError from '@/Elements/InputError';
import { fieldProps, useField } from '@/Elements/Field';

export default {
    name: 'VInput',
    components: {InputError},
    props: {
        ...fieldProps,
        modelValue: [String, Number],
        type: {
            type: String,
            default: 'text',
        },
    },
    emits: ['update:modelValue'],
    setup(props, { slots, emit }) {
        const focused = ref(false);
        const inputValue = ref(props.modelValue);

        watch(
            () => inputValue.value,
            (value) => {
                    emit('update:modelValue', value);
                }
        );

        watch(
            () => props.modelValue,
            (value) => inputValue.value = value,
        );

        return {
            ...useField(props),
            inputValue,
            focused,
            hasAppendSlot: computed(() => {
                return !!slots.append
            }),
            hasPrependSlot: computed(() => {
                return !!slots.prepend
            }),
            onFocus() {
                focused.value = true;
            },
            onBlur() {
                focused.value = false;
            },
        }
    }
}
</script>
