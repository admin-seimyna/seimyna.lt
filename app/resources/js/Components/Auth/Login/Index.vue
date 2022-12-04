<template>
    <Slider ref="sliderRef"
            :slides="slides"
            horizontal
            disable-user-interaction
    >
        <template #resetPassword>
            <ResetPasswordForm  @back="slideTo('login')"/>
        </template>
        <template #login>
            <LoginForm @reset="slideTo('resetPassword')"
                       @signup="slideTo('signup')"
            />
        </template>

        <template #signup>
            <SignUpForm @back="slideTo('login')"/>
        </template>
    </Slider>
</template>
<script>

import Slider from '@/Elements/Slider/Slider';
import VImage from '@/Elements/Image';
import LoginForm from '@/Components/Auth/Login/LoginForm';
import {ref} from 'vue';
import VButton from '@/Elements/Button';
import SignUpForm from '@/Components/Auth/Login/SignUpForm';
import ResetPasswordForm from '@/Components/Auth/Login/ResetPassword';

export default {
    name: 'Login',
    components: {ResetPasswordForm, SignUpForm, VButton, LoginForm, VImage, Slider },
    setup(props) {
        const sliderRef = ref(null);
        return {
            sliderRef,
            slides: [
                {
                    name: 'resetPassword'
                },{
                    name: 'login',
                    active: true,
                }, {
                    name: 'signup'
                }
            ],
            slideTo(name) {
                sliderRef.value.slideTo(name);
            }
        }
    }
}
</script>
