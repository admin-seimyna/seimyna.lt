<template>
    <div class="family-tree__member"
         :class="{
            'family-tree__member--active': active,
         }"
    >
        <FamilyTreeButton
            v-if="active"
            class="family-tree__button--remove"
            @click="emit('remove')"
        >
            -
        </FamilyTreeButton>

        <Avatar
            :subject="member"
            @click="emit('select')"
        />

        <FamilyTreeButton
            v-if="active"
            class="family-tree__button--add"
            @click="emit('add')"
        />
    </div>
</template>
<script>
import FamilyTreeButton from '@/Elements/Family/Button';
import Avatar from '@/Elements/Avatar';
export default {
    name: 'FamilyTreeMember',
    components: {Avatar, FamilyTreeButton},
    emits: ['add', 'remove', 'select'],
    props: {
        active: Boolean,
        member: Object,
    },
    setup(props, { emit }) {
        return {
            emit,
        }
    }
}
</script>
