<template>
    <component :is="type"
               class="button select-none"
               :class="{
                 progress,
                 disabled,
                 'button--rounded': rounded,
                 'button--primary': primary,
                 'button--warning': warning,
                 'button--danger': danger,
                 'button--shadow': shadow,
                 'button--basic': basic,
                 'button--bordered': bordered,
                 'justify-center': center,
                 'button--soft': soft,
               }"
    >
        <VSpinner v-if="progress"
                  class="button__spinner"
                  :class="{
                    'mr-2': !rounded
                  }"
        />
        <slot v-if="!rounded || (rounded && !progress)" />
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
        danger: Boolean,
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
