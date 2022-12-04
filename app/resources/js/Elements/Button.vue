<template>
    <component :is="type"
               class="button select-none"
               :class="{
                 progress,
                 disabled,
                 'button--rounded': rounded,
                 'button--primary': primary,
                 'button--warning': warning,
                 'button--shadow': shadow,
                 'button--basic': basic,
                 'button--bordered': bordered,
                 'justify-center': center,
                 'button--soft': soft,
               }"
    >
        <VSpinner v-if="progress"
                  class="button__spinner mr-2"
        />
        <slot />
    </component>
</template>
<script>
import VSpinner from '@/Elements/Spinner';
export default {
    name: 'VButton',
    components: {VSpinner},
    props: {
        type: {
            type: String,
            default: 'button',
        },
        progress: Boolean,
        disabled: Boolean,
        primary: Boolean,
        warning: Boolean,
        shadow: Boolean,
        basic: Boolean,
        bordered: Boolean,
        rounded: Boolean,
        soft: Boolean,
        center: {
            type: Boolean,
            default: true,
        }
    }
}
</script>
