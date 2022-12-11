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
                <input :name="name"
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
import {computed, ref} from 'vue';
import { useI18n } from 'vue-i18n';
import InputError from '@/Elements/InputError';

export default {
    name: 'VInput',
    components: {InputError},
    props: {
        title: String,
        type: {
            type: String,
            default: 'text',
        },
        name: String,
        error: String,
        errors: Object,
    },
    setup(props, { slots, emit }) {
        const focused = ref(false);
        const { t } = useI18n();
        const errorMessage = computed(() => {
            if (!props.error && !props.errors) return null;
            if (props.error) return props.error;
            return props.errors[props.name];
        });
        const label = computed(() => {
            return props.title || t(`field.title.${props.name}`);
        });

        return {
            label,
            errorMessage,
            hasError: computed(() => {
                return !!errorMessage.value;
            }),
            placeholder: computed(() => {
                return `${props.placeholder || t('field.placeholder.insert')}...`;
            }),
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
