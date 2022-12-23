<template>
    <div class="avatar flex items-center justify-center rounded-full overflow-hidden shrink-0">
        <img v-if="src"
             :src="src"
             :alt="alt"
             class="absolute top-0 left-0 w-full h-full object-cover"
        />

        <span class="flex items-center justify-center absolute top-0 left-0 w-full h-full"
              :style="{backgroundColor: color}"
        >
            <span class="font-bold text-xs align-middle uppercase"
                  :class="{
                    'text-xs': !large,
                    'text-xl': large,
                    'text-white': !ignoreColor
                  }"
            >
                {{ text }}
            </span>
        </span>
    </div>
</template>
<script>
import {computed} from 'vue';
export default {
    name: 'Avatar',
    props: {
        subject: Object,
        nameKey: {
            type: String,
            default: 'name'
        },
        ignoreColor: Boolean,
        large: Boolean,
    },
    setup(props) {
        const alt = computed(() => {
            return props.subject[props.nameKey];
        });
        return {
            color: computed(() => {
                if (props.ignoreColor) return;

                const string = props.subject[props.nameKey];
                // get first alphabet in upper case
                const firstAlphabet = string.charAt(0).toLowerCase();
                // get the ASCII code of the character
                const asciiCode = firstAlphabet.charCodeAt(0);
                // number that contains 3 times ASCII value of character -- unique for every alphabet
                const colorNum = asciiCode.toString() + asciiCode.toString() + asciiCode.toString();
                const num = Math.round(0xffffff * parseInt(colorNum));
                return `rgb(${num >> 16 & 255}, ${num >> 8 & 255}, ${num & 255}, 1)`;
            }),
            alt,
            text: computed(() => {
                return alt.value.substring(0, alt.value.length === 1 ? 1 : 2);
            }),
            src: computed(() => {
                if(!props.subject.avatar) return null;
                return props.subject.avatar.url;
            })
        }
    }
}
</script>
