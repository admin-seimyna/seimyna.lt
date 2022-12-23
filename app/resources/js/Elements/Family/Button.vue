<template>
    <div
        class="family-tree__button w-10 h-10 border border-primary-500 border-solid text-primary-500 font-bold text-xl flex rounded-full flex items-center justify-center bg-white shrink-0">
        <slot>+</slot>
    </div>
</template>
<script>
export default {
    name: 'FamilyTreeButton',
    props: {
        inline: Boolean,
    }
}
</script>
