<template>
    <div class="flex flex-col w-full">
        <div class="separator w-full justify-center mb-5">
            <span class="font-semibold bg-gray-100">
                {{ $t('auth.title.login-with-social')}}:
            </span>
        </div>

        <div class="flex items-center justify-center">
            <VImage src="google-logo.svg" class="mr-1 w-10" />
            <VImage src="facebook-logo.svg"
                    class="w-10 h-10 m-1"
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
