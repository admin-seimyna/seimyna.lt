<template>
    <VPage class="px-10">
        <span class="h1 text-xxxl mt-auto text-center w-full">
            {{ $t('family.title.members') }}
        </span>

        <ul class="flex flex-col my-5">
            <li v-for="(member, index) in members"
                :key="`member-${index}`"
                @click="editMember(index)"
                class="flex items-center bg-white rounded border mb-1 p-3"
            >
                <Avatar :subject="member"
                        class="w-12 h-12"
                />
                <span class="font-semibold ml-3 ellipsis">
                    {{ member.name }}
                </span>
            </li>

            <li class="flex items-center justify-center bg-white rounded border border-primary-500 border-dashed text-primary-500 p-3"
                @click="addMember"
            >
                + {{ $t('family.button.add_member')}}
            </li>
        </ul>

        <FamilyCreateFooter
            :progress="progress"
            @back="emit('back')"
        />
    </VPage>
</template>
<script>
import VPage from '@/Components/Layout/Page';
import FamilyCreateFooter from '@/Components/Family/Create/Footer';
import Avatar from '@/Elements/Avatar';
import VButton from '@/Elements/Button';
import Member from '@/Components/Family/Create/Member';
import {inject} from 'vue';

export default {
    name: 'FamilyMembers',
    components: {
        VButton,
        Avatar,
        FamilyCreateFooter,
        VPage
    },
    props: {
        progress: Boolean,
        members: Array
    },
    emits: ['back'],
    setup(props, { emit }) {
        const app = inject('app');

        function openMemberModal(index) {
            app.modal({
                component: Member,
                props: {
                    members: props.members,
                    index
                },
                options: {
                    type: 'popup'
                }
            });
        }

        return {
            emit,
            addMember() {
                openMemberModal();
            },
            editMember(index) {
                if (!index) return;
                openMemberModal(index);
            }
        };
    },
}
</script>
