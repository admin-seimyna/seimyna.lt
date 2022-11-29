<template>
    <img :src="`/img/${src}`" :alt="alt" />
</template>
<script>
export default {
    name: 'VImage',
    props: {
        src: String,
        alt: String,
    }
}
</script>
