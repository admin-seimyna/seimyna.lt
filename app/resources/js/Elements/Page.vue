<template>
    <div class="w-full h-full flex flex-col"
         :class="{
            'bubble-top-right': bubbleTopRight,
            'bubble-bottom-right': bubbleBottomRight,
            'bubble-center': bubbleCenter,
            'bubble-center-right': bubbleCenterRight,
            'bubble-top-center': bubbleTopCenter,
         }"
    >
        <slot />
    </div>
</template>
<script>
export default {
    name: 'VPage',
    props: {
        bubbleTopRight: Boolean,
        bubbleBottomRight: Boolean,
        bubbleCenter: Boolean,
        bubbleCenterRight: Boolean,
        bubbleTopCenter: Boolean,
    }
}
</script>
