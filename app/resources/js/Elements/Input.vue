<template>
    <div class="input w-full flex flex-col items-center"
         :class="{'input--focus': focused}"
    >
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
</template>
<script>
import {computed, ref} from 'vue';

export default {
    name: 'VInput',
    props: {
        title: String,
        type: {
            type: String,
            default: 'text',
        },
        name: String,
    },
    setup(props, { slots, emit }) {
        const focused = ref(false);

        return {
            placeholder: computed(() => {
                return `${props.title}...`;
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
