<template>
    <div class="flex flex-col w-full">
        <div class="separator w-full justify-center mb-5">
            <span class="bg-gray-100 font-semibold">
                arba prisijunkite su:
            </span>
        </div>

        <div class="flex items-center justify-center">
            <VImage src="google-logo.svg" class="mr-1" />
            <VImage src="facebook-logo.svg"
                    class="w-7 h-7 m-1"
            />
        </div>
    </div>
</template>
<script>
import VImage from '@/Elements/Image';
export default {
    name: 'SocialLogin',
    components: {VImage},
}
</script>
