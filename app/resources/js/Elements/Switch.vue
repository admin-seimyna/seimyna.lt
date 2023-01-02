<template>
    <div class="flex items-center">
        <input :value="inputValue ? trueValue : falseValue"
               type="hidden"
               :name="name"
        />

        <div class="switch"
             :class="{
                'switch--on': inputValue
             }"
             @click="toggle"
        >
            <span class="switch__circle"></span>
        </div>

        <label :for="name"
               class="ml-2"
               :class="{
                    'font-semibold': inputValue
               }"
        >{{ label }}</label>
    </div>
</template>
<script>
import { useField, fieldProps } from '@/Elements/Field';
import {ref, watch} from 'vue';

export default {
    name: 'Switch',
    props: {
        ...fieldProps,
        modelValue: [Number, Boolean],
        trueValue: {
            type: [Number, Boolean],
            default: 1,
        },
        falseValue: {
            type: [Number, Boolean],
            default: 0,
        }
    },
    setup(props, { emit }) {
        const inputValue = ref(!!props.modelValue);

        watch(
            () => props.modelValue,
            (value) => {
                inputValue.value = !!value;
            }
        )

        watch(
            () => inputValue.value,
            (value) => {
                emit('update:modelValue', value ? props.trueValue : props.falseValue);
            }
        )

        return {
            inputValue,
            ...useField(props),
            toggle() {
                inputValue.value = !inputValue.value;
            }
        }
    }
}
</script>
