<template>
    <div class="flex w-full h-full shrink-0">
        <slot />
    </div>
</template>
<script>
export default {
    name: 'SliderSlide',
    props: {
        name: {
            type: String,
            required: true,
        },
    }
}
</script>
