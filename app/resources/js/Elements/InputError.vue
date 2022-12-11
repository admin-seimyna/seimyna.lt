<template>
    <span class="w-full flex text-danger-500 text-xs font-semibold">{{ message }}</span>
</template>
<script>
export default {
    name: 'InputError',
    props: {
        message: String,
    }
}
</script>
