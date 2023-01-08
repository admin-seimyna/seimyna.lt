<template>
    <div class="animated-icon flex-center rounded-full"
         :class="{
            'animated-icon--success': success,
         }"
    >
        <i class="text-xxxl"
           :class="{
                'icon-check text-success-500 mt-2': success
           }"
        />
    </div>
</template>
<script>
export default {
    name: 'AnimatedIcon',
    props: {
        success: Boolean
    }
}
</script>
