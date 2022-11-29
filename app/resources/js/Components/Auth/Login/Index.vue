<template>
    <Slider ref="sliderRef"
            :slides="slides"
            disable-user-interaction
    >
        <template #login>
            <div class="w-full flex flex-col items-center justify-center">
                <h1>Prisijungimas</h1>

                <LoginForm
                    @reset="slideTo('resetPassword')"
                />

                <SocialLogin class="mt-8" />
            </div>
        </template>

        <template #resetPassword>
            <ResetPassword @back="slideTo('login')" />
        </template>
    </Slider>
</template>
<script>

import Slider from '@/Elements/Slider/Slider';
import VImage from '@/Elements/Image';
import LoginForm from '@/Components/Auth/Login/LoginForm';
import SocialLogin from '@/Components/Auth/Login/SocialLogin';
import {ref} from 'vue';
import ResetPassword from '@/Components/Auth/Login/ResetPassword';

export default {
    name: 'Login',
    components: {ResetPassword, SocialLogin, LoginForm, VImage, Slider },
    setup(props) {
        const sliderRef = ref(null);
        return {
            sliderRef,
            slides: [
                {
                    name: 'login'
                }, {
                    name: 'resetPassword'
                }
            ],
            slideTo(name) {
                sliderRef.value.slideTo(name);
            }
        }
    }
}
</script>
