<template>
    <div class="flex flex-col w-full">
        <ul class="flex w-full gap-1"
            :class="{
                'flex-col': !vertical,
            }"
        >
            <li v-for="(option, index) in options"
                :key="`${index}-${option[valueKey]}`"
                class="flex flex-col w-full"
                :class="optionClass"
                @click="select(option)"
            >
                <slot v-bind="{option}">
                    <div
                        class="option"
                        :class="{
                            'option--selected': option.selected
                        }"
                    >
                        {{ option[nameKey] }}
                    </div>
                </slot>
            </li>

            <input v-for="value in selectedValues"
                   :key="value"
                   type="hidden"
                   :name="name"
                   :value="value"
            />
        </ul>

        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />
    </div>
</template>
<script>
import { fieldProps, useField } from '@/Elements/Field';
import {computed, reactive, watch} from 'vue';
import InputError from '@/Elements/InputError';

export default {
    name: 'VOptions',
    components: {InputError},
    props: {
        payload: Array,
        modelValue: [Number, String, Array],
        multiple: Boolean,
        vertical: Boolean,
        optionClass: [String, Array, Object],
        listClass: [String, Array, Object],
        ...fieldProps,
    },
    emits: ['change', 'update:modelValue'],
    setup(props, { emit }) {
        const selectedValues = reactive([]);

        if (props.modelValue) {
            (Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]).forEach((value) => {
                selectedValues.push(value);
            });
        }

        watch(
            () => props.payload,
            () => selectedValues.splice(0, selectedValues.length)
        );

        watch(
            () => selectedValues,
            (value) => {
                if (!props.multiple) {
                    value = value.length ? value[0] : null;
                }
                emit('update:modelValue', value);
                emit('change', value);
            },
            { deep: true }
        );

        return {
            ...useField(props),
            selectedValues,
            options: computed(() => {
                return [...props.payload].map((option) => {
                    return Object.assign(option, {
                        selected: selectedValues.indexOf(option.id) !== -1
                    });
                });
            }),
            select(option) {
                if (!props.multiple) {
                    selectedValues.splice(0, 1);
                }

                selectedValues.push(option[props.valueKey]);
            }
        };
    }
}
</script>
