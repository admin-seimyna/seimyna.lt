<template>
    <div class="flex">
        <input
            v-for="(value, index) in code"
            :key="`input-${index}`"
            v-model="code[index]"
            ref="inputRef"
            :type="type"
            name="code[]"
            class="verify mr-2"
            :class="{'verify--filled': code[index]}"
            placeholder="*"
            @input="onChange(index)"
            @keyup="onKeyUp($event, index)"
        />
    </div>
</template>
<script>
import {computed, reactive, ref} from 'vue';

export default {
    name: 'VerificationCode',
    props: {
        completeness: Boolean,
        length: [String, Number],
        type: {
            type: String,
            default: 'number'
        }
    },
    emits: [
        'complete',
        'change',
        'update:completeness'
    ],
    setup(props, { emit }) {
        const inputRef = ref(null);
        const length = ref(parseInt(props.length));
        const code = reactive([]);
        const codeIsEntered = computed(() => {
            let value = 0;
            code.forEach(val => value += typeof val !== 'undefined' && val !== null && val !== '' ? 1 : 0);
            return value >= length.value;
        });

        for(let x = 0; x < length.value; x++) {
            code.push(null);
        }


        return {
            inputRef,
            code,
            codeIsEntered,
            onChange(index) {
                const input = inputRef.value[index];
                if (!input.value.length) {
                    return;
                }

                input.value = input.value[0];
                index++;

                if (codeIsEntered.value) {
                    emit('complete');
                    emit('update:completeness', true);
                    return;
                }

                emit('change');
                emit('update:completeness', false);
                if (index >= code.length) return;

                inputRef.value[index].focus();
            },

            clear() {
                code.forEach((value, index) => code[index] = null);
                emit('change');
                emit('update:completeness', false);
            },

            focus() {
                inputRef.value[0].focus();
            },

            onKeyUp(e, index) {
                const input = inputRef.value[index];
                if (input.value.length) {
                    return;
                }

                index--;
                if (index < 0) return;
                if (e.key === 'Backspace') {
                    inputRef.value[index].focus();
                }
            },
        }
    }
}
</script>
